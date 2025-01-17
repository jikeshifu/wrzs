import Vue from 'vue'
import App from './App'
import store from '@/store'
import * as pubJS from '@/js/index.js'
import {
	apiRequest
} from '@/api/index.js'

import precisionArithmetic from 'high-precision-four-fundamental-rules'

import 'normalize.css'
import '@/mixins/index'

Vue.config.productionTip = false

App.mpType = 'app'

Object.keys(pubJS).forEach(item => {
	Vue.prototype['$' + item] = pubJS[item]
})

Vue.prototype.$apiRequest = apiRequest
Vue.prototype.$calc = precisionArithmetic

import wLoading from '@/components/w-loading.vue'
import Topbar from '@/components/Topbar.vue'
import UniLoadMore from '@/components/uni-load-more.vue'
import NoData from '@/components/NoData.vue'

Vue.component('wLoading', wLoading)
Vue.component('Topbar', Topbar)
Vue.component('UniLoadMore', UniLoadMore)
Vue.component('NoData', NoData)

const app = new Vue({
	store,
	...App
})
app.$mount()
