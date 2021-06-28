import Vue from 'vue'
import Vuex from 'vuex'
import * as mutation_types from './store/mutation_type'

//IMPORT MODULES
import setting from './store/modules/setting'

Vue.use(Vuex)

let app_state = {
  app_user: null,
  app_state: '',
}

let getters = {
  CHANGE_PROFILE: state => {
    return state.app_state;
  }
}

let mutations = {
  [mutation_types.APP_USER](state, data) {
    state.app_user = data
  },
  SET_CHANGE_PROFILE: (state, payload) => {
    state.app_state = payload;
  }
}

let actions = {
  setAppUser({ commit }, info) {
    commit(mutation_types.APP_USER, info)
  }
}

export default new Vuex.Store({
  app_state,
  getters,
  mutations,
  actions,
  modules: {
    setting
  }
})
