import request from '@/utils/request'

// 获取token
export function getUserToken(data) {
  return request({
    url: '/user.User/token',
    method: 'post',
    data
  })
}
