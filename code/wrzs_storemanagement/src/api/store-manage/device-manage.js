import request from '@/utils/request'

// 获取设备列表
export function getDeviceList(data) {
  return request({
    url: '/device.Device/list',
    method: 'post',
    data
  })
}

// 添加设备
export function addDevice(data) {
  return request({
    url: '/device.Device/add',
    method: 'post',
    data
  })
}

// 编辑设备语音
export function editDevice(data) {
  return request({
    url: '/device.Device/voice',
    method: 'post',
    data
  })
}

// 编辑设备二维码
export function editDeviceEwm(data) {
  return request({
    url: '/device.Device/qr',
    method: 'post',
    data
  })
}

// 删除设备
export function delDevice(data) {
  return request({
    url: '/device.Device/del',
    method: 'post',
    data
  })
}
