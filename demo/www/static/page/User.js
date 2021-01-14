const UserPage = {
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
        max-height="650"
      >
        <el-table-column
          prop="id"
          label="ID"
          width="100">
        </el-table-column>
        <el-table-column
          prop="username"
          label="账号"
          width="180">
        </el-table-column>
        <el-table-column
          prop="name"
          label="姓名"
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
        :total="total">
      </el-pagination>
    </div>

    <custom-dialog v-model="formVisible" :title="formTitle" @ok="handleSave" :loading="submitLoading" :data-loading="customDataLoading">
      <el-form :model="formData" :label-width="webConfig.labelWidth">
        <el-form-item label="ID" v-if="formData.id">
          {{formData.id}}
        </el-form-item>
        <el-form-item label="账号">
          <el-input v-model="formData.username">
          </el-input>
        </el-form-item>
        <el-form-item label="姓名">
          <el-input v-model="formData.name">
          </el-input>
        </el-form-item>
        <el-form-item label="密码">
          <el-input v-model="formData.password">
          </el-input>
        </el-form-item>
      </el-form>
    </custom-dialog>
  </div>`,
  data () {
    return {
      MODEL: 'user',
      search: {
        page: 1,
        pageSize: 10
      },
      entries: [],
      total: 0,
      page: 1
    }
  },
  methods: {},
  created () {
    this.loadData()
  }
}