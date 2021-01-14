Vue.component('custom-follow-dialog', {
  template: `<div>
    <custom-dialog v-model="model" :loading="loading" :title="formTitle" @ok="handleFollowSave" :data-loading="dataLoading">
      <el-form :model="formData" :label-width="webConfig.labelWidth">
        <el-form-item label="ID" v-if="formData.id">
          {{formData.id}}
        </el-form-item>
        <el-form-item label="内容">
          <el-input v-model="formData.note" @keyup.enter.native="handleFollowSave">
          </el-input>
        </el-form-item>
      </el-form>
      <div>
        <el-table
          :data="entries"
          border
          stripe
          height="400"
        >
          <el-table-column
            prop="id"
            label="ID"
            width="100">
          </el-table-column>
          <el-table-column
            prop="note"
            label="内容">
          </el-table-column>
          <el-table-column
            label="管理员"
            width="90">
            <template slot-scope="scope">
              <custom-admin-name :id="scope.row.adminId"></custom-admin-name>
            </template>
          </el-table-column>
          <el-table-column
            prop="created"
            label="时间"
            width="180">
          </el-table-column>
        </el-table>
      </div>
    </custom-dialog>
  </div>`,
  props: {
    customerId: {
      type: [Number, String],
      default: ''
    },
    value: {
      type: [Boolean],
      default: false
    }
  },
  data () {
    return {
      model: false,
      dataLoading: false,
      formData: {},
      formTitle: '',
      loading: false,
      visible: false,
      entries: []
    }
  },
  methods: {
    async handleFollowSave () {
      let customerId = this.customerId
      let { note } = this.formData
      if (!note) {
        return this.$custom.tip('内容不能为空', 'error')
      }
      this.dataLoading = true

      try {
        let r = await this.$request.post('customer/follow', { note, customerId })
        await this.loadFollowData(customerId)
        this.formData.note = ''
        this.$custom.success('跟进成功')
      } finally {
        this.dataLoading = false
      }

    },
    async loadFollowData (customerId) {
      this.entries = []
      this.dataLoading = true
      try {
        let r = await this.$request.get('customer/follow?customerId=' + customerId)
        this.entries = r.data
      } finally {
        this.dataLoading = false
      }
    },
    async getCustomer (customerId) {
      this.$request.get('/customer/' + customerId).then(r => {
        this.formTitle = '跟进 ' + r.name + ' (' + r.company + ':' + r.id + ')'
      })
    }
  },
  created () {

  },
  watch: {
    value (val, old) {
      this.model = val
    },
    model (val, old) {
      this.$emit('input', val)
    },
    customerId (val, old) {
      console.log(val)
      if (val) {
        this.getCustomer(val)
        this.loadFollowData(val)
      }
    }
  }
})