const state = {
  storeAdminManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.storeAdminManage = data
  }
}

const actions = {
  setStoreAdminData({ commit }, data) {
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
