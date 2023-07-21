import request from '@/utils/request'

// 获取优惠券列表
export function getCouponList(data) {
  return request({
    url: '/discount.Coupons/list',
    method: 'post',
    data
  })
}

// 添加优惠券
export function addCoupon(data) {
  return request({
    url: '/discount.Coupons/add',
    method: 'post',
    data
  })
}

// 编辑优惠券
export function editCoupon(data) {
  return request({
    url: '/discount.Coupons/edit',
    method: 'post',
    data
  })
}

// 删除优惠券
export function delCoupon(data) {
  return request({
    url: '/discount.Coupons/del',
    method: 'post',
    data
  })
}

// 赠送个人优惠券
export function sendCoupon(data) {
  return request({
    url: '/discount.Coupons/give',
    method: 'post',
    data
  })
}

// 赠送所有用户优惠券
export function sendAllUserCoupon(data) {
  return request({
    url: '/discount.Coupons/gives',
    method: 'post',
    data
  })
}