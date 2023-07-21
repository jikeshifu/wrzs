import dom from './index.vue'

const editPwd = {
  install(Vue) {
    const con = Vue.extend(dom)
    const inst = new con()
    inst.$mount(document.createElement('div'))
    document.body.appendChild(inst.$el)
    Vue.prototype.$editPwd = (callback) => {
      inst.dialogVisible = true
      inst.callback = callback
    }
  }
}
export default editPwd
