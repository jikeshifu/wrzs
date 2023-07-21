export default {
  methods: {
    // 日期之间的时间差
    $diffTime(startTime, endTime) {
      let stime = new Date(startTime).getTime()
      let etime = new Date(endTime).getTime()
      // 两个时间戳相差的毫秒数
      let usedTime = etime - stime
      // 计算相差的天数
      let days = Math.floor(usedTime / (24 * 3600 * 1000))
      // 计算天数后剩余的毫秒数
      let leave1 = usedTime % (24 * 3600 * 1000)
      // 计算出小时数
      let hours = Math.floor(leave1 / (3600 * 1000))
      // 计算小时数后剩余的毫秒数
      let leave2 = leave1 % (3600 * 1000)
      // 计算相差分钟数
      let minutes = Math.floor(leave2 / (60 * 1000))
      let time = ''
    
      if (days >= 1) {
        time = days + '天' + hours + '小时' + (minutes > 0 ? (minutes + '分钟') : '')
      } else if (hours > 0) {
        time = hours + '小时' + (minutes > 0 ? (minutes + '分钟') : '')
      } else {
        time = minutes + '分钟'
      }
      return {
        days,
        fmt: time,
        hours,
        minutes
      }
    }
  }
}