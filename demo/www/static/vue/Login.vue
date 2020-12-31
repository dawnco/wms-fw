<template>
  <div style="width: 300px;margin: 100px auto 0 auto;border: 1px solid #CCCCCC;border-radius: 10px;padding: 10px">
    <el-form
      label-position="top"
      :model="fromData"
      size="default">
      <el-form-item prop="username">
        <el-input
          type="text"
          v-model="fromData.username"
          @keyup.enter.native="submit"
          placeholder="用户名">
          <i slot="prepend" class="el-icon-user"></i>
        </el-input>
      </el-form-item>
      <el-form-item prop="password">
        <el-input
          type="password"
          v-model="fromData.password"
          @keyup.enter.native="submit"
          placeholder="密码">
          <i slot="prepend" class="el-icon-lock"></i>
        </el-input>
      </el-form-item>
      <el-button
        style="width: 100%"
        size="default"
        @click="submit"
        type="primary"
        :loading="loading"
        class="button-login">
        登录
      </el-button>
    </el-form>
  </div>
</template>
<script>
export default {
  data: () => {
    return {
      fromData: {},
      loading: false
    }
  },
  methods: {
    submit () {
      this.loading = true
      console.log(this.fromData)
      this.$request.post('/login', this.fromData).then(r => {
        this.$store.commit('logged', r.token)
        this.$tip.success('登录成功')
        this.$router.push({ path: '/home' })
      }).finally(r => {
        this.loading = false
      })
    }
  }
}
</script>
