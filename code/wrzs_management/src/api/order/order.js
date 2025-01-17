import request from '@/utils/request'

// 获取订单列表
export function getOrderList(data) {
  return request({
    url: '/order.Order/list',
    method: 'post',
    data
  })
}

// 取消订单
export function cancelOrder(data) {
  return request({
    url: '/order.Order/cancel',
    method: 'post',
    data
  })
}
