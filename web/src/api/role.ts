/**
 * role
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/3
 */
import request from '@/utils/request'

export function list(params: any) {
    return request({
        url: '/role',
        method: 'get',
        params
    })
}

export function onSwitch(data: any) {
    return request({
        url: '/role/onSwitch',
        method: 'post',
        data
    })
}

export function Delete(data: any) {
    return request({
        url: '/role/delete',
        method: 'post',
        data
    })
}

export function save(data: {id?:number, name?: string, remark?: string, status?: number}) {
    return request({
        url: data.id ? '/role/update' : '/role/create',
        method: 'post',
        data
    })
}

export function setAccess(data: {id:number, access: string}) {
    return request({
        url: '/role/setAccess',
        method: 'post',
        data
    })
}