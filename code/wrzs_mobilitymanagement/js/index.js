// export * from './date.js'
// export * from './time.js'

// 信息提示
export function showMsg(msg) {
	uni.showToast({
		icon: 'none',
		title: msg,
		mask: false,
		duration: 2000
	})
}

// 时间戳转年月日
export function dateFormatter(date, str) {
	let d = ''
	if (date) {
		d = new Date(date)
	} else {
		d = new Date()
	}
	const year = d.getFullYear()
	const month = d.getMonth() + 1
	const dt = d.getDate()
	const h = d.getHours()
	const m = d.getMinutes()
	const s = d.getSeconds()
	if (str === 'yyyy-mm-dd') {
		return year + '-' + month + '-' + dt
	}
	if (str === 'yyyy-mm-dd hh:mm:ss') {
		return year + '-' + month + '-' + dt + ' ' + h + ':' + m + ':' + s
	}
}