/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const sellDrawerManage = {
  path: '/sellDrawerManage',
  component: Layout,
  name: 'SellDrawerManage',
  redirect: '/sellDrawerManage/list',
  meta: {
    title: '售货柜管理',
    icon: 'el-icon-refrigerator'
  },
  children: [{
    path: '/sellDrawerManage/list',
    component: () => import('@/views/sell-drawer-manage/sell-drawer-manage'),
    name: '/sellDrawerManage/list',
    meta: { title: '售货柜列表', icon: 'el-icon-receiving' }
  }]
}

export default sellDrawerManage
