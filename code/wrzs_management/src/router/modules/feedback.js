/** When your routing table is too long, you can split it into small modules**/

import Layout from '@/layout'

const feedback = {
  path: '/feedback',
  component: Layout,
  name: 'Feedback',
  redirect: '/feedback/list',
  meta: {
    title: '意见发聩',
    icon: 'el-icon iconfont icon-apply'
  },
  children: [{
    path: '/feedback/list',
    component: () => import('@/views/feedback'),
    name: '/feedback/list',
    meta: { title: '反馈列表', icon: 'el-icon iconfont icon-apply' }
  }]
}

export default feedback
