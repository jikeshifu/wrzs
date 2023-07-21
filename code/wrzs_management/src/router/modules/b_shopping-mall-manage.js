/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const shoppingMallManage = {
  path: '/shoppingMallManage',
  component: Layout,
  name: 'ShoppingMallManage',
  redirect: '/shoppingMallManage/goods-manage/list',
  meta: {
    title: '商城管理',
    icon: 'el-icon iconfont icon-sc'
  },
  children: [{
    path: '/shoppingMallManage/goods-manage/list',
    component: () => import('@/views/shopping-mall-manage/goods-manage/goods-manage'),
    name: '/shoppingMallManage/goods-manage/list',
    meta: { title: '商品列表', icon: 'el-icon iconfont icon-sp' }
  }, {
    path: '/shoppingMallManage/goods-type-manage/list',
    component: () => import('@/views/shopping-mall-manage/goods-type-manage/goods-type-manage'),
    name: '/shoppingMallManage/goods-type-manage/list',
    meta: { title: '商品分类', icon: 'el-icon iconfont icon-fz' }
  }, {
    path: '/shoppingMallManage/order-manage/list',
    component: () => import('@/views/shopping-mall-manage/order-manage/order-manage'),
    name: '/shoppingMallManage/order-manage/list',
    meta: { title: '订单管理', icon: 'el-icon iconfont icon-order' }
  }]
}

export default shoppingMallManage
