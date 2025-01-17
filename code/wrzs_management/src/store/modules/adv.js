const state = {
  advData: {}
}

const getters = {

}

const mutations = {
  setAdvData(state, data) {
    state.advData = data
  }
}

export default {
  namespaced: true,
  state,
  getters,
  mutations
}
