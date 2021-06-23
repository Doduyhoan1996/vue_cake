import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import acl from './acl'
import './registerServiceWorker'

Vue.config.productionTip = false

// Mixin
import mixins from './mixin'
Vue.mixin(mixins)

// Localstorage
import LocalStore from 'vue-ls'
Vue.use(LocalStore, {
  namespace: 'pcm__',
  name: 'ls',
  storage: 'local'
})

// Load ElementUI
import ElementUI from 'element-ui';
import 'element-ui/lib/theme-chalk/index.css';
import locale from 'element-ui/lib/locale/lang/vi'
Vue.use(ElementUI, { locale })

// Axios
import Axios from 'axios';
let axiosCommon = Axios.create({
  baseURL: process.env.VUE_APP_BASE_API_URL,
  headers: {
    Authorization: Vue.ls.get('auth_token')
  }
})
Vue.prototype.$axios = axiosCommon

// Vee Validation
import VeeValidate, { Validator } from 'vee-validate'
import LocaleVee from 'vee-validate/dist/locale/vi'
Validator.localize('vi', LocaleVee)
Vue.use(VeeValidate, {
  events: ''
})

require('./filter')

new Vue({
  router,
  store,
  acl,
  render: h => h(App),
  mounted() {
    this.$router.push({name: 'login'}) // added this for abstract mode
  }
}).$mount('#app')
