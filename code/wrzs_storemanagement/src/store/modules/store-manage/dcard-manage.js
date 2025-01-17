const state = {
  dcardManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.dcardManage = data
  }
}

const actions = {
  setDcardManageData({ commit }, data) {
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
