/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const regionManage = {
  path: '/regionManage',
  component: Layout,
  name: 'RegionManage',
  redirect: '/regionManage/list',
  meta: {
    title: '区域管理',
    icon: 'el-icon iconfont icon-area'
  },
  children: [{
    path: '/regionManage/list',
    component: () => import('@/views/region-manage/region-manage'),
    name: '/regionManage/list',
    meta: { title: '区域列表', icon: 'el-icon iconfont icon-city' }
  }]
}

export default regionManage
