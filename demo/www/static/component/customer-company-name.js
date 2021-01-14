Vue.component('custom-customer-company-name', {
  template: `<div>
    <i v-show="loading" class="el-icon-loading"></i>
    {{name}}
  </div>`,
  props: {
    id: {
      type: [String, Number],
      default: 0
    }
  },
  data () {
    return {
      name: '',
      loading: true
    }
  },
  methods: {
    remoteGetName (id) {
      this.loading = true
      this.$request.post('/data/name', { type: 'customerCompany', id: id }).then(r => {
        this.name = r
      }).finally(r => {
        this.loading = false
      })
    }
  },
  created () {
    this.remoteGetName(this.id)
  },
  watch: {
    id (val, old) {
      this.remoteGetName(val)
    }
  }
})