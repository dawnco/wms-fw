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

Vue.prototype.$custom = {
  confirm (message) {
    return new Promise((resolve, reject) => {
      Vue.prototype.$confirm(message, '提示', { type: 'warning' }).then(resolve).catch(() => {
      })
    })
  },
  tip (message = '操作成功', type = 'success') {
    Vue.prototype.$message({
      showClose: true,
      message: message,
      type: type
    })
  }
}

Vue.mixin({
  data: () => {
    return {
      MODEL: '',
      webConfig: {
        labelWidth: '100px',
        pageSizes: [10, 20, 50, 100],
        pageLayout: 'total, sizes, prev, pager, next, jumper'
      },
      search: {
        pageSize: 10
      },
      formData: {},
      searchLoading: false,
      submitLoading: false,
      customDataLoading: false,
      formVisible: false,
      formTitle: '新增'
    }
  },
  methods: {
    pageSizeChange (size) {
      this.search.pageSize = size
      this.search.page = 1
      this.loadData()
    },
    pageChange (page) {
      this.search.page = page
      this.loadData()
    },
    handleShowAdd () {
      this.formData = {}
      this.formVisible = true
    },
    handleSave () {
      this.submitLoading = true

      let id = this.formData.id || ''

      let method = 'post'
      if (id) {
        method = 'put'
      }

      this.$request[method]('/' + this.MODEL + '/' + id, this.formData).then(r => {
        this.$custom.tip('保存成功')
        this.formVisible = false
        this.loadData()
      }).finally(r => {
        this.submitLoading = false
      })
    },
    loadData () {
      this.searchLoading = true
      this.$request.get('/' + this.MODEL, { params: this.search }).then(r => {
        this.entries = r.data
        this.total = r.total
        this.page = r.page
      }).finally(r => {
        this.searchLoading = false
      })
    },
    handleSearch () {
      this.search.page = 1
      this.loadData()
    },
    handleEdit (index, row) {
      this.formVisible = true
      this.customDataLoading = true
      this.$request.get('/' + this.MODEL + '/' + row.id).then(r => {
        this.formData = r
      }).finally(() => {
        this.customDataLoading = false
      })
    },
    handleRemove (index, row) {
      this.restRemove('customer', row.id)
    },
    restRemove (entry, id) {
      let loading = this.$loading()
      this.$request.delete('/' + this.MODEL + '/' + id).then(r => {
        this.$custom.tip('删除成功')
      }).finally(r => {
        loading.close()
      })
    }
  }
})

const app = new Vue({
  router,
  store
}).$mount('#app')
