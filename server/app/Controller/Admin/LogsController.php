<?php

namespace App\Controller\Admin;

use App\Common\Annotation\CollectAccess;
use App\Common\Utils\Log;
use App\Controller\AbstractController;
use Hyperf\DbConnection\Db;
use Monolog\Logger;

/**
 * Class LogsController
 * @package App\Controller\Admin
 */
class LogsController extends AbstractController
{
    /**
     * @CollectAccess(rule="logs@index")
     * @author: Zhong Weiwei
     * @Date: 19:23  2022/4/5
     */
    public function index()
    {
        $limit  =   (int)$this->request->input('limit',10);
        $keyword=   $this->request->input('keyword','');
        $time   =   $this->request->input('time','');
        $level  =   $this->request->input('level','');
        $where  =   [];
        $keyword != ''    &&  $where[]    =   ['message|context','like',"%$keyword%"];
        $level  &&  $where[]    =   ['level','=',$level];
        if ($time){
            [$stime,$etime] = explode(' - ', $time);
            $where[]    =   [function ($query)use($stime,$etime) {
                $query->whereBetween('writetime',[$stime,$etime.' 23:59:59']);
            }];
        }
        $list   =   Db::table('logs')->where($where)->orderBy('writetime','desc')->paginate($limit);
        $this->listData(['list'=>$list->items(),'levels'=>array_flip(Logger::getLevels())],$list->total(),$list->currentPage(),$list->perPage());
    }

    /**
     * @CollectAccess(rule="logs@clear")
     * @author: Zhong Weiwei
     * @Date: 20:16  2022/4/5
     */
    public function clear()
    {
        $type   =   $this->request->input('type',1);
        if ($type == 1){
            Db::table('logs')->where('writetime','<=', date('Y-m-d',strtotime('-7 day')))->delete();
            Log::info("管理员【".$this->auth->guard()->user()->login_name."】清空了近七天的日志");
        }else{
            Db::table('logs')->truncate();
            Log::info("管理员【".$this->auth->guard()->user()->login_name."】清空了日志");
        }
        $this->success([],'操作成功');
    }
}
