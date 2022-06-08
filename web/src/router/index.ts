/**
 * index
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/1/10
 */
import {createRouter, createWebHistory} from 'vue-router'
import {constantRouterMap} from '@/config/router.config'


const router = createRouter({
    history: createWebHistory(),
    routes: constantRouterMap
})


export default router
