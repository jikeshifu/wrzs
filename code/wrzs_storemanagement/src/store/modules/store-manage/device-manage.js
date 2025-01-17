const state = {
  deviceManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.deviceManage = data
  }
}

const actions = {
  setDeviceManageData({ commit }, data) {
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
