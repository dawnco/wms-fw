
const store = new Vuex.Store({
  state: {
    count: 0
  },
  mutations: {
    increment (state) {
      state.count++
    }
  }
})

Vue.component('component-demo', {
  props: [
    'name'
  ],
  created () {
    this.$request.get('/')
  },
  data () {
    return {
      desc: '这是一个组件的属性'
    }
  },
  template: `<div>
<slot></slot>
<h3>{{name}}</h3>
<div>{{desc}}</div>
</div>`
})

const app = new Vue({
  router
}).$mount('#app')

/*
new Vue({
  el: '#app',
  store: store,
  data: function () {
    return {
      visible: false,
      showPage2: false,
      tableData: [],
      loading: false
    }
  },
  created () {
    this.loadData()
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
})

*/
