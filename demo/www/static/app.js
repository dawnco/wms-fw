// 3. 创建 router 实例，然后传 `routes` 配置
// 你还可以传别的配置参数, 不过先这么简单着吧。
const router = new VueRouter({
  routes // (缩写) 相当于 routes: routes
})

Vue.prototype.$tip = {
  success (msg) {
    Vue.prototype.$message({
      message: msg,
      type: 'success',
      showClose: true,
      duration: 3 * 1000
    })
  },
  error (msg) {
    Vue.prototype.$message({
      message: msg,
      type: 'error',
      showClose: true,
      duration: 3 * 1000
    })
  }
}

const app = new Vue({
  router,
  store
}).$mount('#app')
