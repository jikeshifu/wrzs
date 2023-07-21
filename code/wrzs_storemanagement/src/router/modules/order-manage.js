/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const orderManage = {
  path: '/orderManage',
  component: Layout,
  name: 'OrderManage',
  redirect: '/roomOrder/list',
  meta: {
    title: '订单管理',
    icon: 'el-icon-document'
  },
  children: [{
    path: '/roomOrder/list',
    component: () => import('@/views/order-manage/room-order-manage/room-order-manage'),
    name: '/roomOrder/list',
    meta: { title: '房间订单', icon: 'el-icon-document' }
  }, {
    path: '/shgspOrder/list',
    component: () => import('@/views/order-manage/shgsp-order-manage/shgsp-order-manage'),
    name: '/shgspOrder/list',
    meta: { title: '售货柜商品订单', icon: 'el-icon-document' }
  }]
}

export default orderManage
