<?php
declare(strict_types=1);
namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Common\Utils\Cache;
use App\Common\Utils\Log;
use App\Controller\AbstractController;
use App\Model\{AccessRule, Menu, Role, User, UserRole};

/**
 * Class UserController
 * @package App\Controller\Admin
 */
class UserController extends AbstractController
{
    /**
     * @CollectAccess(rule="user@index")
     * @author: Zhong Weiwei
     * @Date: 17:48  2022/4/4
     */
    public function index()
    {
        $limit  =   (int)$this->request->input('limit',10);
        $keyword=   $this->request->input('keyword','');
        $time   =   $this->request->input('time','');
        $status =   $this->request->input('status','');
        $sortField = $this->request->input('sortField','created_at');
        $sortOrder = $this->request->input('sortOrder','descend');
        $role_id = $this->request->input('role_id','');
        $where  =   [];
        $keyword != ''    &&  $where[]    =   ['user.user_name|nickname|user.login_name|user.mobile','like',"%$keyword%"];
        is_numeric($status) && $where[]   =   ['user.status','=',$status];
        $role_id    &&  $where[]    =   ['user_role.role_id','=',$role_id];
        if ($time){
            [$stime,$etime] = explode(' - ', $time);
            $where[]    =   [function ($query)use($stime,$etime) {
                $query->whereBetween('user.created_at',[$stime,$etime.' 23:59:59']);
            }];
        }
        $list   =   User::query()->leftJoin('user_role','user.id','=','user_role.uid')
            ->where($where)->orderBy('user.'.$sortField,$sortOrder == 'descend' ? 'desc' : 'asc')
            ->groupBy('user.id')
            ->paginate($limit,['user.id','user.user_name','user.login_name','user.nickname','user.mobile','user.created_at','user.status','user.sex']);
        foreach ($list as &$item){
            $roleName = $roles = [];
            foreach ($item->userRole as $value){
                $roleName[] =   $value['name'];
                $roles[]    =   $value['id'];
            }
            $item['sex']    =   intval($item['sex']);
            $item['roles']  =   $roles;
            $item['roleName'] = implode(',',$roleName);
            unset($item->userRole);
        }
        $this->listData($list->items(),$list->total(),$list->currentPage(),$list->perPage());
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 19:11  2022/4/4
     */
    public function getAllRole()
    {
        $list   =   Role::query()->orderBy('created_at','desc')->get(['id','name'])->toArray();
        $this->success($list);
    }

    /**
     * @CollectAccess(rule="user@create")
     * @author: Zhong Weiwei
     * @Date: 21:06  2022/4/4
     */
    public function create()
    {
        $this->onSave();
    }

    /**
     * @CollectAccess(rule="user@update")
     * @author: Zhong Weiwei
     * @Date: 21:33  2022/4/4
     */
    public function update()
    {
        $this->onSave();
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 21:06  2022/4/4
     */
    public function onSave()
    {
        $id         =   $this->request->input('id','');
        $login_name =   trim($this->request->input('login_name',''));
        $nickname   =   trim($this->request->input('nickname',''));
        $user_name  =   trim($this->request->input('user_name',''));
        $password   =   trim($this->request->input('password',''));
        $sex        =   $this->request->input('sex',3);
        $mobile     =   trim($this->request->input('mobile',''));
        $photo      =   $this->request->input('photo','');
        $status     =   $this->request->input('status',3);
        $roles      =   $this->request->input('roles',[]);

        !$login_name    &&  $this->error('????????????????????????');
        !$nickname      &&  $this->error('??????????????????');
        !$id    &&  !$password  &&  $this->error('????????????????????????');
        !is_array($roles) && !in_array($sex,[1,2,3]) &&  $this->error('???????????????');
        !$roles &&  $this->error('??????????????????');

        $rolesIds   =   Role::query()->whereIn('id',$roles)->pluck('id');

        !$rolesIds  &&  $this->error('??????????????????');
        if ($password){
            (strlen($password) < 6 || strlen($password) > 12) && $this->error('?????????6~12????????????');
        }
        if ($id){
            User::query()->where('id','<>',$id)
                ->where('login_name',$login_name)
                ->value('id') && $this->error('????????????????????????');
            $user =   User::query()->find($id);
            !$user    &&  $this->error('??????????????????');
            UserRole::query()->where('uid',$id)->delete();
            $password   &&  $user->password =   password_hash($password, PASSWORD_DEFAULT);
            $logMessage =   "????????????".$this->auth->guard()->user()->login_name."?????????????????????".$user->login_name;
        }else{
            User::query()->where('login_name',$login_name)->value('id') && $this->error('????????????????????????');
            $user   =   new User();
            $user->password =    password_hash($password, PASSWORD_DEFAULT);
            $logMessage =   "????????????".$this->auth->guard()->user()->login_name."?????????????????????".$login_name;
        }

        $mobile  && !is_mobile($mobile) &&  $this->error('???????????????????????????');

        $user->login_name   =   $login_name;
        $user->nickname     =   $nickname;
        $user->user_name    =   $user_name;
        $user->user_name    =   $user_name;
        $user->sex          =   $sex;
        $user->mobile       =   $mobile;
        $user->photo        =   $photo;
        $user->status       =   $status;
        if ($user->save()){
            $insert =   [];
            foreach ($rolesIds as $role_id){
                $insert[]   =   ['uid'=>$user->id,'role_id'=>$role_id];
            }
            UserRole::insert($insert);
            Log::info($logMessage);
            $this->success([],'????????????');
        }else{
            $this->error('???????????????');
        }
    }

    /**
     * @CollectAccess(rule="user@update")
     * @author: Zhong Weiwei
     * @Date: 18:04  2022/4/4
     */
    public function onSwitch()
    {
        $id =   $this->request->input('id','');
        !$id    &&  $this->error('????????????');
        $detail =   User::query()->find($id);
        $detail->status =   $detail->status == 1 ? 0 : 1;
        $detail->save();
        $this->success([],'????????????');
    }

    /**
     * @CollectAccess(rule="user@delete")
     * @author: Zhong Weiwei
     * @Date: 21:39  2022/4/4
     */
    public function delete()
    {
        $id =   (array)$this->request->input('id','');
        in_array($this->auth->guard()->user()->getId(),$id) &&  $this->error('?????????????????????');
        $login_name =   User::query()->whereIn('id',$id)->pluck('login_name')->toArray();
        $result =   User::query()->whereIn('id',$id)->delete();
        if ($result){
            UserRole::query()->whereIn('uid',$id)->delete();
            Log::info("????????????".$this->auth->guard()->user()->login_name."?????????????????????".implode('???',$login_name));
            $this->success([],'????????????');
        }else{
            $this->error('???????????????');
        }
    }

    /**
     * ??????
     * @author: Zhong Weiwei
     * @Date: 21:34  2022/3/16
     */
    public function login()
    {
        $login_name =   $this->request->post('login_name','');
        $password   =   $this->request->post('password','');
        !trim($login_name)  &&  $this->error('????????????????????????');
        !trim($password)    &&  $this->error('????????????????????????');
        $user   =   User::query()->where('login_name',$login_name)->where('source','system')->first();
        if ($user && password_verify($password, $user->password)){
            $result = parallel([
                'roles'             =>  function () use($user){
                    return UserRole::query()->join('role','user_role.role_id','=','role.id')
                        ->where('user_role.uid',$user->id)->value('role_id');
                },
                'checkRolesStatus'  =>  function () use($user){
                    return UserRole::query()->join('role','user_role.role_id','=','role.id')
                        ->where('user_role.uid',$user->id)->where('role.status',1)->value('role_id');;
                }
            ]);
            !$result['roles']   &&  $this->error('???????????????????????????????????????????????????');

            if ($user->status == 0 || !$result['checkRolesStatus']){
                $this->error('??????????????????????????????????????????????????????');
            }
            $token  =   $this->auth->guard()->login($user);
            $user->last_login_time  =   date('Y-m-d H:i:s');
            $user->last_login_ip    =   $this->clientRealIP();
            $user->save();
            Log::info("????????????".$login_name."??????????????????");
            $this->success($token,'????????????');
        }else{
            $this->error('???????????????????????????');
        }
    }

    /**
     * ????????????
     * @author: Zhong Weiwei
     * @Date: 21:46  2022/3/16
     */
    public function logout()
    {
        $user   =   $this->auth->guard()->user();
        Log::info("????????????".$user->login_name."??????????????????");
        $this->auth->guard()->logout();
        if (config('sso.is_sso')){
            $this->success([
                'url'  =>  config('sso.server_sso_url') . '/core/connect/endsession?' . http_build_query([
                    'post_logout_redirect_uri'  =>  $this->getDomain(),
                    'id_token_hint'             =>  Cache::get('ID_TOKEN_HINT'.$user->getId())
                ])], '???????????????');
        }else{
            $this->success([],'???????????????');
        }
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 12:52  2022/3/19
     */
    public function info()
    {
        $user   =   $this->auth->guard()->user();
        $menus  =   Menu::getMenus(['status'=>1],false, ['id','name','path','component','redirect','is_show','meta','parent_id','access','logic']);
        $menuList   =   [];
        foreach ($menus as $item){
            $item['meta']['is_show']   = $item['is_show'];
            $item['meta']['access']   = $item['access'];
            $item['meta']['logic']   = $item['logic'];
            $item['meta']['id']   = $item['id'];
            $item['meta']['parent_id']   = $item['parent_id'];
            if ($item['access']){
                $accessIds  =   explode(',', $item['access']);
                $access = AccessRule::query()->whereIn('id', $accessIds)->where('type',1)->pluck('rule')->toArray();
                $intersect  =   array_intersect($access,$user->permission);
                if (!$intersect || $item['logic'] == 'and' && count($intersect) != count($accessIds)){
                    continue;
                }
            }
            unset($item['is_show'],$item['access'],$item['logic'],$item['id'],$item['parent_id']);
            $menuList[] =   $item;
        }
        $user->menus    =   $menuList;
        $this->success($user);
    }

    /**
     * ????????????
     * @author: Zhong Weiwei
     * @Date: 22:02  2022/4/7
     */
    public function settings()
    {
        $user   =   $this->auth->guard()->user();
        $nickname   =   trim($this->request->input('nickname',''));
        $user_name  =   trim($this->request->input('user_name',''));
        $mobile     =   trim($this->request->input('mobile',''));
        $sex        =   trim($this->request->input('sex',3));
        $photo      =   trim($this->request->input('photo',''));

        !$nickname  &&  $this->error('??????????????????');
        $mobile  && !is_mobile($mobile) &&  $this->error('???????????????????????????');
        $info   =   User::query()->find($user->id);

        $info->nickname     =   $nickname;
        $info->user_name    =   $user_name;
        $info->sex          =   $sex;
        $info->mobile       =   $mobile;
        $info->photo        =   $photo;
        $info->save();
        $this->success([],'????????????');
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 22:18  2022/4/10
     */
    public function edotPassword()
    {
        $info   =   $this->auth->guard()->user();
        $old_password       =   $this->request->input('OldPassword','');
        $password           =   $this->request->input('password','');
        $ConfirmPassword    =   $this->request->input('ConfirmPassword','');

        !$old_password  &&  $this->error('????????????????????????');
        !$password  &&  $this->error('?????????????????????');
        !$ConfirmPassword  &&  $this->error('????????????????????????');
        $user   =   User::query()->find($info->getId());
        if (!password_verify($old_password, $user->password)){
            $this->error('????????????????????????');
        }

        if ($password || $ConfirmPassword){
            (strlen($password) < 6 || strlen($password) > 12) && $this->error('?????????6~12????????????');
        }

        $password !== $ConfirmPassword  &&  $this->error('??????????????????????????????');
        $user->password =   password_hash($password, PASSWORD_DEFAULT);
        $user->save();
        $this->success([],'????????????');
    }

}
