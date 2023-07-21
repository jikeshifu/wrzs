import request from '@/utils/request'

// 获取提现列表
export function getWithdrawalList(data) {
  return request({
    url: '/join.Wthdrawal/list',
    method: 'post',
    data
  })
}
