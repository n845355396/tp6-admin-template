import request from '@/utils/request'


export function menuList(params) {
  return request({
    url: 'menu/list',
    method: 'get',
    params: params
  })
}

export function menuInfo(params) {
  return request({
    url: 'menu/info',
    method: 'get',
    params: params
  })
}

export function menuCreate(data) {
  return request({
    url: 'menu/create',
    method: 'post',
    data: data
  })
}

export function menuEdit(data) {
  return request({
    url: 'menu/edit',
    method: 'post',
    data: data
  })
}

export function menuHidden(data) {
  return request({
    url: 'menu/hidden',
    method: 'post',
    data: data
  })
}

export function menuDelete(data) {
  return request({
    url: 'menu/delete',
    method: 'post',
    data: data
  })
}