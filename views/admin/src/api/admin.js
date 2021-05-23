import request from '@/utils/request'

export function adminList(params) {
  return request({
    url: 'admin/list',
    method: 'get',
    params: params
  })
}

export function adminInfo(params) {
  return request({
    url: 'admin/info',
    method: 'get',
    params: params
  })
}

export function adminEdit(data) {
  return request({
    url: 'admin/edit',
    method: 'post',
    data: data
  })
}

export function adminCreate(data) {
  return request({
    url: 'admin/create',
    method: 'post',
    data: data
  })
}

export function adminDel(data) {
  return request({
    url: 'admin/delete',
    method: 'post',
    data: data
  })
}

export function adminDisble(data) {
  return request({
    url: 'admin/disable',
    method: 'post',
    data: data
  })
}


export function adminUpPassword(data) {
  return request({
    url: 'admin/up_password',
    method: 'post',
    data: data
  })
}

