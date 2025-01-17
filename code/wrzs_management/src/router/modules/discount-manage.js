/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const discountManage = {
  path: '/discountManage',
  component: Layout,
  name: 'DiscountManage',
  meta: {
    title: '优惠管理',
    icon: 'el-icon iconfont icon-hui'
  },
  children: [{
    path: '/czPackageManage/list',
    component: () => import('@/views/discount-manage/cz-package-manage/cz-package-manage'),
    name: '/czPackageManage/list',
    meta: { title: '充值套餐', icon: 'el-icon iconfont icon-package' }
  }, {
    path: '/couponManage/list',
    component: () => import('@/views/discount-manage/coupon-manage/coupon-manage'),
    name: '/couponManage/list',
    meta: { title: '优惠券', icon: 'el-icon iconfont icon-coupon' }
  }]
}

export default discountManage
