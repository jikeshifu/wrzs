import request from '@/utils/request'

// 获取加盟商列表
export function getJoininList(data) {
  return request({
    url: '/join.User/list',
    method: 'post',
    data
  })
}

// 添加加盟商账号
export function addJoinin(data) {
  return request({
    url: '/join.User/add',
    method: 'post',
    data
  })
}

// 编辑加盟商账号
export function editJoinin(data) {
  return request({
    url: '/join.User/edit',
    method: 'post',
    data
  })
}

// 删除加盟商账号
export function delJoinin(data) {
  return request({
    url: '/join.User/del',
    method: 'post',
    data
  })
}

// 修改加盟商账号启用状态
export function changeStatus(data) {
  return request({
    url: '/join.User/status',
    method: 'post',
    data
  })
}

// 获取加盟申请列表
export function getJoinApplyList(data) {
  return request({
    url: '/join.User/applyList',
    method: 'post',
    data
  })
}

// 联系状态更改
export function applyStatusChange(data) {
  return request({
    url: '/join.User/applyListStatus',
    method: 'post',
    data
  })
}

// 获取加盟商收益列表
export function getProfitList(data) {
  return request({
    url: '/join.User/profit',
    method: 'post',
    data
  })
}

// 获取加盟商提现申请列表
export function getWithdrawalList(data) {
  return request({
    url: '/join.Wthdrawal/list',
    method: 'post',
    data
  })
}

// 获取加盟商提现申请成功
export function examineSucc(data) {
  return request({
    url: '/join.Wthdrawal/examine',
    method: 'post',
    data
  })
}

// 修改加盟商密码
export function editJoinPwd(data) {
  return request({
    url: '/join.User/setPwd',
    method: 'post',
    data
  })
}