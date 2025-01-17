/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const advManage = {
  path: '/advManage',
  component: Layout,
  name: 'AdvManage',
  redirect: '/advManage/list',
  meta: {
    title: '广告位管理',
    icon: 'el-icon iconfont icon-adv'
  },
  children: [{
    path: '/advManage/list',
    component: () => import('@/views/adv-manage/adv-manage'),
    name: '/advManage/list',
    meta: { title: '广告列表', icon: 'el-icon iconfont icon-adv' }
  }]
}

export default advManage
