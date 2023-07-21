import request from '@/utils/request'

// 修改密码
export function editPwd(data) {
  return request({
    url: '/s.User/pwd',
    method: 'post',
    data
  })
}