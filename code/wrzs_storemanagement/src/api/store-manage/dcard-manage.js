import request from '@/utils/request'

// 获取门卡列表
export function getDcardList(data) {
  return request({
    url: '/device.Card/list',
    method: 'post',
    data
  })
}

// 添加门卡
export function addDcard(data) {
  return request({
    url: '/device.Card/add',
    method: 'post',
    data
  })
}

// 删除门卡
export function delDcard(data) {
  return request({
    url: '/device.Card/del',
    method: 'post',
    data
  })
}
