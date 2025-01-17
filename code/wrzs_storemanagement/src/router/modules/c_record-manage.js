/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const recordManage = {
  path: '/recordManage',
  component: Layout,
  name: 'RecordManage',
  redirect: '/recordManage/deviceList',
  meta: {
    title: '记录管理',
    icon: 'el-icon-document'
  },
  children: [{
    path: '/recordManage/deviceList',
    component: () => import('@/views/record-manage/device/device'),
    name: '/recordManage/device',
    meta: { title: '设备记录', icon: 'el-icon-cpu' }
  }, {
    path: '/recordManage/profitList',
    component: () => import('@/views/record-manage/profit/profit'),
    name: '/recordManage/profitList',
    meta: { title: '收益明细', icon: 'el-icon-s-marketing' }
  }, {
    path: '/recordManage/withdrawal',
    component: () => import('@/views/record-manage/withdrawal/withdrawal'),
    name: '/recordManage/withdrawal',
    meta: { title: '提现记录', icon: 'el-icon-s-finance' }
  }]
}

export default recordManage
