import request from '@/utils/request'
// 登录
export function login(data) {
  return request({
    url: '/user.User/login',
    method: 'post',
    data
  })
}
