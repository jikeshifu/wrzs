import request from '@/utils/request'

// 获取反馈列表
export function getFeedback(data) {
  return request({
    url: '/proposal.Proposal/list',
    method: 'post',
    data
  })
}

// 反馈处理
export function statusHandle(data) {
  return request({
    url: '/proposal.Proposal/status',
    method: 'post',
    data
  })
}