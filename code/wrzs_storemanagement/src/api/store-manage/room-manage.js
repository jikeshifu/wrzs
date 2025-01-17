import request from '@/utils/request'

// 获取房间列表
export function getRoomList(data) {
  return request({
    url: '/room.Room/list',
    method: 'post',
    data
  })
}

// 添加房间
export function addRoom(data) {
  return request({
    url: '/room.Room/add',
    method: 'post',
    data
  })
}

// 编辑房间
export function editRoom(data) {
  return request({
    url: '/room.Room/edit',
    method: 'post',
    data
  })
}

// 删除房间
export function delRoom(data) {
  return request({
    url: '/room.Room/del',
    method: 'post',
    data
  })
}

// 复制房间
export function copyRoom(data) {
  return request({
    url: '/room.Room/copy',
    method: 'post',
    data
  })
}

// 更改房间状态
export function editRoomStatus(data) {
  return request({
    url: '/room.Room/status',
    method: 'post',
    data
  })
}

// 更改房间顺序
export function editRoomSort(data) {
  return request({
    url: '/room.Room/sort',
    method: 'post',
    data
  })
}

// 更改房间自动关电状态
export function editRoomOffStatus(data) {
  return request({
    url: '/room.Room/electricStopStatus',
    method: 'post',
    data
  })
}

// 更改房间自动通电状态
export function editRoomOnStatus(data) {
  return request({
    url: '/room.Room/electricStartStatus',
    method: 'post',
    data
  })
}

// 修改房间用户是否可取消
export function editRoomCancelStatus(data) {
  return request({
    url: '/room.Room/cancelStatus',
    method: 'post',
    data
  })
}

// 房间结算功能改变
export function editRoomSettlStatus(data) {
  return request({
    url: '/room.Room/settlStatus',
    method: 'post',
    data
  })
}