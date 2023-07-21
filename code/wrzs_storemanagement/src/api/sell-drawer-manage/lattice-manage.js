import request from '@/utils/request'

// 获取商品格列表
export function getLatticeList(data) {
  return request({
    url: '/cabinet.Goods/list',
    method: 'post',
    data
  })
}

// 添加商品格
export function addLattice(data) {
  return request({
    url: '/cabinet.Goods/add',
    method: 'post',
    data
  })
}

// 删除商品格
export function delLattice(data) {
  return request({
    url: '/cabinet.Goods/del',
    method: 'post',
    data
  })
}

// 编辑商品格
export function editLattice(data) {
  return request({
    url: '/cabinet.Goods/edit',
    method: 'post',
    data
  })
}

// 编辑商品格状态
export function editLatticeStatus(data) {
  return request({
    url: '/cabinet.Goods/status',
    method: 'post',
    data
  })
}
