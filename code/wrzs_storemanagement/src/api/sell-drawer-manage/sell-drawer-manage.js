import request from '@/utils/request'

// 获取售货柜列表
export function getSellDrawerList(data) {
  return request({
    url: '/cabinet.Cabinet/list',
    method: 'post',
    data
  })
}

// 添加售货柜
export function addSellDrawer(data) {
  return request({
    url: '/cabinet.Cabinet/add',
    method: 'post',
    data
  })
}

// 编辑售货柜
export function editSellDrawer(data) {
  return request({
    url: '/cabinet.Cabinet/edit',
    method: 'post',
    data
  })
}

// 删除售货柜
export function delSellDrawer(data) {
  return request({
    url: '/cabinet.Cabinet/del',
    method: 'post',
    data
  })
}
