// 员工角色
export function staffType(type) {
  const obj = {
    '1': '员工',
    '2': '家属',
    '3': '客户'
  }
  return obj[type]
}
