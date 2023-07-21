// 秒数转时分秒
export function secondToTimeStr(t) {
	let hours = ''
	let minutes = ''
	let seconds = ''
	if (!t) {
		return
	}
	if (t < 60) {
		hours = 0
		minutes = 0
		seconds = t
	}
	if (t < 3600) {
		hours = 0
		minutes = parseInt(t / 60)
		seconds = t % 60
	}
	if (3600 <= t) {
		hours = parseInt(t / 3600)
		minutes = parseInt(t % 3600 / 60)
		seconds = t % 60
	}
	return {
		hours,
		minutes,
		seconds
	}
}
