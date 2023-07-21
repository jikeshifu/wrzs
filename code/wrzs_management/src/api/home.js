import request from '@/utils/request'

// 获取顶部4条数据
export function getTop4Data(data) {
  return request({
    url: '/statistics.Device/index',
    method: 'post',
    data
  })
}

// 获取一周内的历史记录
export function getAWeekHistory(data) {
  return request({
    url: '/statistics.Lock/historyNumber',
    method: 'post',
    data
  })
}
