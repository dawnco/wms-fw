const store = new Vuex.Store({
  state: {
    count: 0,
  },
  mutations: {
    increment (state) {
      state.count++
    },
    logged(state, token){
        window.localStorage.setItem('X-Token', token)
    },
    logout(state){
      window.localStorage.removeItem('X-Token')
    }
  },
  actions:{

  }
})
