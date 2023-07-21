import request from '@/utils/request'

// 获取满减列表
export function getManJianList(data) {
  return request({
    url: '/discount.Reduce/list',
    method: 'post',
    data
  })
}

// 添加满减
export function addManJian(data) {
  return request({
    url: '/discount.Reduce/add',
    method: 'post',
    data
  })
}

// 编辑满减
export function editManJian(data) {
  return request({
    url: '/discount.Reduce/edit',
    method: 'post',
    data
  })
}

// 删除满减
export function delManJian(data) {
  return request({
    url: '/discount.Reduce/del',
    method: 'post',
    data
  })
}