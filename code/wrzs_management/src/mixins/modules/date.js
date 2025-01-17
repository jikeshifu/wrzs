export default {
  methods: {
    // 时间转换为日期格式
    $dateFormatter(time, type) {
      let nowDate = new Date(time)
      let year = nowDate.getFullYear()
      let month = nowDate.getMonth() + 1 < 10 ? '0' + (nowDate.getMonth() + 1) : nowDate.getMonth() + 1
      let day = nowDate.getDate() < 10 ? '0' + nowDate.getDate() : nowDate.getDate()
      let hours = nowDate.getHours() < 10 ? '0' + nowDate.getHours() : nowDate.getHours()
      let minutes = nowDate.getMinutes() < 10 ? '0' + nowDate.getMinutes() : nowDate.getMinutes()
      let seconds = nowDate.getSeconds() < 10 ? '0' + nowDate.getSeconds() : nowDate.getSeconds()
      if (type === 'yy-mm-dd') {
        return year + '-' + month + '-' + day
      }
      if (type === 'yy-mm-dd hh:mm:ss') {
        return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes + ':' + seconds
      }
      if (type === 'yy-mm-dd hh:mm') {
        return year + '-' + month + '-' + day + ' ' + hours + ':' + minutes
      }
      if (type === 'yy-mm-dd hh') {
        return year + '-' + month + '-' + day + ' ' + hours + ':' + '00'
      }
    }
  }
}
