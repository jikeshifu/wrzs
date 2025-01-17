const state = {
  roomManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.roomManage = data
  }
}

const actions = {
  setRoomData({ commit }, data) {
    return new Promise((resolve, reject) => {
      commit('SET_DATA', data)
      resolve(true)
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
