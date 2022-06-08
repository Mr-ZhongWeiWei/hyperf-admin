<?php

namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Common\Utils\Log;
use App\Controller\AbstractController;
use App\Model\AccessRule;

/**
 * Class AccessRule
 * @package App\Controller\Admin
 */
class AccessRuleController extends AbstractController
{
    /**
     * @CollectAccess(rule="access@index")
     * @author: Zhong Weiwei
     * @Date: 21:16  2022/3/22
     */
    public function index()
    {
        $list   =   AccessRule::orderBy('sort')
            ->orderBy('created_at')
            ->get(['*','id as key','name as title'])->toArray();
        $this->success(listToTree($list));
    }

    /**
     * @CollectAccess(rule="access@create")
     * @author: Zhong Weiwei
     * @Date: 12:04  2022/3/23
     */
    public function create()
    {
        $this->onSave();
    }

    /**
     * @CollectAccess(rule="access@update")
     * @author: Zhong Weiwei
     * @Date: 16:11  2022/3/23
     */
    public function update()
    {
        $this->onSave();
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 12:04  2022/3/23
     */
    protected function onSave()
    {
        $id         =   $this->request->input('id','');
        $name       =   $this->request->input('name','');
        $type       =   $this->request->input('type',1);
        $parent_id  =   $this->request->input('parent_id',0);
        $rule       =   $this->request->input('rule','');

        !$name  &&  $this->error('名称不能为空！');
        if ($type == 1){
            !$rule  &&  $this->error('权限值不能为空');
            !$parent_id  &&  $this->error('请选择权限分组');
        }

        if ($id){
            $type == 1 && AccessRule::query()->where('id','<>',$id)->where('rule',$rule)->value('id') &&  $this->error('权限值已存在！');
            $access =   AccessRule::query()->find($id);
        }else{
            $type == 1 && AccessRule::query()->where('rule',$rule)->value('id') &&  $this->error('权限值已存在！');
            $access =   new AccessRule();
        }
        $access->name       =   $name;
        $access->type       =   $type;
        $access->parent_id  =   $parent_id;
        $access->rule       =   $rule;
        $access->sort       =   99;

        $access->save();
        Log::info("管理员【".$this->auth->guard()->user()->login_name."】".($id ? '编辑' : '添加')."了".($type == 1 ? '权限' : '权限组')."(".$name.")");
        $this->success([],'操作成功');
    }

    /**
     * @CollectAccess(rule="access@delete")
     * @author: Zhong Weiwei
     * @Date: 16:15  2022/3/23
     */
    public function delete()
    {
        $id =   $this->request->input('id','');
        $info   =   AccessRule::query()->find($id);
        !$info  &&  $this->error('数据不存在！');
        $res = AccessRule::query()->where('id',$id)->delete();
        if ($res){
            AccessRule::query()->where('parent_id',$id)->delete();
            Log::info("管理员【".$this->auth->guard()->user()->login_name."】删除了".($info->type == 1 ? '权限' : '权限组')."(".$info->name.")");
            $this->success([],'删除成功');
        }else{
            $this->error('删除失败！');
        }
    }
}
