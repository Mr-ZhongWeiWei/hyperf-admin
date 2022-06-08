/**
 * user
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/4
 */
import request from '@/utils/request'

export function list(params: any) {
    return request({
        url: '/user',
        method: 'get',
        params
    })
}

export function getAllRole() {
    return request({
        url: '/user/getAllRole',
        method: 'get',
    })
}

export function onSwitch(data: any) {
    return request({
        url: '/user/onSwitch',
        method: 'post',
        data
    })
}

export function save(data: any) {
    return request({
        url: data.id ? '/user/update' : '/user/create',
        method: 'post',
        data
    })
}

export function Delete(data: any) {
    return request({
        url: '/user/delete',
        method: 'post',
        data
    })
}

export function settings(data: any) {
    return request({
        url: '/user/settings',
        method: 'post',
        data
    })
}

export function edotPassword(data: any) {
    return request({
        url: '/user/edotPassword',
        method: 'post',
        data
    })
}