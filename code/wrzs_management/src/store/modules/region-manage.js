const state = {
  regionData: {}
}

const getters = {

}

const mutations = {
  setRegionData(state, data) {
    state.regionData = data
  }
}

const actions = {
  setRegionData({ commit }, data) {
    return new Promise((resolve, reject) => {
      commit('setRegionData', data)
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