<?php

namespace App\Controller\Admin;

use App\Common\Utils\Cache;
use App\Common\Utils\Log;
use App\Controller\AbstractController;
use App\Exception\ResponseException;
use App\Model\Role;
use App\Model\User;
use App\Model\UserRole;
use Hyperf\DbConnection\Db;
use Hyperf\Guzzle\ClientFactory;

/**
 * Class CommonContreller
 * @package App\Controller\Admin
 */
class CommonController extends AbstractController
{

    public function getInitConfig()
    {
        $data   =   [
            'is_sso'    =>  (int)config('sso.is_sso'),
            'url'       =>  config('sso.server_sso_url').'/core/connect/authorize?'.http_build_query([
                'response_type'     =>  'code',
                'client_id'         =>  config('sso.oauth_client_id'),
                'redirect_uri'      =>  $this->getDomain().'/accesslogin',
                'scope'             =>  config('sso.oauth_scope'),
                'state'             =>  'state'
            ]),
            'siteTitle' =>  config('setup.title')
        ];
        return $this->success($data);
    }

    public function accessLogin(ClientFactory $clientFactory)
    {
        Db::beginTransaction();
        try {
            $client = $clientFactory->create([
                'base_uri' => config('sso.server_sso_url'),
            ]);
            $response   =   $this->getAccessToken($client);
            $userInfo   =   $this->getUserInfo($client,$response['access_token']);

            $roles  =   Role::query()->where('status',1)
                ->whereIn('role_identity',$userInfo['UserRoles'])->pluck('id')->toArray();
            if (!$roles){
                Db::rollBack();
                $this->error('未分配权限，请联系管理员!');
            }
            $this->getOrganizeInfo($client, $userInfo['OrganizeId']);
            $user   =   $this->saveUserInfo($userInfo);
            $userRole   =   UserRole::query()->whereIn('role_id',$roles)->where('uid',$user->id)->pluck('role_id')->toArray();

            UserRole::query()->where('uid',$user->id)->whereNotIn('role_id',$roles)->delete();
            $roleDiff   =   array_diff($roles,$userRole);
            $roleDiff    &&  UserRole::query()->insert(array_map(function ($role_id) use($user){
                return [
                    'uid'       =>  $user->id,
                    'role_id'   =>  $role_id
                ];
            },$roleDiff));
            Log::info("【用戶：{$user->login_name}】SSO登录成功",['userInfo'=>$user->toArray()]);
            $token  =   $this->auth->guard()->login($user);
            Cache::set('ID_TOKEN_HINT'.$user->id,$response['id_token']);
            Db::commit();
            $this->success($token,'登录成功');
        }catch (\Throwable $throwable){
            Db::rollBack();
            if ($throwable instanceof ResponseException){
                $this->response->write($throwable->getMessage());
            }else{
                $this->error($throwable->getMessage());
            }
        }

    }

    protected function getAccessToken(\GuzzleHttp\Client $client): array
    {
        $code   =   $this->request->input('code');
        $response = $client->post(config('sso.server_sso_url').config('sso.token_url'),[
            'headers'       =>  [
                'Content-Type' =>  'application/x-www-form-urlencoded'
            ],
            'form_params'   =>  [
                'grant_type'    =>  'authorization_code',
                'code'          =>  $code,
                'client_id'     =>  config('sso.oauth_client_id'),
                'client_secret' =>  config('sso.oauth_client_secret'),
                'redirect_uri'  =>  $this->getDomain().'/accesslogin'
            ]
        ])->getBody()->getContents();

        if (!$response){
            Db::rollBack();
            $this->error('获取令牌失败!');
        }
        $response = json_decode($response, true);
        if (isset($response['error'])){
            Db::rollBack();
            $this->error($response['error']);
        }
        return $response;
    }

    protected function getUserInfo(\GuzzleHttp\Client $client, string $access_token): array
    {
        $result =   $client->get(config('sso.server_sso_url').config('sso.user_info_url'),[
            'headers'       =>  [
                'Authorization' =>  'Bearer '.$access_token
            ],
        ])->getBody()->getContents();
        Log::info("获取用户详细信息",['result'=>$result]);
        $result =   json_decode($result, true);
        if(!$result || !isset($result['Data']) || !$result['Data']){
            Db::rollBack();
            $this->error('获取用户信息失败，请联系管理员');
        }
        return $result['Data'];
    }

    protected function getOrganizeInfo(\GuzzleHttp\Client $client, string $organizeid): array
    {
        $result =   $client->get(config('sso.server_sso_url').config('sso.organize_info_url'),[
            'query'       =>  [
                'organizeId' =>  $organizeid
            ],
        ])->getBody()->getContents();
        $organizeInfo   =   json_decode($result,true);
        $_data  =   [
            'organizeid'    =>  $organizeInfo['Data']['SingleData']['OrganizeId'],
            'parentid'      =>  $organizeInfo['Data']['SingleData']['ParentId'],
            'organizename'  =>  $organizeInfo['Data']['SingleData']['SchoolName'],
            'shortname'     =>  $organizeInfo['Data']['SingleData']['Description'],
            'organizemail'  =>  $organizeInfo['Data']['SingleData']['Email'],
            'organizecode'  =>  $organizeInfo['Data']['SingleData']['OrganizeCode'],
            'status'        =>  $organizeInfo['Data']['SingleData']['DeleteMark'] == 1 ? 2 : ($organizeInfo['Data']['SingleData']['EnabledMark'] == 1 ? 1 : 0),
        ];
        $check  =   Db::table('organize')->where(['organizeid'=>$_data['organizeid'],'organizecode'=>$_data['organizecode']], null,'or')->first();
        if ($check){
            Db::table('organize')->where(['organizeid'=>$_data['organizeid'],'organizecode'=>$_data['organizecode']], null,'or')->update($_data);
        }else{
            Db::table('organize')->insert($_data);
        }
        return $_data;
    }

    protected function saveUserInfo(array $userInfo)
    {
        $_data  =   [
            'user_name'         =>  $userInfo['RealName'],
            'nickname'          =>  $userInfo['RealName'],
            'login_name'        =>  $userInfo['Account'],
            'mobile'            =>  $userInfo['MobilePhone'],
            'photo'             =>  $userInfo['HeadIcon'],
            'uuid'              =>  $userInfo['Uid'],
            'organizeid'        =>  $userInfo['OrganizeId'],
            'last_login_time'   =>  date('Y-m-d H:i:s'),
            'last_login_ip'     =>  $this->clientRealIP(),
            'user_type'         =>  $userInfo['UserType']
        ];

        $user   =   User::query()->where('uuid',$_data['uuid'])->first();
        if ($user){
            $_data['id']    =   $user->id;
            $user->update($_data);
            return $user;
        }else{
            $_data['created_at']    =   date('Y-m-d H:i:s');
            $_data['updated_at']    =   date('Y-m-d H:i:s');
            $_data['password']      =   password_hash(config('sso.default_password','123456'), PASSWORD_DEFAULT);
            $_data['source']        =   'sso';
            $id    =   User::query()->insertGetId($_data);
            return User::query()->where('id',$id)->first();
        }
    }
}
