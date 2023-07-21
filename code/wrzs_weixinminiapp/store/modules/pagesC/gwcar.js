import { apiRequest } from '@/api/index.js'
import precisionArithmetic from 'high-precision-four-fundamental-rules'

const state = {
	gwcarData: []
}

const getters = {
	totalMoney(state) {
		let total = 0
		state.gwcarData.forEach(item => {
			total = total + +precisionArithmetic.multiply(item.number, item.goodsInfo.goods_price, 2)
		})
		return total
	}
}

const mutations = {
	initGwcarData(state, data) {
		state.gwcarData = data
	}
}

const actions = {
	initGwcarData({ commit }, data) {
		return new Promise(async (resolve, reject) => {
			const data = await apiRequest({
				url: '/shop.ShoppingCar/list'
			})
			commit('initGwcarData', data.list)
			resolve(true)
		})
	}
}

export default {
	namespaced: true,
	state,
	getters,
	mutations,
	actions
}