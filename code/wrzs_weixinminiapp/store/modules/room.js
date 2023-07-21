const state = {
	roomData: {}
}

const getters = {
	
}

const mutations = {
	initRoomData(state, data) {
		state.roomData = data
	}
}

const actions = {
	initRoomData({ commit }, data) {
		return new Promise((resolve, reject) => {
			commit('initRoomData', data)
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