import router from './router'
import store from './store'
const storage = require('store')
import {ACCESS_TOKEN} from '@/store/mutation-types'
const NProgress = require('nprogress')
import '@/components/NProgress/nprogress.less'
NProgress.configure({showSpinner: false})
import {  RouteLocationNormalized, NavigationGuardNext, RouteRecordRaw} from "vue-router"
import request from "@/utils/request";

const allowList = ['login', 'register', 'registerResult']
const loginRoutePath = '/user/login'
const defaultRoutePath = '/dashboard/workplace'

router.beforeEach((to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) => {
    NProgress.start()
    if (to.meta.title) {
        (document as any).title = to.meta.title + ' - ' + store.getters.initConfig.siteTitle;
    }
    if(to.path =='/accesslogin'){
        request({url: '/accessLogin', method: 'post', data: {code:to.query.code}}).then((res)=>{
            const token   =   res.data
            storage.set(ACCESS_TOKEN, token)
            store.commit('SET_TOKEN',token)
            next({path:'/'});
            NProgress.done()
        })
    }else {
        if (storage.get(ACCESS_TOKEN)) {
            if (to.path === loginRoutePath) {
                next({path: defaultRoutePath})
                NProgress.done()
            } else {
                if (store.getters.roles.length === 0) {
                    store.dispatch('GetInfo').then(({data:{menus}}) => {
                        store.dispatch('GenerateRoutes', {menus}).then(() => {
                            store.getters.addRouters.forEach((item: RouteRecordRaw)=>{
                                router.addRoute(item)
                            })
                            const redirect = decodeURIComponent(<string>from.query.redirect || to.path)
                            if (to.path === redirect) {
                                next({...to, replace: true})
                            } else {
                                // 跳转到目的路由
                                next({path: redirect})
                            }
                        })
                    })
                } else {
                    next()
                }
            }
        } else {
            if (allowList.includes(<string>to.name)) {
                // 在免登录名单，直接进入
                next()
            } else {
                request({url: '/getInitConfig', method: 'get'}).then(({data})=>{
                    if (data.is_sso == 1){
                        window.location.href = data.url
                    }else {
                        next({path: loginRoutePath, query: {redirect: to.fullPath}})
                        NProgress.done()
                    }
                })
            }
        }
    }
})

router.afterEach(() => {
    NProgress.done()
})

