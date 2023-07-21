const state = {
  joininData: {}
}

const getters = {

}

const mutations = {
  setJoininData(state, data) {
    state.joininData = data
  }
}

const actions = {
  setJoininData({ commit }, data) {
    return new Promise((resolve, reject) => {
      commit('setJoininData', data)
      resolve()
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
