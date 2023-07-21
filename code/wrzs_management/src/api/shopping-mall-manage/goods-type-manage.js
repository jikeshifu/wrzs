import request from '@/utils/request'

// 获取商品分类列表
export function getSPTypeList(data) {
  return request({
    url: '/shop.GoodsType/list',
    method: 'post',
    data
  })
}

// 添加商品分类
export function addSPType(data) {
  return request({
    url: '/shop.GoodsType/add',
    method: 'post',
    data
  })
}

// 编辑商品分类
export function editSPType(data) {
  return request({
    url: '/shop.GoodsType/edit',
    method: 'post',
    data
  })
}

// 删除商品分类
export function delSPType(data) {
  return request({
    url: '/shop.GoodsType/del',
    method: 'post',
    data
  })
}