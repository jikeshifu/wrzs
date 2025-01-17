/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const joininManage = {
  path: '/joiinManage',
  component: Layout,
  name: 'JoiinManage',
  redirect: '/joiinManage/list',
  meta: {
    title: '加盟商管理',
    icon: 'el-icon iconfont icon-jm'
  },
  children: [{
    path: '/joiinManage/list',
    component: () => import('@/views/joinin-manage/joinin-manage'),
    name: '/joiinManage/list',
    meta: { title: '加盟商列表', icon: 'el-icon iconfont icon-jmlb' }
  }, {
    path: '/joiinManage/applyManage/list',
    component: () => import('@/views/joinin-manage/apply-manage/apply-manage'),
    name: '/joiinManage/applyManage/list',
    meta: { title: '加盟申请', icon: 'el-icon iconfont icon-apply' }
  }, {
    path: '/joiinManage/withdrawal/list',
    component: () => import('@/views/joinin-manage/withdrawal/index'),
    name: '/joiinManage/withdrawal/list',
    meta: { title: '提现申请', icon: 'el-icon-s-finance' }
  }]
}

export default joininManage
