<?php

namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Common\Utils\Log;
use App\Controller\AbstractController;
use App\Model\AccessRule;
use App\Model\Role;
use App\Model\UserRole;

/**
 * Class RoleController
 * @package App\Controller\Admin
 */
class RoleController extends AbstractController
{
    /**
     * @CollectAccess(rule="role@index")
     * @author: Zhong Weiwei
     * @Date: 20:50  2022/3/29
     */
    public function index()
    {
        $limit  =   (int)$this->request->input('limit',10);
        $keyword=   $this->request->input('keyword','');
        $time   =   $this->request->input('time','');
        $status =   $this->request->input('status','');
        $sortField = $this->request->input('sortField','created_at');
        $sortOrder = $this->request->input('sortOrder','descend');
        $where  =   [];
        $keyword != ''    &&  $where[]    =   ['name','like',"%$keyword%"];
        is_numeric($status) && $where[]   =   ['status','=',$status];
        if ($time){
            [$stime,$etime] = explode(' - ', $time);
            $where[]    =   [function ($query)use($stime,$etime) {
                $query->whereBetween('created_at',[$stime,$etime.' 23:59:59']);
            }];
        }
        $list   =   Role::query()->where($where)->orderBy($sortField,$sortOrder == 'descend' ? 'desc' : 'asc')->paginate($limit);
        $this->listData($list->items(),$list->total(),$list->currentPage(),$list->perPage());
    }

    /**
     * @CollectAccess(rule="role@create")
     * @author: Zhong Weiwei
     * @Date: 21:02  2022/3/29
     */
    public function create()
    {
        $this->onSave();
    }

    /**
     * @CollectAccess(rule="role@update")
     * @author: Zhong Weiwei
     * @Date: 21:02  2022/3/29
     */
    public function update()
    {
        $this->onSave();
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 21:01  2022/3/29
     */
    protected function onSave()
    {
        $id         =   $this->request->input('id','');
        $name       =   $this->request->input('name','');
        $status     =   $this->request->input('status',1);
        $remark     =   $this->request->input('remark','');
        $access     =   $this->request->input('access','');
        !trim($name)    &&  $this->error('请填写角色名称！');

        if ($id){
            $role   =   Role::query()->find($id);
            !$role  &&  $this->error('记录不存在！');
            $logMessage =   "管理员【".$this->auth->guard()->user()->login_name."】修改了角色：".$role->name."=>".$name."";
        }else{
            $role   =   new Role();
            $logMessage =   "管理员【".$this->auth->guard()->user()->login_name."】添加了角色：".$name."";
        }
        $role->name     =   $name;
        $role->status   =   $status;
        $role->remark   =   $remark;
        $role->access   =   $access;
        $role->save();
        Log::info($logMessage);
        $this->success([],'操作成功');
    }

    /**
     * @CollectAccess(rule="role@update")
     * @author: Zhong Weiwei
     * @Date: 17:52  2022/4/3
     */
    public function onSwitch()
    {
        $id =   $this->request->input('id','');
        !$id    &&  $this->error('参数错误');
        $detail =   Role::query()->find($id);
        $detail->status =   $detail->status == 1 ? 0 : 1;
        $detail->save();
        $this->success([],'操作成功');
    }

    /**
     * @CollectAccess(rule="role@delete")
     * @author: Zhong Weiwei
     * @Date: 21:09  2022/3/29
     */
    public function delete()
    {
        $id =   $this->request->input('id','');
        UserRole::query()->where('role_id',$id)->count()    &&  $this->error('有用户绑定该角色,请先解绑！');
        $name   =   Role::query()->where('id',$id)->value('name');
        !$name  &&  $this->error('角色不存在！');
        Role::destroy($id);
        Log::info("管理员【".$this->auth->guard()->user()->login_name."】删除了：".$name.")");

        $this->success([],'删除成功');
    }

    /**
     * @CollectAccess(rule="role@setAccess")
     * @author: Zhong Weiwei
     * @Date: 12:45  2022/4/4
     */
    public function setAccess()
    {
        $id     =   $this->request->input('id','');
        $access =   $this->request->input('access','');
        $detail =   Role::query()->find($id);
        !$detail    &&  $this->error('角色不存在！');
        !$access    &&  $this->error('请选择权限！');
        $ids    =   AccessRule::query()->whereIn('id',explode(',',$access))->pluck('id')->toArray();
        !$ids   &&  $this->error('权限不存在！');
        $detail->access =   implode(',',$ids);
        $name   =   $detail->name;
        $detail->save();
        Log::info("管理员【".$this->auth->guard()->user()->login_name."】修改了:({$name})权限");
        $this->success([],'操作成功');
    }
}
