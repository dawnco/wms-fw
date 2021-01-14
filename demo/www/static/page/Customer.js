const CustomerPage = {
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
            <p>{{scope.row.name}}</p>
            <p>{{scope.row.company}}</p>
            <p>{{scope.row.area}}</p>
          </template>
        </el-table-column>
        <el-table-column
          prop="mobile"
          label="电话"
          width="180">
          <template slot-scope="scope">
            <p>{{scope.row.mobile}}</p>
            <p>{{scope.row.mobile2}}</p>
          </template>
        </el-table-column>
        <el-table-column
          prop="note"
          label="备注">
        </el-table-column>
        <el-table-column
          label="管理员"
          width="180">
          <template slot-scope="scope">
            <custom-admin-name :id="scope.row.adminId"></custom-admin-name>
            <p>{{scope.row.created}}</p>
          </template>
        </el-table-column>
        <el-table-column
          label=""
          width="80">
          <template slot-scope="scope">
            <p>
              <el-button type="text" size="small" icon="el-icon-s-promotion" @click.native="handleFollow(scope.$index, scope.row)">跟进</el-button>
            </p>
            <p>
              <el-button type="text" size="small" icon="el-icon-circle-plus" @click.native="handleAddOrder(scope.$index, scope.row)">下单</el-button>
            </p>
          </template>
        </el-table-column>
        <el-table-column
          prop="created"
          label="操作"
          width="80">
          <template slot-scope="scope">
            <p>
              <el-button type="text" size="small" icon="el-icon-edit" @click.native="handleEdit(scope.$index, scope.row)">编辑</el-button>
            </p>
            <p>
              <el-popconfirm
                title="确定删除？"
                @confirm="handleRemove(scope.$index, scope.row)">
                <el-button slot="reference" type="text" size="small" icon="el-icon-delete">删除</el-button>
              </el-popconfirm>
            </p>

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

    <custom-dialog v-model="formVisible" :title="formTitle" @ok="handleSave" :loading="submitLoading" :data-loading="customDataLoading">
      <el-form :model="formData" :label-width="webConfig.labelWidth">
        <el-form-item label="ID" v-if="formData.id">
          {{formData.id}}
        </el-form-item>
        <el-form-item label="分组">
          <el-select v-model="formData.group">
            <el-option label="普通" value="1"></el-option>
            <el-option label="意向" value="2"></el-option>
            <el-option label="重要" value="3"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item label="企业">
          <el-input v-model="formData.company">
          </el-input>
        </el-form-item>
        <el-form-item label="联系人">
          <el-input v-model="formData.name">
          </el-input>
        </el-form-item>
        <el-form-item label="电话">
          <el-input v-model="formData.mobile">
          </el-input>
        </el-form-item>
        <el-form-item label="电话2">
          <el-input v-model="formData.mobile2">
          </el-input>
        </el-form-item>
        <el-form-item label="所在城市">
          <el-input v-model="formData.area">
          </el-input>
        </el-form-item>
        <el-form-item label="备注">
          <el-input v-model="formData.note">
          </el-input>
        </el-form-item>
      </el-form>
    </custom-dialog>

    <custom-follow-dialog v-model="followVisible" :customer-id="customerId"></custom-follow-dialog>
    <custom-order-dialog v-model="orderVisible" :customer-id="customerId"></custom-order-dialog>
  </div>`,
  data () {
    return {
      MODEL: 'customer',
      entries: [],
      followVisible: false,
      customerId: 0,
      orderVisible: false
    }
  },
  methods: {
    handleFollow (index, row) {
      this.followVisible = true
      this.customerId = row.id
    },
    handleAddOrder (index, row) {
      this.orderVisible = true
      this.customerId = row.id
    }
  },
  created () {
    this.loadData()
  }
}