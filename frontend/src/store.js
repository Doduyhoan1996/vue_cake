import Vue from 'vue'
import Vuex from 'vuex'
import * as mutation_types from './store/mutation_type'

//IMPORT MODULES
import setting from './store/modules/setting'

Vue.use(Vuex)

let state = {
  app_user: null,
  flag_change: false,
}

let getters = {

}

let mutations = {
  [mutation_types.APP_USER](state, data) {
    state.app_user = data
  },
  [mutation_types.FLAG_CHANGE](state, data) {
    state.flag_change = data
  },
}

let actions = {
  setAppUser({ commit }, info) {
    commit(mutation_types.APP_USER, info)
  },
  setFlagChange({ commit }, info) {
    commit(mutation_types.FLAG_CHANGE, info)
  }
}

export default new Vuex.Store({
  state,
  getters,
  mutations,
  actions,
  modules: {
    setting
  }
})
