import request from '@/utils/request'


export function menuList() {
  return request({
    url: 'menu/list',
    method: 'get'
  })
}
