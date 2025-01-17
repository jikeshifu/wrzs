import request from '@/utils/request'

// 获取设备记录列表
export function getDeviceRecordList(data) {
  return request({
    url: '/device.Record/list',
    method: 'post',
    data
  })
}
