import request from '@/utils/request'

// 获取门店列表
export function getStoreList(data) {
  return request({
    url: '/store.Store/list',
    method: 'post',
    data
  })
}

// 添加门店
export function addStore(data) {
  return request({
    url: '/store.Store/add',
    method: 'post',
    data
  })
}

// 编辑门店
export function editStore(data) {
  return request({
    url: '/store.Store/edit',
    method: 'post',
    data
  })
}

// 删除门店
export function delStore(data) {
  return request({
    url: '/store.Store/del',
    method: 'post',
    data
  })
}

// 修改门店状态
export function editStoreStatus(data) {
  return request({
    url: '/store.Store/status',
    method: 'post',
    data
  })
}
