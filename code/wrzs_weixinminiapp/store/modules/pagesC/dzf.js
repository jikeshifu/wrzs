import { apiRequest } from '@/api/index.js'

const state = {
	dzfCount: 0
}

const getters = {
	
}

const mutations = {
	initDZFCount(state, data) {
		state.dzfCount = data
	}
}

const actions = {
	initDZFCount({ commit }, data) {
		return new Promise(async (resolve, reject) => {
			const data = await apiRequest({
				url: '/order.Goods/list',
				data: {
					type: 1
				}
			})
			commit('initDZFCount', data.count)
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