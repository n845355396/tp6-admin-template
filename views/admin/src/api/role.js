import request from '@/utils/request'

export function roleList(params) {
  return request({
    url: '/role/list',
    method: 'get',
    params: params
  })
}

export function roleInfo(params) {
  return request({
    url: '/role/info',
    method: 'get',
    params: params
  })
}

export function permissionList() {
  return request({
    url: '/role/permission_list',
    method: 'get'
  })
}

export function roleEdit(data) {
  return request({
    url: '/role/edit',
    method: 'post',
    data: data
  })
}

export function roleCreate(data) {
  return request({
    url: '/role/create',
    method: 'post',
    data: data
  })
}

export function roleDisble(data) {
  return request({
    url: '/role/disable',
    method: 'post',
    data: data
  })
}

export function roleDel(data) {
  return request({
    url: '/role/delete',
    method: 'post',
    data: data
  })
}