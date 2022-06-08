import store from "@/store";

const storage = require('store')
import {login, getInfo, logout} from '@/api/login'
import {ACCESS_TOKEN} from '@/store/mutation-types'

const user = {
    state: {
        token: '',
        name: '',
        avatar: '',
        roles: [],
        info: {},
        permissions: [],
    },

    mutations: {
        SET_TOKEN: (state: { token: any }, token: any) => {
            state.token = token
        },
        SET_NAME: (state: { name: any }, {name}: any) => {
            state.name = name
        },
        SET_AVATAR: (state: { avatar: any }, avatar: any) => {
            state.avatar = avatar
        },
        SET_ROLES: (state: { roles: any }, roles: any) => {
            state.roles = roles
        },
        SET_INFO: (state: { info: any }, info: any) => {
            state.info = info
        },
        SET_PERMISSIONS: (state: any, permissions: any) => {
            state.permissions = permissions
        },
    },

    actions: {
        // 登录
        Login({commit}: any, userInfo: any) {
            return new Promise<void>((resolve, reject) => {
                login(userInfo).then((response: any) => {
                    const token = response.data
                    if (token){
                        storage.set(ACCESS_TOKEN, token)
                        commit('SET_TOKEN', token)
                        resolve(response)
                    }
                }).catch((error: any) => {
                    reject(error)
                })
            })
        },

        // 获取用户信息
        GetInfo({commit}: any) {
            return new Promise((resolve, reject) => {
                getInfo().then((response: any) => {
                    const { user_role } = response.data
                    if (user_role.length > 0) {
                        commit('SET_ROLES', user_role)
                        commit('SET_INFO', response.data)
                    } else {
                        reject(new Error('getInfo: roles must be a non-null array !'))
                    }
                    commit('SET_NAME', {name: response.data.user_name})
                    commit('SET_AVATAR', response.data.photo)
                    commit('SET_PERMISSIONS', response.data.permission)
                    resolve(response)
                }).catch((error: any) => {
                    reject(error)
                })
            })
        },

        // 登出
        Logout({commit}: any) {
            return new Promise<void>((resolve) => {
                logout().then(({data}) => {
                    commit('SET_TOKEN', '')
                    commit('SET_ROLES', [])
                    storage.remove(ACCESS_TOKEN)
                    if (store.getters.initConfig.is_sso == 1){
                        window.location.href    =   data.url
                    }
                    resolve()
                }).catch(() => {
                    resolve()
                }).finally(() => {
                })
            })
        }

    }
}

export default user
