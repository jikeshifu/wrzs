const state = {
  storeManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.storeManage = data
  }
}

const actions = {
  setStoreManageData({ commit }, data) {
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
