<?php

namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Common\Utils\Cache;
use App\Controller\AbstractController;
use Hyperf\DbConnection\Db;

/**
 * Class ConfigController
 * @package App\Controller\Admin
 */
class ConfigController extends AbstractController
{
    /**
     * @CollectAccess(rule="config@index")
     * @author: Zhong Weiwei
     * @Date: 15:23  2022/4/9
     */
    public function index()
    {
        $all        =   (bool)$this->request->input('all',false);
        if ($all == 1 || env('APP_ENV') == 'dev'){
            //此处通过url输入all=1参数返回全部分组
            $group  =   [
                ['name'=>'基础设置','key'=>1],
                ['name'=>'微信设置','key'=>2],
            ];
        }else{
            //此处返回允许管理员修改的参数分组
            $group  =   [
                ['name'=>'微信设置','key'=>2],
            ];
        }
        $group_id   =   $this->request->input('group_id',$group[0]['key']);
        $configs    =   Db::table('config')->where('group_id',$group_id)->orderByRaw('sort asc,id asc')->get()->toArray();
        foreach ($configs as &$item){
            switch ($item->type){
                case 'checkbox':
                    $item->value = $item->value ? json_decode($item->value, true) : [];
                    break;
                case 'singleimg':
                case 'multipleimg':
                    $item->value = $item->value ? json_decode($item->value,true) : [];
                    break;
            }
            if ($item->data && in_array($item->type,['checkbox','radio','select'])){
                $item->data = json_decode($item->data,true);
                $dataTextArea = [];
                foreach ($item->data as $value){
                    $dataTextArea[] =   "{$value['value']}={$value['label']}";
                }
                $item->dataTextArea =   implode("\n",$dataTextArea);
            }

            if ($item->type == 'multipleimg'){
                $item->data =   $item->data ? intval($item->data) : 1;
            }
        }
        $this->success([
            'group'     =>  $group,
            'configs'   =>  $configs,
            'is_dev'    =>  env('APP_ENV') == 'dev' ? true : false
        ]);
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 17:02  2022/4/9
     */
    public function create()
    {
        $id   = $this->request->input('id','');
        $data = array_map("trim", $this->request->inputs(['name', 'label', 'type', 'group_id','data','extra'],''));
        (!isset($data['name']) || !$data['name'])   &&  $this->error('请填写参数名！');
        (!isset($data['label']) || !$data['label'])   &&  $this->error('请填写参数中文名！');
        (!isset($data['type']) || !$data['type'])   &&  $this->error('请选择显示类型！');
        (!isset($data['group_id']) || !$data['group_id'])   &&  $this->error('请选择分组！');
        if (in_array($data['type'], ["select","checkbox","radio"])){
            !$data['data']  &&  $this->error("请填写可选参数!");
            $options = explode("\n", $data['data']);
            $options = array_map("trim", $options);
            !$options && $this->error("请填写可选参数!");

            $option_data = [];
            foreach ($options as $val){
                $_data = explode("=", $val);
                count($_data) == 2 && $option_data[] = ['value'=> $_data[0], 'label' => $_data[1]];
            }
            !$option_data && $this->error("请填写可选参数!");
            $data['data']   =   json_encode($option_data,JSON_UNESCAPED_UNICODE);
        }

        if (!$id){
            Db::table('config')->where('name',$data['name'])->value('id') && $this->error('参数名已存在！');
            $result =   Db::table('config')->insert($data);
        }else{
            Db::table('config')->where('id','<>',$id)->where('name',$data['name'])->value('id') && $this->error('参数名已存在！');
            $result =   Db::table('config')->where('id',$id)->update($data);
        }

        if ($result !== false){
            Cache::getInstance()->delete('DB_EXTEND_CONFIGS');
            $this->success([],'操作成功');
        }else{
            $this->success([],'操作失败！');
        }
    }

    /**
     * @author: Zhong Weiwei
     * @Date: 22:51  2022/4/9
     */
    public function delete()
    {
        $id =   $this->request->input('id','');
        Db::table('config')->where('id',$id)->delete();
        Cache::getInstance()->delete('DB_EXTEND_CONFIGS');
        $this->success([],'操作成功');
    }

    /**
     * @CollectAccess(rule="config@save")
     * @throws \Psr\SimpleCache\InvalidArgumentException
     * @author: Zhong Weiwei
     * @Date: 13:30  2022/4/10
     */
    public function save()
    {
        $configs    =   $this->request->input('configs',[]);
        foreach ($configs as $name=>$value){
            is_array($value) && $value = json_encode($value,JSON_UNESCAPED_UNICODE);
            Db::table('config')->where('name',$name)->update(['value'=>$value]);
        }
        Cache::getInstance()->delete('DB_EXTEND_CONFIGS');
        $this->success([],'保存成功');
    }
}
