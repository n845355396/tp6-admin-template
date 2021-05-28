import request from '@/utils/request'

export function taskList(params) {
    return request({
        url: '/task/list',
        method: 'get',
        params: params
    })
}