import request from '@/utils/request'

// 获取服务列表
export function getServiceList(data) {
  return request({
    url: '/room.RoomServer/list',
    method: 'post',
    data
  })
}

// 添加服务
export function addService(data) {
  return request({
    url: '/room.RoomServer/add',
    method: 'post',
    data
  })
}

// 编辑服务
export function editService(data) {
  return request({
    url: '/room.RoomServer/edit',
    method: 'post',
    data
  })
}

// 删除服务
export function delService(data) {
  return request({
    url: '/room.RoomServer/del',
    method: 'post',
    data
  })
}