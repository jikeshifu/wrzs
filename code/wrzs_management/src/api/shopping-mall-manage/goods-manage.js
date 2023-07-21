import request from '@/utils/request'

// 获取商品列表
export function getSPList(data) {
  return request({
    url: '/shop.Goods/list',
    method: 'post',
    data
  })
}

// 添加商品
export function addSP(data) {
  return request({
    url: '/shop.Goods/add',
    method: 'post',
    data
  })
}

// 编辑商品
export function editSP(data) {
  return request({
    url: '/shop.Goods/edit',
    method: 'post',
    data
  })
}


// 删除商品
export function delSP(data) {
  return request({
    url: '/shop.Goods/del',
    method: 'post',
    data
  })
}