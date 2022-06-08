
# 环境要求

 - PHP >= 7.4
 - Swoole PHP 扩展 >= 4.5，并关闭了 Short Name
 - OpenSSL PHP 扩展（如需要使用到 HTTPS）
 - Pcntl PHP 扩展
 - JSON PHP 扩展
 - PDO PHP 扩展
 - Redis PHP 扩展
 

# rbac权限

使用注解收集需要验证权限的操作，使用方式如下：@CollectAccess(rule="access@index")
```
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
```
# 权限验证中间件 PermissionMiddleware
```
public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $permission =   [];
        foreach (AnnotationCollector::getMethodsByAnnotation(CollectAccess::class) as $item){
            $callback   =   $item['class'].'@'.$item['method'];
            if ($callback   ==  $request->getAttribute(Dispatched::class)->handler->callback){
                $permission =   [
                    'rule'  =>  $item['annotation']->rule,
                    'logic' =>  $item['annotation']->logic
                ];
            }
        }

        if ($permission){
            if (is_string($permission['rule'])){
                $permission['rule']   =   explode(',',$permission['rule']);
            }

            $intersect  =   array_intersect($permission['rule'],$this->auth->guard()->user()->permission);
            if (!$intersect || $permission['logic'] == 'and' && count($intersect) != count($permission['rule'])){
                return $this->response->withStatus(403)->withBody(new SwooleStream($this->error('您没有权限！', $this->NoAccessCode, false)));
            }
        }
        return $handler->handle($request);
    }
```

# 前端使用 auth 权限验证指令
```
import {App, toRaw} from "vue";
import {isString} from "ant-design-vue/es/_util/util";
import store from '@/store'
export const setupDirective = (app: App<Element>)=>{
    /**
     * auth 权限指令
     * 指令用法：
     *  - 在需要控制 auth 级别权限的组件上使用 v-auth:and , 如下：
     *    字符串用法：<i-button v-auth="'user@add,user@update'" >添加用户</a-button>
     *    数组用法：<i-button v-auth="['user@add','user@update']" >添加用户</a-button>
     *    <a-button v-auth:and="['user@add','user@update']">同时满足多个权限</a-button>
     *    <a-button v-auth:and="'user@add,user@update'">同时满足多个权限</a-button>
     *
     *  - 当前用户没有权限时，组件上使用了该指令则会被隐藏
     */
    app.directive('auth', {
        mounted(el, binding) {
            const data  =   {...toRaw(binding)}
            let access:string[]  =   []
            if (isString(data.value)){
                access  =   data.value.split(',')
            }
            if (Array.isArray(data.value)){
                access  =   data.value
            }
            const intersect = Array.from(new Set([...store.getters.userInfo.permission].filter(v => new Set(access).has(v))))
            if (intersect.length == 0 || data.arg == 'and' && intersect.length != access.length){
                el.style.display = 'none'
            }
        }
    })
}
```
# 前端Table组件封装参数说明
封装参数说明，其他参数参考Ant Design Vue 表格组件使用

|参数名|类型|说明|默认|
|:----  |:----- |:-----   |:-----   |
|showToolBar | Boolean |是否显示头部工具   | true|
|data | Function |异步请求表格数据   | |
|layout | strung |头部工具布局方式layoutUpAndDown、layoutLeftAndRight、layoutLeftAndRight2   |layoutLeftAndRight |
|alert | Object或Boolean |显示选中条数以及清空选中操作   | null |

