import Vue from 'vue'
import App from './App'

import {
	apiRequest,
	baseUrl
} from '@/api/index.js'

import * as pubJS from '@/js/index.js'
Object.keys(pubJS).forEach(item => {
	Vue.prototype['$' + item] = pubJS[item]
})

Vue.prototype.$apiRequest = apiRequest
Vue.prototype.$baseUrl = baseUrl

Vue.config.productionTip = false

import 'normalize.css'

App.mpType = 'app'

const app = new Vue({
	...App
})
app.$mount()
