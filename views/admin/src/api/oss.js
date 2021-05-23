import request from '@/utils/request'
export function policy(data) {
  return request({
    url: 'upload/image',
    method: 'post',
    data: data,
    contentType: 'multipart/form-data'
  })
}
