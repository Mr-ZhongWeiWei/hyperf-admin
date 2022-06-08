/**
 * menu
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/3/19
 */

import request from '@/utils/request'

export function list() {
    return request({
        url: '/menu',
        method: 'get',
    })
}

export function save(data: any) {
    return request({
        url: data.id ? '/menu/update' : '/menu/create',
        method: 'post',
        data: data
    })
}

export function del(id: number[] | string[]) {
    return request({
        url: '/menu/delete',
        method: 'post',
        data: {id}
    })
}


export function sort(data : {dropKey: string | number, dragKey: string | number, dropPosition: string | number}) {
    return request({
        url: '/menu/sort',
        method: 'post',
        data: data
    })
}

export function accessList() {
    return request({
        url: '/access',
        method: 'get',
    })
}

export function accessSave(data: any) {
    return request({
        url: data.id ? '/access/update' : '/access/create',
        method: 'post',
        data: data
    })
}

export function delAccess(id: number | string) {
    return request({
        url: '/access/delete',
        method: 'post',
        data: {id}
    })
}