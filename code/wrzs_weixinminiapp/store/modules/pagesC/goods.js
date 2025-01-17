const state = {
	goodsData: {}
}

const getters = {
	
}

const mutations = {
	initGoodsData(state, data) {
		state.goodsData = data
	}
}

const actions = {
	initGoodsData({ commit }, data) {
		return new Promise((resolve, reject) => {
			commit('initGoodsData', data)
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