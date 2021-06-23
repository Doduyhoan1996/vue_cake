import Vue from 'vue'
import {
    AclInstaller,
    AclCreate,
    AclRule
} from 'vue-acl'
import router from './router'
import acl_config from './config/acl'
Vue.use(AclInstaller)

export default new AclCreate({
    initial: acl_config.PUBLIC,
    notfound: '/error-permission',
    router,
    acceptLocalRules: true,
    globalRules: {
        isPublic: new AclRule(acl_config.PUBLIC).or(acl_config.ADMIN).or(acl_config.USER).or(acl_config.HR).generate(),
        isLogged: new AclRule(acl_config.ADMIN).or(acl_config.USER).or(acl_config.HR).generate(),
        isHR: new AclRule(acl_config.ADMIN).or(acl_config.HR).generate(),
        isAdmin: new AclRule(acl_config.ADMIN).generate()
    }
})