<template>
  <div class="layout">
    <!--
    https://element.eleme.cn/#/zh-CN/component/icon
      -->
    <div class="layout-left">
      <el-menu
        background-color="#545c64"
        text-color="#fff"
        active-text-color="#ffd04b"
        default-active="/home"
        @select="handleSelect"
        @open="handleOpen"
        @close="handleClose">
        <el-menu-item index="home">
          <i class="el-icon-s-home"></i>
          <span slot="title">首页</span>
        </el-menu-item>
        <el-menu-item index="/Customer">
          <i class="el-icon-bank-card"></i>
          <span slot="title">客户</span>
        </el-menu-item>
        <el-menu-item index="/CustomerOrder">
          <i class=el-icon-shopping-cart-1></i>
          <span slot="title">订单</span>
        </el-menu-item>
        <el-menu-item index="/Admin">
          <i class="el-icon-user-solid"></i>
          <span slot="title">管理员</span>
        </el-menu-item>
        <el-menu-item index="logout">
          <i class="el-icon-download"></i>
          <span slot="title">退出</span>
        </el-menu-item>
      </el-menu>
    </div>
    <div class="layout-right">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
export default {
  title: 'Layout',
  data () {
    return {
      formData: {}
    }
  },
  methods: {
    logout () {
      this.$custom.confirm('确认退出').then(() => {
        this.$request.get('/logout').then(r => {
          this.$router.push({ path: '/login' })
        })
      })
    },
    handleSelect (key, keyPath) {

      if (key == 'logout') {
        return this.logout()
      }

      if (this.$router.history.current.fullPath === key) {
        return
      }

      if (key == 'home') {
        this.$router.push('/')
      } else {
        this.$router.push(key)
      }
    },
    handleOpen (key, keyPath) {
      console.log(key, keyPath)
    },
    handleClose (key, keyPath) {
      console.log(key, keyPath)
    }
  },
  created () {

  }
}
</script>
