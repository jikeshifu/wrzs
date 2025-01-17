import request from '@/utils/request'

// 获取顶部4个数量
export function getTop4Data(data) {
  return request({
    url: '/config.System/info',
    method: 'post',
    data
  })
}

// 获取7日内的交易数量
export function getAWeekHistory(data) {
  return request({
    url: '/upload.File/upload',
    method: 'post',
    data
  })
}
