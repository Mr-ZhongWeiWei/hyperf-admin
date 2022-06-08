/**
 * config
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/9
 */

import request from '@/utils/request'

export function list(params: any) {
    return request({
        url: '/configs',
        method: 'get',
        params
    })
}

export function save(data: any) {
    return request({
        url: '/configs/create',
        method: 'post',
        data
    })
}

export function del(data: any) {
    return request({
        url: '/configs/delete',
        method: 'post',
        data
    })
}

export function release(data: any) {
    return request({
        url: '/configs/save',
        method: 'post',
        data
    })
}