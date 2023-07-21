import request from '@/utils/request'

// 登录
export function userLogin(data) {
  return request({
    url: '/user.Login/account',
    method: 'post',
    data
  })
}
