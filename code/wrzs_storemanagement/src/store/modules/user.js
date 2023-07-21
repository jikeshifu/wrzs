import { getToken, setToken, removeToken } from '@/utils/auth'
// import { resetRouter } from '@/router'
import { MessageBox } from "element-ui"
import {userLogin} from "@/api/login"

const state = {
  token: getToken(),
  userInfo: {}
}

const mutations = {
  SET_TOKEN: (state, token) => {
    state.token = token
  },
  setUserInfo: (state, data) => {
    state.userInfo = data
  },
  SET_NAME: (state, name) => {
    state.name = name
  },
  SET_AVATAR: (state, avatar) => {
    state.avatar = avatar
  },
  SET_ROLES: (state, roles) => {
    state.roles = roles
  }
}

const actions = {
  // user login
  login({ commit }, form) {
    return new Promise((resolve, reject) => {
      userLogin(form).then(res => {
        commit('SET_TOKEN', res.token)
        setToken(res.token)
        resolve()
      }).catch(error => {
        reject(error)
      })
    })
  },

  // get user info
  getInfo({ commit, state }) {
    return new Promise((resolve, reject) => {
      // getInfo(state.token).then(response => {
      //   const { data } = response
      //
      //   if (!data) {
      //     return reject('Verification failed, please Login again.')
      //   }
      //
      //   const { roles, name, avatar } = data
      //
      //   // roles must be a non-empty array
      //   if (!roles || roles.length <= 0) {
      //     reject('getInfo: roles must be a non-null array!')
      //   }
      //
      //   commit('SET_ROLES', roles)
      //   commit('SET_NAME', name)
      //   commit('SET_AVATAR', avatar)
      //   resolve(data)
      // }).catch(error => {
      //   reject(error)
      // })
    })
  },

  // user logout
  logout({ commit, state, dispatch }) {
    return new Promise((resolve, reject) => {
      // logout(state.token).then(() => {
      //   removeToken() // must remove  token  first
      //   // resetRouter()
      //   commit('SET_TOKEN', '')
      //   commit('SET_ROLES', [])
      //   dispatch('tagsView/delAllViews', null, { root: true })
      //   resolve()
      // }).catch(error => {
      //   reject(error)
      // })
      removeToken()
      commit('SET_TOKEN', '')
      dispatch('tagsView/delAllViews', null, { root: true })
      resolve()
    })
  },

  // remove token
  resetToken({ commit }) {
    return new Promise(resolve => {
      removeToken() // must remove  token  first
      commit('SET_TOKEN', '')
      resolve()
    })
  }
}

export default {
  namespaced: true,
  state,
  mutations,
  actions
}
