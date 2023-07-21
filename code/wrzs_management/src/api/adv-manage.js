import request from '@/utils/request'

// 获取广告位列表
export function getAdvList(data) {
  return request({
    url: '/ad.Ad/list',
    method: 'post',
    data
  })
}

// 添加广告位
export function addAdv(data) {
  return request({
    url: '/ad.Ad/add',
    method: 'post',
    data
  })
}

// 编辑广告位
export function editAdv(data) {
  return request({
    url: '/ad.Ad/edit',
    method: 'post',
    data
  })
}

// 删除广告位
export function delAdv(data) {
  return request({
    url: '/ad.Ad/del',
    method: 'post',
    data
  })
}