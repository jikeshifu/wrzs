import request from '@/utils/request'

// 获取用户列表
export function getUserList(data) {
  return request({
    url: '/member.Member/list',
    method: 'post',
    data
  })
}

// 用户充值
export function rechargeAPI(data) {
  return request({
    url: '/member.Member/recharge',
    method: 'post',
    data
  })
}