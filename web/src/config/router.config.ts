/**
 * router.config
 *
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2021/9/26
 */

import { UserLayout, BasicLayout } from "@/layouts/index";
import { RouteRecordRaw } from "vue-router"

export const asyncRouterMap:Array<RouteRecordRaw> = [
    {
        path: '/:pathMatch(.*)*',
        redirect: '/404',
    }
]

export const constantRouterMap: Array<RouteRecordRaw> = [
    {
        path: '/user',
        component: UserLayout,
        redirect: '/user/login',
        children: [
            {
                path: 'login',
                name: 'login',
                component: () => import('@/views/user/Login.vue')
            }
        ]
    },
    {
        path: '/404',
        component: () => import('@/views/exception/404.vue')
    },
    {
        component: BasicLayout,
        path: '/exception',
        redirect: '/exception/403',
        children: [
            {
                path: '403',
                name: '403',
                component: () => import('@/views/exception/403.vue')
            }
        ]
    }
]
