import { apiRequest } from '@/api/index.js'

const state = {
	userInfo: uni.getStorageSync('userInfo') || {},
	myAsset: {
		money: 0,
		coupon_number: 0,
		integral: 0
	}
}

const getters = {
	isLogin(state, getters) {
		return state.userInfo.nick_name ? true : false
	}
}

const mutations = {
	initUserInfo(state, data) {
		state.userInfo = data.userInfo
		uni.setStorageSync('userInfo', data.userInfo)
		uni.setStorageSync('token', data.token)
	},
	resetUserInfo(state, userInfo) {
		uni.setStorageSync('userInfo', userInfo)
		state.userInfo = userInfo
	},
	getAssetInfo(state, data) {
		state.myAsset = data
	}
}

const actions = {
	// 初始化用户信息
	initUserInfo({ commit }) {
		return new Promise((resolve, reject) => {
			uni.login({
				success: async res => {
					const data = await apiRequest({
						url: '/watch.user/login',
						data: {
							code: res.code
						}
					})
					commit('initUserInfo', data)
					resolve(true)
				}
			})
		})
	},
	// 获取我的资产信息
	getAssetInfo({ commit }) {
		return new Promise(async (resolve, reject) => {
			const data = await apiRequest({
				url: '/user.Balance/info'
			})
			commit('getAssetInfo', data.info)
			resolve(true)
		})
	},
	// 修改用户信息
	editUserInfo({ commit }) {
		return new Promise((resolve, reject) => {
			uni.getUserProfile({
				desc: '获取您的头像、性别',
				success: async res => {
					await apiRequest({
						url: '/watch.user/edit',
						data: {
							nick_name: res.userInfo.nickName,
							avatar_url: res.userInfo.avatarUrl
						}
					})
					const userInfo = uni.getStorageSync('userInfo')
					userInfo.nick_name = res.userInfo.nickName
					userInfo.avatar_url = res.userInfo.avatarUrl
					commit('resetUserInfo', userInfo)
					resolve(true)
				}
			})
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