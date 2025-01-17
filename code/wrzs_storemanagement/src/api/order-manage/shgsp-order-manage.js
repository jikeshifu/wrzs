import request from '@/utils/request'

// 获取售货柜商品列表
export function getSHGSPOrderList(data) {
  return request({
    url: '/order.Cabinet/list',
    method: 'post',
    data
  })
}

// 获取售货柜商品列表
export function cancelOrder(data) {
  return request({
    url: '/order.Cabinet/cancel',
    method: 'post',
    data
  })
}