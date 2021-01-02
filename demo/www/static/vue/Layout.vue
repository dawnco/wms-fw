<template>
  <div class="layout">
    <div class="layout-left">
        休息下
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
  methods: {},
  created () {

  }
}
</script>
