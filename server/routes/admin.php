<?php
/**
 * Created by PhpStorm.
 * User: Zhong Weiwei
 * Date: 2022/3/13
 * Time: 15:30
 */
use Hyperf\HttpServer\Router\Router;
Router::post('/user/login','App\Controller\Admin\UserController@login');
Router::get('/getInitConfig','App\Controller\Admin\CommonController@getInitConfig');
Router::post('/accessLogin','App\Controller\Admin\CommonController@accessLogin');
Router::addGroup('/',function (){
    //用户管理
    Router::get('user','App\Controller\Admin\UserController@index');
    Router::post('user/logout','App\Controller\Admin\UserController@logout');
    Router::get('user/info','App\Controller\Admin\UserController@info');
    Router::post('user/onSwitch','App\Controller\Admin\UserController@onSwitch');
    Router::get('user/getAllRole','App\Controller\Admin\UserController@getAllRole');
    Router::post('user/create','App\Controller\Admin\UserController@create');
    Router::post('user/update','App\Controller\Admin\UserController@update');
    Router::post('user/delete','App\Controller\Admin\UserController@delete');
    Router::post('user/settings','App\Controller\Admin\UserController@settings');
    Router::post('user/edotPassword','App\Controller\Admin\UserController@edotPassword');

    //菜单管理
    Router::get('menu','App\Controller\Admin\MenuController@index');
    Router::post('menu/create','App\Controller\Admin\MenuController@create');
    Router::post('menu/update','App\Controller\Admin\MenuController@update');
    Router::post('menu/delete','App\Controller\Admin\MenuController@delete');
    Router::post('menu/sort','App\Controller\Admin\MenuController@sort');

    //权限管理
    Router::get('access','App\Controller\Admin\AccessRuleController@index');
    Router::post('access/create','App\Controller\Admin\AccessRuleController@create');
    Router::post('access/update','App\Controller\Admin\AccessRuleController@update');
    Router::post('access/delete','App\Controller\Admin\AccessRuleController@delete');

    //角色管理
    Router::get('role','App\Controller\Admin\RoleController@index');
    Router::post('role/create','App\Controller\Admin\RoleController@create');
    Router::post('role/update','App\Controller\Admin\RoleController@update');
    Router::post('role/delete','App\Controller\Admin\RoleController@delete');
    Router::post('role/onSwitch','App\Controller\Admin\RoleController@onSwitch');
    Router::post('role/setAccess','App\Controller\Admin\RoleController@setAccess');

    //系统日志
    Router::get('logs','App\Controller\Admin\LogsController@index');
    Router::post('logs/clear','App\Controller\Admin\LogsController@clear');

    //参数设置
    Router::get('configs','App\Controller\Admin\ConfigController@index');
    Router::post('configs/create','App\Controller\Admin\ConfigController@create');
    Router::post('configs/delete','App\Controller\Admin\ConfigController@delete');
    Router::post('configs/save','App\Controller\Admin\ConfigController@save');

    Router::post('upload','App\Controller\Admin\UploadController@uploadFile');
},['middleware'=>[
    Qbhy\HyperfAuth\AuthMiddleware::class,
    App\Middleware\PermissionMiddleware::class
]]);

