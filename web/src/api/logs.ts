/**
 * logs
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/5
 */
import request from '@/utils/request'

export function list(params: any) {
    return request({
        url: '/logs',
        method: 'get',
        params
    })
}

export function clear(data: any) {
    return request({
        url: '/logs/clear',
        method: 'post',
        data
    })
}