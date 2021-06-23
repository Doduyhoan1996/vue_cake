<template>
    <div id="pcm-login-view">
        <div class="container">

            <!-- Outer Row -->
            <div class="row justify-content-center">

                <div class="col-xl-10 col-lg-12 col-md-9">

                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div
                                    class="col-lg-6 d-flex bg-gray-100 flex-column align-items-center justify-content-center">
                                    <img src="../../assets/logo-sm.png" class="img-fluid" alt="">
                                    <br>
                                    <h3><span class="text-warning">PA</span>COM</h3>
                                    <h4>System Management</h4>
                                </div>
                                <div class="col-lg-6" v-loading="loadingState.login">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Đăng nhập</h1>
                                        </div>
                                        <form class="user" @submit.prevent="login" autocomplete="off">
                                            <div class="form-group">
                                                <input type="email" 
                                                    v-model="form.email" 
                                                    v-validate="'required|email'"
                                                    data-vv-name="email" data-vv-as="Email"
                                                    class="form-control form-control-user" 
                                                    :class="{'is-invalid': errors.has('email')}" 
                                                    placeholder="Nhập email"
                                                    autofocus
                                                    >
                                            </div>
                                            <div class="form-group">
                                                <input type="password" v-model="form.password" v-validate="'required'"
                                                    data-vv-name="password" data-vv-as="Mật khẩu"
                                                    class="form-control form-control-user" :class="{'is-invalid': errors.has('password')}" placeholder="Nhập mật khẩu">
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" v-model="form.remember" class="custom-control-input" id="customCheck">
                                                    <label class="custom-control-label" for="customCheck">
                                                        Tự động đăng nhập
                                                    </label>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Đăng nhập
                                            </button>
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            <button type="button" class="btn btn-link">Quên mật khẩu ?</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</template>

<script>
    import acl_config from '@/config/acl'
    import API_USER from '@/utils/api/user'
    import USER from '@/model/user'
    export default {
        name: 'pcm-login-view',
        data() {
            return {
                loadingState: {
                    login: false
                },
                form: {
                    email: '',
                    password: '',
                    remember: false
                }
            }
        },
        created() {
            if (this.$ls.get('auth_token')) {
                this.checkSession()
            }
        },
        methods: {
            login() {
                let self = this
                self.$validator.validateAll().then((result) => {
                    if (result) {
                        self.loadingState.login = true;
                        API_USER.auth(self.$axios, self.form, function (data) {
                            self.loadingState.login = false
                            if (data.status) {
                                self.showMessage('success', 'Chào mừng quay trở lại')
                                //Sync Setting
                                self.$store.dispatch('syncSettings', {
                                    isSync: false,
                                    data: data.data.setting
                                })
                                delete data.data.setting
                                self.$axios.defaults.headers.Authorization = data.data.Authorization
                                self.$store.dispatch('setAppUser', data.data)
                                if (self.form.remember) {
                                    self.$ls.set('auth_token', data.data.Authorization)
                                }
                                switch (data.data.user.role) {
                                    case USER.role.ADMIN:
                                        self.$acl.change(acl_config.ADMIN)
                                        break
                                    case USER.role.HR:
                                        self.$acl.change(acl_config.HR)
                                        break
                                    default:
                                        self.$acl.change(acl_config.USER)
                                }
                                self.$router.push({name: 'dashboard'})
                            } else {
                                //Show error
                                self.showErrorNotify(null, 'Đăng nhập thất bại')
                            }
                        }, function (error) {
                            self.loadingState.login = false
                        })
                    }
                })
            },
            checkSession() {
                let self = this
                self.loadingState.login = true;
                API_USER.checkSession(self.$axios, function (data) {
                    self.loadingState.login = false
                    if (data.status) {
                        self.showMessage('success', 'Chào mừng quay trở lại')
                        //Sync Setting
                        self.$store.dispatch('syncSettings', {
                            isSync: false,
                            data: data.data.setting
                        })
                        delete data.data.setting
                        self.$axios.defaults.headers.Authorization = data.data.Authorization
                        self.$store.dispatch('setAppUser', data.data)
                        self.$ls.set('auth_token', data.data.Authorization)
                        switch (data.data.user.role) {
                            case USER.role.ADMIN:
                                self.$acl.change(acl_config.ADMIN)
                                break
                            case USER.role.HR:
                                self.$acl.change(acl_config.HR)
                                break
                            default:
                                self.$acl.change(acl_config.USER)
                        }
                        self.$router.push({name: 'dashboard'})
                    } else {
                        //Show error
                        self.showErrorNotify(null, 'Đăng nhập thất bại')
                        self.$ls.set('auth_token', null)
                    }
                }, function (error) {
                    self.loadingState.login = false
                })
            }
        },
        mounted() {
            let elBody = document.body
            elBody.classList.add('bg-gradient-primary')
        },
        destroyed() {
            let elBody = document.body
            elBody.classList.remove('bg-gradient-primary')
        }
    }
</script>