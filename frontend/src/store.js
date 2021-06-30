import Vue from 'vue'
import Vuex from 'vuex'
import * as mutation_types from './store/mutation_type'

Vue.use(Vuex)

//IMPORT MODULES
import setting from './store/modules/setting'

export default new Vuex.Store({
  state: {
    app_user: null
  },
  mutations: {
    [mutation_types.APP_USER](state, data) {
      state.app_user = data
    }
  },
  actions: {
    setAppUser({commit}, info) {
      commit(mutation_types.APP_USER, info)
    }
  },
  modules: {
    setting
  }
})
