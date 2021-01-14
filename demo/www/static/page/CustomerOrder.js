const CustomerOrderPage = {
  template: `<div class="container">
    <div class="search">
      <el-form :inline="true" :model="search" class="demo-form-inline">
        <el-form-item label="关键词">
          <el-input v-model="search.keyword" placeholder="" clearable></el-input>
        </el-form-item>
        <el-form-item label="分组">
          <el-select v-model="search.group" placeholder="" clearable>
            <el-option label="普通" value="1"></el-option>
            <el-option label="意向" value="2"></el-option>
            <el-option label="重要" value="3"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="handleSearch" icon="el-icon-search" :loading="searchLoading">查询</el-button>
          <el-button type="primary" @click="handleShowAdd" icon="el-icon-plus">新增</el-button>
        </el-form-item>
      </el-form>
    </div>
    <div class="body">
      <el-table
        :data="entries"
        border
        stripe
        v-loading="searchLoading"
        key="id"
        max-height="650"
      >
        <el-table-column
          prop="id"
          label="ID"
          width="100">
        </el-table-column>
        <el-table-column
          prop="company"
          label="企业"
          width="180">
          <template slot-scope="scope">
            <custom-customer-company-name :id="scope.row.customerId"></custom-customer-company-name>
          </template>
        </el-table-column>
        <el-table-column
          prop="name"
          label="联系人"
          width="180">
          <template slot-scope="scope">
            <custom-customer-name :id="scope.row.customerId"></custom-customer-name>
          </template>
        </el-table-column>
        <el-table-column
          prop="productName"
          label="产品"
          width="180">
        </el-table-column>
        <el-table-column
          prop="productQuantity"
          label="数量"
          width="180">
        </el-table-column>
        <el-table-column
          prop="expressConsignee"
          label="收货人"
          width="180">
        </el-table-column>
        <el-table-column
          prop="note"
          label="备注">
        </el-table-column>
        <el-table-column
          prop="created"
          label="新增时间"
          width="180">
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
          label="操作"
          width="180">
          <template slot-scope="scope">
            <el-button type="text" size="small" icon="el-icon-edit" @click.native="handleEdit(scope.$index, scope.row)">编辑</el-button>
            <el-popconfirm
              title="确定删除？"
              @confirm="handleRemove(scope.$index, scope.row)">
              <el-button slot="reference" type="text" size="small" icon="el-icon-delete">删除</el-button>
            </el-popconfirm>

          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="pagination">
      <el-pagination
        background
        :page-sizes="webConfig.pageSizes"
        :layout="webConfig.pageLayout"
        :page-size="search.pageSize"
        @size-change="pageSizeChange"
        @current-change="pageChange"
        :total="search.total">
      </el-pagination>
    </div>

    <custom-order-dialog v-model="orderVisible" :order-id="orderId" :customer-id="customerId" @ok="ok"></custom-order-dialog>

  </div>`,
  data () {
    return {
      MODEL: 'customer/order',
      entries: [],
      orderVisible: false,
      customerId: {},
      orderId: 0
    }
  },
  methods: {
    handleEdit (index, row) {
      this.orderVisible = true
      this.customerId = row.customerId
      this.orderId = row.id
    },
    ok () {
      this.loadData()
    }
  },
  created () {
    this.loadData()
  }
}