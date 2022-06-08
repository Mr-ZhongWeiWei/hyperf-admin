import axios, {AxiosResponse} from 'axios';
import {ACCESS_TOKEN, REQUEST_SUCCESS_CODE, TOKEN_EXPIRED_CODE, TOKEN_INVALID_CODE} from '../store/mutation-types'
import router from '@/router'
import {message} from "ant-design-vue";
const storage = require('store')
import store from '@/store'
// 创建 axios 实例
const request = axios.create({
    // API 请求的默认前缀
    baseURL: process.env.VUE_APP_API_BASE_URL,
    timeout: 6000 // 请求超时时间
})

// 异常拦截处理器
const errorHandler = (error: any) => {
    if (error.response) {
        const data = error.response.data
        const token = storage.get(ACCESS_TOKEN)
        if (error.response.status === 403) {
            message.error(data.message)
            router.push({ path: '/exception/403' })
        }
        if (error.response.status === 401) {
            if (token) {
                storage.remove(ACCESS_TOKEN)
                window.location.reload()
            }
        }
    }
    return Promise.reject(error)
}

request.interceptors.request.use((config: any) => {
    const token = storage.get(ACCESS_TOKEN)
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token
    }
    return config
}, errorHandler)

request.interceptors.response.use((response:AxiosResponse) => {
    if (Object.keys(response.data).length == 0){
        return response
    }

    if (response.data.code !== REQUEST_SUCCESS_CODE) {
        message.error(response.data.message)
        if (response.data.code == TOKEN_EXPIRED_CODE || response.data.code == TOKEN_INVALID_CODE) {
            store.dispatch('Logout').then(() => window.location.reload())
        }
        return Promise.reject(new Error(response.data.message))
    } else {
        return Promise.resolve(response.data)
    }
}, errorHandler)


export default request

