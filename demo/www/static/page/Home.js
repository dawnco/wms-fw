const HomePage = {
  template: `<div>
    <custom-dialog :value="visible"></custom-dialog>
    <el-button @click="visible=true">Button</el-button>
    <el-button @click="handleInc">{{increment}}</el-button>
    <el-table
      v-loading="loading"
      :data="tableData"
      style="width: 100%">
      <el-table-column
        prop="name"
        label="日期"
        width="180">
      </el-table-column>
      <el-table-column
        prop="name"
        label="日期"
        width="180">
        <template slot-scope="scope">
          xxx {{scope.row.name}}
        </template>
      </el-table-column>
    </el-table>
    <div v-show="showPage2">
      page2
    </div>
  </div>`,
  created () {
    this.loadData()
  },
  data: function () {
    return {
      visible: false,
      showPage2: false,
      tableData: [],
      loading: false
    }
  },
  methods: {
    loadData () {
      this.loading = true

      // this.$request('/customer')

    },
    handleInc () {
      this.$store.commit('increment')
    }
  },
  computed: {
    increment () {
      return this.$store.state.count
    }
  }
}