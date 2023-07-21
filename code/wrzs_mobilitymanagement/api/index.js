const baseUrl = 'https://was.weishequ.com'

const apiRequest = object => {
	return new Promise((resolve, reject) => {
		uni.request({
			url: baseUrl + '/admin_h5_api' + object.url,
			method: object.method || 'POST',
			header: {
				'content-type': 'application/json',
				Authorization: uni.getStorageSync('token') || ''
			},
			data: object.data || {},
			success: res => {
				if (res.data.status !== 1) {
					uni.hideLoading()
					// 登录过期
					if (res.data.status === 0 && res.data.error_code === 101) {
						uni.showModal({
							title: '温馨提示',
							content: '登录已超时，请重新登录',
							confirmText: '重新登录',
							showCancel: false,
							complete: () => {
								uni.clearStorageSync()
								uni.reLaunch({
									url: '/pages/login/login'
								})
							}
						})
					} else {
						uni.showToast({
							icon: 'none',
							title: res.data.msg || '似乎出现了错误，请联系客服',
							mask: false,
							duration: 2000
						})
					}
					console.log('错误信息', res)
					reject(res.data.msg || '似乎出现了错误，请联系客服')
				} else {
					resolve(res.data)
				}
			},
			fail: (res) => {
				uni.hideLoading()
				uni.showToast({
					icon: 'none',
					title: '网络似乎出了点问题，请稍后重试',
					mask: false,
					duration: 2000
				})
				console.log('错误信息', res)
				reject('网络似乎出了点问题，请稍后重试')
			}
		})
	})
}

module.exports = {
	baseUrl,
	apiRequest
}