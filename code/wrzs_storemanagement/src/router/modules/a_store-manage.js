/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const storeManage = {
  path: '/storeManage',
  component: Layout,
  name: 'StoreManage',
  redirect: '/storeManage/list',
  meta: {
    title: '门店管理',
    icon: 'el-icon-s-tools'
  },
  children: [{
    path: '/storeManage/list',
    component: () => import('@/views/store-manage/store-manage'),
    name: '/storeManage/list',
    meta: { title: '门店列表', icon: 'el-icon-notebook-2' }
  }]
}

export default storeManage
