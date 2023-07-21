/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const userManage = {
  path: '/userManage',
  component: Layout,
  name: 'UserManage',
  redirect: '/userManage/list',
  meta: {
    title: '用户管理',
    icon: 'el-icon-user-solid'
  },
  children: [{
    path: '/userManage/list',
    component: () => import('@/views/user-manage/user-manage'),
    name: '/userManage/list',
    meta: { title: '用户列表', icon: 'el-icon-user' }
  }]
}

export default userManage
