// 计算时间戳之间的剩余的天时分秒
export function subtTime(minT, maxT) {
	// 时间差的毫秒数
	let timeDiff = new Date(maxT).getTime() - new Date(minT).getTime()
	// 计算出天数
	let days = Math.floor(timeDiff / (24 * 3600 * 1000))
	// 计算天数后剩余的时间
	let leavel1 = timeDiff % (24 * 3600 * 1000)
	// 计算天数后剩余的小时数
	let hours = Math.floor(leavel1 / (3600 * 1000))
	// 计算剩余小时后剩余的毫秒数
	let leavel2 = timeDiff % (3600 * 1000)
	// 计算剩余的分钟数
	let minutes = Math.floor(leavel2 / (60 * 1000))
	const fmt = (days ? days + '天' : '') + (hours ? hours + '小时' : '') + (minutes ? minutes + '分钟' : '')
	return {
		days,
		hours,
		minutes,
		fmt
	}
}

// 切割日期格式
export function dateFormatter(dateStr, fmtStr) {
	const newDate = new Date(dateStr)

	const year = newDate.getFullYear()
	const month = newDate.getMonth() + 1
	const date = newDate.getDate()

	const hours = newDate.getHours() >= 10 ? newDate.getHours() : ('0' + newDate.getHours())
	const minutes = newDate.getMinutes() >= 10 ? newDate.getMinutes() : ('0' + newDate.getMinutes())
	const seconds = newDate.getSeconds() >= 10 ? newDate.getSeconds() : ('0' + newDate.getSeconds())

	if (fmtStr === 'yyyy年mm月dd日 hh:mm') {
		return year + '年' + month + '月' + date + '日' + ' ' + hours + ':' + minutes
	}
	if (fmtStr === 'mm月dd日 hh:mm') {
		return month + '月' + date + '日' + ' ' + hours + ':' + minutes
	}
	if (fmtStr === 'yyyy年mm月') {
		return year + '年' + month + '月'
	}
	if (fmtStr === 'yyyy-mm-dd') {
		return year + '-' + month + '-' + date
	}
	if (fmtStr === 'yyyy-mm-dd hh:mm:ss') {
		return year + '-' + month + '-' + date + ' ' + hours + ':' + minutes + ':' + seconds
	}
}

// 获取当前日期月初，月底的时间戳
export function getMonthST_ED(d) {
	// 比如是 2021-9-27，或者是 2021-9-27 的时间戳
	const newDate = new Date(d)
	// 月初的
	const year = newDate.getFullYear()
	const month = newDate.getMonth() + 1
	const date = 1
	// 先拿到下个月月初的日期
	let year2 = year
	let month2 = month + 1
	let date2 = 1
	if (month2 > 12) {
		month2 = 1
		year2 += 1
	}
	// 月底的
	const newDate2 = new Date(new Date(year2 + '-' + month2 + '-' + date2).getTime() - 24 * 3600 * 1000)
	const year3 = newDate2.getFullYear()
	const month3 = newDate2.getMonth() + 1
	const date3 = newDate2.getDate()

	return {
		yc: year + '-' + month + '-' + date,
		yw: year3 + '-' + month3 + '-' + date3
	}
}
