const state = {
	currentLocationInfo: {
	latitude:'31.77351499999999',
	longitude:'119.95000499999992'
	}
}

const getters = {
	
}

const mutations = {
	// 获取当前坐标地址
	getCurrentLocationInfo(state, info) {
		state.currentLocationInfo = info
	},
	// 设置默认坐标
	setDefaultPosition(state, data) {
		state.currentLocationInfo.latitude = data.latitude
		state.currentLocationInfo.longitude = data.longitude
	}
}

const actions = {
	// 获取当前坐标地址
	getCurrentLocationInfo({ commit }, map) {
		return new Promise((resolve, reject) => {
			// 获取经纬度
			uni.getLocation({
				type: 'gcj02',
				success: location => {
					const {
						latitude,
						longitude
					} = location
					uni.request({
						url: `https://api.map.baidu.com/reverse_geocoding/v3/?output=json&coordtype=wgs84l&ak=AGX9GxzOK67RrB65UwAEVvQxby2Q8Y08&location=${latitude},${longitude}`,
						success: res => {
							const {
								province,
								city,
								district
							} = res.data.result.addressComponent
							commit('getCurrentLocationInfo', {
								province,
								city,
								district,
								...location,
								map
							})
							resolve(true)
						}
					})
				},
				fail: _ => {
					uni.showModal({
						title: '温馨提示',
						content: '您已拒绝获取位置，部分功能将无法正常使用',
						confirmText: '重新获取',
						success: (btn) => {
							if (btn.confirm) {
								uni.openSetting({
								  success(res) {
										reject()
								  }
								})
							}
						}
					})
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