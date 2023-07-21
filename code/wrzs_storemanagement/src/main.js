import Vue from 'vue'

import 'normalize.css/normalize.css' // A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
// import locale from 'element-ui/lib/locale/lang/en' // lang i18n

import '@/styles/index.scss' // global css

import * as filter from './filter/index'
Object.keys(filter).forEach(item => {
  Vue.filter(item, filter[item])
})

import App from './App'
import store from './store'
import router from './router'
import VueParticles from 'vue-particles'

import '@/icons' // icon
import '@/permission' // permission control
import '@/mixins/index'// mixins

/**
 * If you don't want to use mock-server
 * you want to use MockJs for mock api
 * you can execute: mockXHR()
 *
 * Currently MockJs will be used in the production environment,
 * please remove it before going online ! ! !
 */
// if (process.env.NODE_ENV === 'production') {
//   const { mockXHR } = require('../mock')
//   mockXHR()
// }

ElementUI.Dialog.props.closeOnClickModal.default = false

// set ElementUI lang to EN
// Vue.use(ElementUI, { locale })
// 如果想要中文版 element-ui，按如下方式声明
Vue.use(ElementUI)
Vue.use(VueParticles)
// 公用分页
import Pagination from '@/components/Pagination/index'
Vue.use(Pagination)
Vue.component('Pagination', Pagination)

Vue.config.productionTip = false

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App)
})
