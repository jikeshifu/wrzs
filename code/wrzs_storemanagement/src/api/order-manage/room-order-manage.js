import request from '@/utils/request'

// 获取房间列表
export function getRoomOrderList(data) {
  return request({
    url: '/order.Room/list',
    method: 'post',
    data
  })
}

// 退款
export function returnDeposit(data) {
  return request({
    url: '/order.Room/returnDeposit',
    method: 'post',
    data
  })
}

// 取消订单
export function cancelOrder(data) {
  return request({
    url: '/order.Cancel/index',
    method: 'post',
    data
  })
}
