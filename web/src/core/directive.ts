
/**
 * directive
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/4
 */
import {App, toRaw} from "vue";
import {isString} from "ant-design-vue/es/_util/util";
import store from '@/store'
export const setupDirective = (app: App<Element>)=>{
    /**
     * auth 权限指令
     * 指令用法：
     *  - 在需要控制 auth 级别权限的组件上使用 v-auth:and , 如下：
     *    字符串用法：<a-button v-auth="'user@add,user@update'" >添加用户</a-button>
     *    数组用法：<a-button v-auth="['user@add','user@update']" >添加用户</a-button>
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