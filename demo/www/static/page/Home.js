const Home = {
  template: `<div>
<router-link to="/foo">Go to Foo</router-link>
  <router-link to="/bar">Go to Bar</router-link>
    <el-button @click="loadData">Button</el-button>
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
  <component-demo name="组件名称">
    这是slot
  </component-demo></div>`,
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

      this.$request('/customer')

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
const Foo = { template: `<div>foo</div>` }
