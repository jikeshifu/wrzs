const baseUrl = 'https://was.weishequ.com'
import store from '@/store'
const apiRequest = object => {
	return new Promise((resolve, reject) => {
		uni.request({
			url: baseUrl + '/api' + object.url,
			method: object.method || 'POST',
			header: {
				'content-type': 'application/json',
				Authorization: uni.getStorageSync('token') || ''
			},
			data: object.data || {},
			success: async res => {
				if (res.data.error_code !== 0) {
					uni.hideLoading()
					// 登录过期
					if (res.data.error_code === 101) {
						await store.dispatch('USER/initUserInfo');
					let  reRequest =	await apiRequest({
							url: object.url,
							data: object.data
						}).catch((err)=>{
							reject(err)
							return
						})
							console.log("reRequest:",reRequest)
						if(reRequest){
							resolve(reRequest)
							return
						}
						
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
