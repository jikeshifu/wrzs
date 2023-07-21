const state = {
  goodsData: {}
}

const getters = {

}

const mutations = {
  setGoodsData(state, data) {
    state.goodsData = data
  }
}

const actions = {
  setGoodsData({ commit }, data) {
    return new Promise((resolve, reject) => {
      commit('setGoodsData', data)
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
