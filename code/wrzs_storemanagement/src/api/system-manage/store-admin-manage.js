import request from '@/utils/request'

// 获取门店管理员列表
export function getStoreAdminList(data) {
  return request({
    url: '/config.StoreAdmin/list',
    method: 'post',
    data
  })
}

// 添加门店管理员
export function addStoreAdmin(data) {
  return request({
    url: '/config.StoreAdmin/add',
    method: 'post',
    data
  })
}

// 编辑门店管理员
export function editStoreAdmin(data) {
  return request({
    url: '/config.StoreAdmin/edit',
    method: 'post',
    data
  })
}

// 删除门店管理员
export function delStoreAdmin(data) {
  return request({
    url: '/config.StoreAdmin/del',
    method: 'post',
    data
  })
}

// 短信通知编辑
export function smsEdit(data) {
  return request({
    url: '/config.StoreAdmin/editSmsStatus',
    method: 'post',
    data
  })
}

// 修改门店管理员状态
export function editStoreAdmStatus(data) {
  return request({
    url: '/config.StoreAdmin/editStatus',
    method: 'post',
    data
  })
}

// 修改密码
export function editPwd(data) {
  return request({
    url: '/config.StoreAdmin/resetPw',
    method: 'post',
    data
  })
}
