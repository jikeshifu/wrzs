import request from '@/utils/request'

// 获取设备记录列表
export function getProfitList(data) {
  return request({
    url: '/join.User/profit',
    method: 'post',
    data
  })
}
