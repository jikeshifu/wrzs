/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const feedback = {
  path: '/order',
  component: Layout,
  name: 'Order',
  redirect: '/order',
  meta: {
    title: '订单列表',
    icon: 'el-icon iconfont icon-order sub-el-icon'
  },
  children: [{
    path: '/order/list',
    component: () => import('@/views/order/list'),
    name: 'Order',
    meta: { title: '订单列表', icon: 'el-icon iconfont icon-order sub-el-icon' }
  }]
}

export default feedback
