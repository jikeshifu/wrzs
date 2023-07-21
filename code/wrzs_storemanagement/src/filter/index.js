// 星期 - 数字转中文
export function numTransZh_Week(num) {
  const obj = {
    '1': '星期一',
    '2': '星期二',
    '3': '星期三',
    '4': '星期四',
    '5': '星期五',
    '6': '星期六',
    '7': '星期日'
  }
  return obj[num]
}

// 数字转中文设备类型
export function numTransZh_deviceType(num) {
  const obj = {
    '1': '门锁/门禁',
    '2': '大门/公共门',
    '3': '空开/断路器',
    '4': '云喇叭'
  }
  return obj[num]
}
