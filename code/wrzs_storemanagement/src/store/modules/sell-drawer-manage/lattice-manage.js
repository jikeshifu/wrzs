const state = {
  latticeManage: {}
}

const mutations = {
  SET_DATA(state, data) {
    state.latticeManage = data
  }
}

const actions = {
  setLatticeManageData({ commit }, data) {
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
