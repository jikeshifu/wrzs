import request from '@/utils/request'

// 获取套餐列表
export function getCzPackageList(data) {
  return request({
    url: '/recharge.Package/list',
    method: 'post',
    data
  })
}

// 添加套餐
export function addPackage(data) {
  return request({
    url: '/recharge.Package/add',
    method: 'post',
    data
  })
}

// 编辑套餐
export function editPackage(data) {
  return request({
    url: '/recharge.Package/edit',
    method: 'post',
    data
  })
}

// 删除套餐
export function delPackage(data) {
  return request({
    url: '/recharge.Package/del',
    method: 'post',
    data
  })
}