import request from '@/utils/request'

export function login(parameter: object) {
    return request({
        url: '/user/login',
        method: 'post',
        data: parameter
    })
}


export function getInfo() {
    return request({
        url: '/user/info',
        method: 'get',
        headers: {
            'Content-Type': 'application/json;charset=UTF-8'
        }
    })
}

export function logout() {
    return request({
        url: '/user/logout',
        method: 'post',
        headers: {
            'Content-Type': 'application/json;charset=UTF-8'
        }
    })
}

