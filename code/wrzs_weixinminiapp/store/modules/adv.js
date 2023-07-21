const state = {
	advData: null
}

const mutations = {
	setAdvData(state, data) {
		state.advData = data
	}
}

export default {
	namespaced: true,
	state,
	mutations
}