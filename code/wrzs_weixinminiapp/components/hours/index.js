// 获取今天，明天，后台的日期
export function getDate() {
	const weeks = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六']
	// 这里用一个变量赋值的话，修改一个的状态，其他的也跟着变，所以要定义三个变量分配空间
	let times = [], times2 = [], times3 = []
	for (let i = 0; i <= 23; i++) {
		times.push({
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:00`
		}, {
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:30`
		})
		times2.push({
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:00`
		}, {
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:30`
		})
		times3.push({
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:00`
		}, {
			checked: false,
			active: false,
			hide: false,
			value: `${i >= 10 ? i : ('0' + i)}:30`
		})
	}
	// 今天
	const newDate = new Date()
	// 筛选下今日的日期
	times.forEach(item => {
		// 如果时间小于当前的时间，隐藏掉
		if (new Date(newDate.getFullYear() + '/' + (newDate.getMonth() + 1) + '/' + newDate.getDate() + ' ' + item.value + ':00').getTime() < newDate.getTime() - 3600 * 1000) {
			item.hide = true
		}
	})
	const today = {
		year: newDate.getFullYear(),
		month: newDate.getMonth() + 1,
		date: newDate.getDate(),
		week: weeks[newDate.getDay()],
		nyr: newDate.getFullYear() + '/' + (newDate.getMonth() + 1) + '/' + newDate.getDate(),
		times
	}
	// 明天
	const newDate2 = new Date(newDate.getTime() + 24 * 3600 * 1000)
	const tomorrow = {
		year: newDate2.getFullYear(),
		month: newDate2.getMonth() + 1,
		date: newDate2.getDate(),
		week: weeks[newDate2.getDay()],
		nyr: newDate2.getFullYear() + '/' + (newDate2.getMonth() + 1) + '/' + newDate2.getDate(),
		times: times2
	}
	// 后天
	const newDate3 = new Date(newDate2.getTime() + 24 * 3600 * 1000)
	const houtian = {
		year: newDate3.getFullYear(),
		month: newDate3.getMonth() + 1,
		date: newDate3.getDate(),
		week: weeks[newDate3.getDay()],
		nyr: newDate3.getFullYear() + '/' + (newDate3.getMonth() + 1) + '/' + newDate3.getDate(),
		times: times3
	}
	
	return [today, tomorrow, houtian]
}

// 判断日期是今天，明天还是后天，并分割格式
export function splitDate(dateStr) {
	const newDate = new Date()
	const myDate = new Date(dateStr)
	let date = ''
	let hours = myDate.getHours() >= 10 ? myDate.getHours() : ('0' + myDate.getHours())
	let minutes = myDate.getMinutes() >= 10 ? myDate.getMinutes() : ('0' + myDate.getMinutes())
	
	let d1 = myDate.getFullYear() + '-' + (myDate.getMonth() + 1) + '-' + myDate.getDate()
	let d2 = newDate.getFullYear() + '-' + (newDate.getMonth() + 1) + '-' + newDate.getDate()
	
	if (Date.parse(d1) - Date.parse(d2) === 0) {
		date = '今天'
	}
	if (Date.parse(d1) - Date.parse(d2) >= 86400000) {
		date = '明天'
	}
	if (Date.parse(d1) - Date.parse(d2) >= 172800000) {
		date = '后天'
	}
	
	return {
		date,
		hm: hours + ':' + minutes
	}
}