const state = {
  sellDrawerManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.sellDrawerManage = data
  }
}

const actions = {
  setSellDrawerManageData({ commit }, data) {
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
