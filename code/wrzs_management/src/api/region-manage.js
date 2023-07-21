import request from '@/utils/request'

// 获取区域列表
export function getRegionList(data) {
  return request({
    url: '/region.City/list',
    method: 'post',
    data
  })
}

// 添加区域
export function addRegion(data) {
  return request({
    url: '/region.City/add',
    method: 'post',
    data
  })
}

// 编辑区域
export function editRegion(data) {
  return request({
    url: '/region.City/edit',
    method: 'post',
    data
  })
}

// 删除区域
export function delRegion(data) {
  return request({
    url: '/region.City/del',
    method: 'post',
    data
  })
}