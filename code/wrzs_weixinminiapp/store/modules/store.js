const state = {
	storeData: {}
}

const getters = {
	
}

const mutations = {
	initStoreData(state, data) {
		state.storeData = data
	}
}

const actions = {
	initStoreData({ commit }, data) {
		return new Promise((resolve, reject) => {
			commit('initStoreData', data)
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