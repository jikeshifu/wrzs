import request from '@/utils/request'

// 获取订单列表
export function getOrderList(data) {
  return request({
    url: '/shop.Order/list',
    method: 'post',
    data
  })
}

// 发货操作
export function deliverHandle(data) {
  return request({
    url: '/shop.Order/deliver',
    method: 'post',
    data
  })
}

// 取消订单
export function cancelOrder(data) {
  return request({
    url: '/shop.Order/cancel',
    method: 'post',
    data
  })
}