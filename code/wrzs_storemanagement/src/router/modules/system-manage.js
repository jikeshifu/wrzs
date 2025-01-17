/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const systemManage = {
  path: '/systemManage',
  component: Layout,
  name: 'SystemManage',
  redirect: '/systemManage/paymentConfig',
  meta: {
    title: '系统设置',
    icon: 'el-icon-s-tools'
  },
  children: [{
    path: '/systemManage/storeAdminManage',
    component: () => import('@/views/system-manage/store-admin-manage/admin-store-manage'),
    name: '/systemManage/storeAdminManage',
    meta: { title: '门店管理员', icon: 'el-icon kj kj-storeAdm' }
  }]
}

export default systemManage
