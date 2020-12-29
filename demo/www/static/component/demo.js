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
