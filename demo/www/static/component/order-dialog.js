Vue.component('custom-order-dialog', {
  template: `<div>
    <custom-dialog v-model="model" :loading="loading" :title="formTitle" @ok="save" :data-loading="dataLoading">
      <el-form :model="formData" :label-width="webConfig.labelWidth">
        <el-form-item label="ID" v-if="formData.id">
          {{formData.id}}
        </el-form-item>
        <el-row>
          <el-col :span="18">
            <el-form-item label="产品">
              <el-input v-model="formData.productName"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="数量">
              <el-input v-model="formData.productQuantity"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="6">
            <el-form-item label="总价">
              <el-input v-model="formData.price"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="成本1">
              <el-input v-model="formData.cost1"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="成本2">
              <el-input v-model="formData.cost2"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="成本3">
              <el-input v-model="formData.cost3"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="6">
            <el-form-item label="收货人">
              <el-input v-model="formData.expressConsignee"></el-input>
            </el-form-item>
          </el-col>
          <el-col :span="6">
            <el-form-item label="发货方式">
              <el-select v-model="formData.expressCode">
                <el-option value="STO" label="申通"></el-option>
                <el-option value="SF" label="顺丰"></el-option>
              </el-select>
            </el-form-item>
          </el-col>
          <el-col :span="12">
            <el-form-item label="单号">
              <el-input v-model="formData.expressNumber"></el-input>
            </el-form-item>
          </el-col>
        </el-row>
        <el-row>
          <el-col :span="8">
            <el-form-item label="发货时间">
              <el-date-picker
                v-model="formData.expressDate"
                type="date"
                value-format="yyyy-MM-dd"
                placeholder="选择日期">
              </el-date-picker>
            </el-form-item>
          </el-col>
          <el-col :span="8">
            <el-form-item label="收货时间">
              <el-date-picker
                v-model="formData.consigneeDate"
                type="date"
                value-format="yyyy-MM-dd"
                placeholder="选择日期">
              </el-date-picker>
            </el-form-item>
          </el-col>
        </el-row>
        <el-form-item label="备注">
          <el-input v-model="formData.note"></el-input>
        </el-form-item>
      </el-form>
    </custom-dialog>
  </div>`,
  props: {
    orderId: {
      type: [Number, String],
      default: ''
    },
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
    async save () {
      let customerId = this.customerId
      try {
        this.formData.customerId = customerId

        let { id } = this.formData

        if (id) {
          await this.$request.put('customer/order/' + id, this.formData)
        } else {
          await this.$request.post('customer/order', this.formData)
        }
        this.$custom.success('下单成功')
        this.$emit('ok')
        this.model = false
      } finally {
        this.dataLoading = false
      }
    },
    loadData (id) {
      if (!id) {
        return
      }
      this.dataLoading = true
      this.$request.get('customer/order/' + id).then(r => {
        this.formData = r
      }).finally(r => {
        this.dataLoading = false
      })
    },
    async getCustomer (customerId) {
      this.$request.get('/customer/' + customerId).then(r => {
        this.formTitle = '订单 ' + r.name + ' (' + r.company + ':' + r.id + ')'
      })
    }
  },
  created () {
    this.loadData(this.orderId)
  },
  watch: {
    value (val, old) {
      this.model = val
    },
    model (val, old) {
      this.$emit('input', val)
    },
    orderId (val, old) {
      this.loadData(val)
    },
    customerId (val, old) {
      this.getCustomer(val)
    }
  }
})