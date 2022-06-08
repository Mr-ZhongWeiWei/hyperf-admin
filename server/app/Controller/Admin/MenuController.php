<?php

namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Controller\AbstractController;
use App\Model\AccessRule;
use App\Model\Menu;

/**
 * Class MenuController
 * @package App\Controller\Admin
 */
class MenuController extends AbstractController
{
    /**
     * @CollectAccess(rule="menu@index")
     * @author: Zhong Weiwei
     * @Date: 21:37  2022/3/19
     */
    public function index()
    {
        $menus  =   Menu::getMenus();
        foreach ($menus as &$item){
            $item['access'] =   $item['access'] ? explode(',',$item['access']) : [];
            foreach ($item['access'] as &$accessId){
                $accessId   =   intval($accessId);
            }
        }
        $this->success(listToTree($menus));
    }

    /**
     * @CollectAccess(rule="menu@create")
     * @author: Zhong Weiwei
     * @Date: 21:36  2022/3/19
     */
    public function create()
    {
        $this->onSave();
    }

    /**
     * @CollectAccess(rule="menu@update")
     * @author: Zhong Weiwei
     * @Date: 21:37  2022/3/19
     */
    public function update()
    {
        $this->onSave();
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 21:37  2022/3/19
     */
    protected function onSave()
    {
        $id     =   $this->request->input('id','');
        $data   =   $this->request->post();
        isset($data['title']) && !$data['title'] && $this->error('请输入菜单名称！');
        isset($data['name']) && !$data['name'] && $this->error('请输入路由名称！');
        isset($data['path']) && !$data['path'] && $this->error('请输入路由规则！');
        isset($data['component']) && !$data['component'] && $this->error('组件路径不能为空！');
        if ($id){
            $menu   =   Menu::query()->find($id);
            !$menu  &&  $this->error('记录不存在！');
        }else{
            $menu   =   new Menu();
            $sort   =   (int)Menu::query()->where('parent_id',$data['parent_id'])->max('sort');
            $menu->sort =   $sort + 1;
        }

        if (isset($data['access'])){
            $access = AccessRule::query()->whereIn('id',$data['access'])->where('type',1)->pluck('id')->toArray();
        }

        $menu->name         =   $data['name'];
        $menu->path         =   $data['path'];
        $menu->component    =   $data['component'];
        $menu->redirect     =   $data['redirect'];
        $menu->status       =   $data['status'];
        $menu->is_show      =   $data['is_show'];
        $menu->parent_id    =   $data['parent_id'];
        $menu->meta         =   json_encode(['title'=>$data['title'],'icon'=>$data['icon']]);
        $menu->access       =   isset($data['access']) ? implode(',',$access) : '';
        $menu->save();
        $this->success([],'操作成功');
    }

    /**
     * @CollectAccess(rule="menu@delete")
     * @author: Zhong Weiwei
     * @Date: 23:05  2022/3/19
     */
    public function delete()
    {
        $id =   $this->request->input('id','');
        if (Menu::query()->whereIn('parent_id',$id)->get()->toArray()){
            $this->error('当前菜单存在子级,请先删除子菜单！');
        }
        Menu::destroy($id);
        $this->success([],'删除成功！');
    }

    /**
     * @CollectAccess(rule="menu@update")
     * @author: Zhong Weiwei
     * @Date: 10:42  2022/3/20
     */
    public function sort()
    {
        $dragKey    =   $this->request->input('dragKey');
        $dropKey    =   $this->request->input('dropKey');
        $dropPosition    =   $this->request->input('dropPosition');
        if (!$dragKey){
            $this->error('移动菜单ID不能为空！');
        }

        if (!$dropKey){
            $this->error('移动目标菜单ID不能为空！');
        }

        if (!in_array($dropPosition,[0,1,-1])){
            $this->error('参数异常！');
        }
        $menu   =   Menu::query()->find($dragKey);
        if (!$menu){
            $this->error('当前移动目录不存在！');
        }

        $dropInfo   =   Menu::query()->where('id',$dropKey)->first();
        if (!$dropInfo){
            $this->error('移动目标目录不存在！');
        }

        $dropParentInfo   =  Menu::query()->where('id',$dropInfo['parent_id'])->first();

        switch ($dropPosition){
            case 0:
                $sort   =   Menu::query()->where('parent_id',$dropKey)->max('sort');
                $menu->id           =   $dragKey;
                $menu->parent_id    =   $dropKey;
                $menu->sort         =   $sort;
                break;
            case 1:
            case -1:
                $sort   =   Menu::query()->where('id',$dropKey)->value('sort');
                Menu::query()->where('parent_id',$menu->parent_id)
                ->where('sort',$dropPosition == 1 ? '>' : '>=',$sort)
                ->where('id','<>',$dragKey)
                ->increment('sort');
                $menu->id           =   $dragKey;
                $menu->parent_id    =   $dropParentInfo ? $dropInfo['parent_id'] : 0;
                $menu->sort         =   $dropPosition == 1 ? $sort+1 : $sort;
                break;
        }
        $menu->save();
        $this->success([],'排序成功');
    }
}
