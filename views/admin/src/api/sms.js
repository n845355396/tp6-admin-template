import request from '@/utils/request'

export function smsList(params) {
    return request({
        url: '/sms/list',
        method: 'get',
        params: params
    })
}

export function smsRetry(data) {
    return request({
        url: '/sms/retry',
        method: 'post',
        data: data
    })
}