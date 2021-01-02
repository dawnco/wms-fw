<template>
  <el-dialog
    :title="title"
    :close-on-click-modal="quickClose"
    :close-on-press-escape="quickClose"
    :visible.sync="visible"
    :width="width"
    :append-to-body="appendToBody"
    top="10px"
  >
    <div v-loading="dataLoading">
      <slot></slot>
    </div>
    <div slot="footer" class="dialog-footer" v-if="!hideFooter">
      <el-button @click="visible = false" icon="el-icon-close" :disabled="this.dataLoading || loading">取 消</el-button>
      <el-button type="primary" icon="el-icon-check" @click="submit" :loading="loading" :disabled="this.dataLoading">确 定</el-button>
    </div>
  </el-dialog>
</template>
<script>
export default {
  props: {
    title: {
      type: String,
      default: ''
    },
    value: {
      type: [Boolean],
      required: false,
      default: false
    },
    loading: {
      type: Boolean,
      default: false
    },
    dataLoading: {
      type: Boolean,
      default: false
    },
    width: {
      type: String,
      default: '50%'
    },
    quickClose: {
      type: Boolean,
      default: false
    },
    appendToBody: {
      type: Boolean,
      default: false
    },
    hideFooter: {
      type: Boolean,
      default: false
    }
  },
  data () {
    return {
      visible: this.value
    }
  },
  methods: {
    submit () {
      this.$emit('ok')
    }
  },
  computed: {},
  watch: {
    value (val) {
      this.visible = val
    },
    visible (val) {
      this.$emit('input', val)
    }
  }
}
</script>
