import Vue from 'vue'
import Vuex from 'vuex'
import * as mutation_types from './store/mutation_type'

Vue.use(Vuex)

//IMPORT MODULES
import setting from './store/modules/setting'

export default new Vuex.Store({
  state: {
    app_user: null,
    app_state: '',
  }, 
  getters: {
    CHANGE_PROFILE: state => {
        return state.app_state;
    }
  },
  mutations: {
    [mutation_types.APP_USER](state, data) {
      state.app_user = data
    },
    SET_CHANGE_PROFILE: (state, payload) => {
        state.app_state = payload;
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
