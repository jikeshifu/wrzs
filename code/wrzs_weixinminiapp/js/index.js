export * from './date.js'
export * from './time.js'

// 错误信息提示
export function showMsg(msg) {
	uni.showToast({
		title: msg,
		icon: 'none'
	})
}

// 获取网址参数
export function getQueryString(url, name) {
	const reg = new RegExp('(^|&|/?)' + name + '=([^&|/?]*)(&|/?|$)', 'i')
	const r = url.substr(1).match(reg)
	if (r != null) {
		return r[2]
	}
	return null
}