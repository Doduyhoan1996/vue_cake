<template>
    <div id="pcm-layout-view">
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion " :class="{'toggled': screen.width < 768 }" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
                    <div class="sidebar-brand-icon" style="width: 40px">
                        <!-- <i class="fas fa-laugh-wink"></i> -->
                        <img width="50px" src="../assets/logo-white.png" alt="">
                    </div>
                    <div class="sidebar-brand-text mx-3">PACOM <sup>system</sup></div>
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Nav Item - Dashboard -->
                <li class="nav-item" :class="{active: _.indexOf(activeMenu, 'dashboard') > -1}">
                    <button class="nav-link btn btn-link" @click="$router.push({name: 'dashboard'})">
                        <i class="fas fa-fw fa-tachometer-alt"></i>
                        <span>Tổng quan</span>
                    </button>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Addons
                </div>

                <li class="nav-item" :class="{active: _.indexOf(activeMenu, 'timesheet') > -1}" >
                    <button class="nav-link btn btn-link" @click="$router.push({name: 'timesheet'})">
                        <i class="fas fa-fw fa-chart-area"></i>
                        <span>Time Sheet</span>
                    </button>
                </li>

                <li class="nav-item" :class="{active: _.indexOf(activeMenu, 'users') > -1}" v-if="$acl.check('isAdmin')">
                    <button class="nav-link btn btn-link" @click="$router.push({name: 'users'})">
                        <i class="fas fa-fw fa-users"></i>
                        <span>Nhân sự</span>
                    </button>
                </li>

                <!-- Divider -->
                <hr class="sidebar-divider">

                <!-- Heading -->
                <div class="sidebar-heading">
                    Phát triển
                </div>

                <!-- Nav Item - Pages Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                        aria-expanded="true" aria-controls="collapseTwo">
                        <i class="fas fa-fw fa-cog"></i>
                        <span>Components</span>
                    </a>
                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Custom Components:</h6>
                            <a class="collapse-item">Buttons</a>
                            <a class="collapse-item">Cards</a>
                        </div>
                    </div>
                </li>

                <!-- Nav Item - Utilities Collapse Menu -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                        aria-expanded="true" aria-controls="collapseUtilities">
                        <i class="fas fa-fw fa-wrench"></i>
                        <span>Utilities</span>
                    </a>
                    <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                        data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Custom Utilities:</h6>
                            <a class="collapse-item" >Colors</a>
                        </div>
                    </div>
                </li>

            </ul>
            <!-- End of Sidebar -->

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    <!-- Topbar -->
                    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow ">

                        <!-- Sidebar Toggle (Topbar) -->
                        <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                            <i class="fa fa-bars"></i>
                        </button>

                        <!-- Topbar Search -->
                        <form
                            class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                                <input type="text" class="form-control bg-light border-0 small"
                                    placeholder="Tìm kiếm..." aria-label="Search" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <button class="btn btn-primary btn-sm" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <!-- Topbar Navbar -->
                        <ul class="navbar-nav ml-auto">

                            <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                            <li class="nav-item dropdown no-arrow d-sm-none">
                                <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-search fa-fw"></i>
                                </a>
                                <!-- Dropdown - Messages -->
                                <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                    aria-labelledby="searchDropdown">
                                    <form class="form-inline mr-auto w-100 navbar-search">
                                        <div class="input-group">
                                            <input type="text" class="form-control bg-light border-0 small"
                                                placeholder="Search for..." aria-label="Search"
                                                aria-describedby="basic-addon2">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="button">
                                                    <i class="fas fa-search fa-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>

                            <!-- Nav Item - Alerts -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <!-- Counter - Alerts -->
                                    <span class="badge badge-danger badge-counter"></span>
                                </a>
                                <!-- Dropdown - Alerts -->
                               
                            </li>

                            <!-- Nav Item - Messages -->
                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-envelope fa-fw"></i>
                                    <!-- Counter - Messages -->
                                    <span class="badge badge-danger badge-counter"></span>
                                </a>
                                <!-- Dropdown - Messages -->
                                
                            </li>

                            <div class="topbar-divider d-none d-sm-block"></div>

                            <!-- Nav Item - User Information -->
                            <li class="nav-item dropdown no-arrow">
                                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ $store.state.app_user.user.full_name }}</span>
                                    <avatar :size="40" :username="$store.state.app_user.user.first_name + ' ' + $store.state.app_user.user.last_name"></avatar>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" @click="editUser($store.state.app_user.user.id)">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Settings
                                    </a>
                                    <button class="dropdown-item" @click="dialogVisible.changePassword = true">
                                        <i class="fas fa-lock fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Đổi mật khẩu
                                    </button>
                                    <div class="dropdown-divider"></div>
                                    <button class="dropdown-item" @click="logout">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Đăng xuất
                                    </button>
                                </div>
                            </li>

                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <router-view @activeMenu="toggleMenu" ></router-view>
                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Pacom System 2019</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Dialog Change Password -->
        <form role="form" @submit.prevent="submitFormChangePassword">
            <el-dialog title="Đổi mật khẩu"
                custom-class="el-dialog-body-gray" 
                :visible.sync="dialogVisible.changePassword" 
                width="500px"
            >
                <div v-loading="loadingState.changePasswordForm">
                    <div class="form-group">
                        <label class="required">Mật khẩu cũ</label>
                        <div>
                            <el-input size="small" 
                                type="password"
                                placeholder=">= 6 ký tự"
                                v-model="form.user.old_password"
                                v-validate="'required|min:6'"
                                data-vv-name="old_password" data-vv-as="Mật khẩu cũ"
                                :class="{'is-invalid': errors.has('old_password')}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required">Mật khẩu mới</label>
                        <div>
                            <el-input size="small" 
                                type="password"
                                placeholder=">= 6 ký tự"
                                v-model="form.user.password"
                                v-validate="'required|min:6'"
                                data-vv-name="password" data-vv-as="Mật khẩu mới"
                                :class="{'is-invalid': errors.has('password')}"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="required">Nhập lại Mật khẩu</label>
                        <div>
                            <el-input size="small" 
                                type="password"
                                placeholder=">= 6 ký tự"
                                v-model="form.user.re_password"
                                v-validate="'required|min:6'"
                                data-vv-name="re_password" data-vv-as="Nhập lại Mật khẩu"
                                :class="{'is-invalid': errors.has('re_password')}"
                            />
                        </div>
                    </div>
                </div>
                <div slot="footer" class="dialog-footer">
                    <button type="submit" class="btn btn-sm btn-primary btn-icon-split" :disabled="loadingState.changePasswordForm">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">Lưu</span>
                    </button>
                </div>
            </el-dialog>
        </form>

        <!-- Dialog - User -->
        <form role="form" @submit.prevent="submitEditProfile">
            <el-dialog title="Thông tin tài khoản"
                custom-class="el-dialog-body-gray" 
                :visible.sync="dialogVisible.profile" 
                width="500px"
            >
                <div v-loading="loadingState.userForm">

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Họ</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.profile.first_name"
                                    v-validate="'required'"
                                    data-vv-name="first_name" data-vv-as="Họ"
                                    :class="{'is-invalid': errors.has('first_name')}"
                                />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Tên</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.profile.last_name"
                                    v-validate="'required'"
                                    data-vv-name="last_name" data-vv-as="Tên"
                                    :class="{'is-invalid': errors.has('last_name')}"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Giới tính</label>
                            <div>
                                <el-radio v-model="form.profile.gender" :label="1">Nam</el-radio>
                                <el-radio v-model="form.profile.gender" :label="2">Nữ</el-radio>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Ngày sinh</label>
                            <div>
                                <el-date-picker 
                                    v-model="form.profile.birthday"
                                    v-validate="'required'" 
                                    data-vv-name="birthday"
                                    data-vv-as="Ngày sinh"  
                                    type="date"
                                    size="small" 
                                    placeholder="Lựa chọn"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :class="{'is-invalid': errors.has('birthday')}"
                                    :picker-options="pickerOptions"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label>Điện thoại</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.profile.phone"
                                />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Nơi ở hiện nay</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.profile.address"
                                />
                            </div>
                        </div>
                    </div>

                </div>
                <div slot="footer" class="dialog-footer">  
                    <button type="submit" class="btn btn-sm btn-primary btn-icon-split" :disabled="loadingState.userForm">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">Lưu</span>
                    </button>
                </div>
            </el-dialog>
        </form>

    </div>
</template>

<script>
import _ from 'lodash'
import acl_config from '@/config/acl'
import API_SETTING from '@/utils/api/setting'
import API_USER from '@/utils/api/user'
import Avatar from 'vue-avatar'
import moment from 'moment'

export default {
    name: 'pcm-layout-view',
    components: {
        Avatar
    },
    data() {
        return {
            pickerOptions: {
                disabledDate(time) {
                return time.getTime() > Date.now();
                }
            },
            activeMenu: [],
            loadingState: {
                changePasswordForm: false,
                userForm: false
            },
            dialogVisible: {
                changePassword: false,
                profile: false
            },
            form: {
                user: {
                    old_password: '',
                    password: '',
                    re_password: ''
                }, 
                profile: {
                    id: '',
                    first_name: '',
                    last_name: '',
                    birthday: '',
                    gender: '',
                    phone: '',
                    address: '',
                }
            }
        }
    },
    computed: {
        _() {
            return _;
        },
        screen(){
            return screen;
        }
    },
    created() {
    },
    methods: {
        toggleMenu(menu) {
            this.activeMenu = _.split(menu, '.');
        },
        logout() {
            let self = this
            this.$confirm('Bạn muốn đăng xuất?', 'Thông báo', {
               confirmButtonText: 'OK',
               cancelButtonText: 'Cancel',
               type: 'info'
            }).then(() => {
               self.$ls.set('auth_token', null)
               self.$acl.change(acl_config.PUBLIC)
               self.$router.push({name: 'login'})
            }).catch(() => {
               
            });
        },
        submitFormChangePassword() {
            let self = this
            self.$validator.validateAll().then((result) => {
                if (result) {
                    if (self.form.user.password != self.form.user.re_password) {
                        self.showMessage('error', 'Mật khẩu không trùng nhau !')
                    } else {
                        self.loadingState.changePasswordForm = true
                        API_USER.changePassword(self.$axios, self.form.user, function(data){
                            self.loadingState.changePasswordForm = false
                            if (data.status) {
                                self.showMessage('success', 'Đổi mật khẩu thành công !')
                                self.dialogVisible.changePassword = false
                                self.form.user = {
                                    old_password: '',
                                    password: '',
                                    re_password: ''
                                }
                            } else {
                                self.showMessage('error', 'Đổi mật khẩu không thành công !')
                            }
                        }, function(error){
                            self.loadingState.changePasswordForm = false
                        })
                    }
                }
            })
        },
        syncSettings() {
            let self = this;
            self.$store.dispatch('syncSettings', {
                isSync: true,
                data: []
            });
            API_SETTING.getList(self.$axios, function (data) {
                if (data.status) {
                    self.$store.dispatch('syncSettings', {
                        isSync: false,
                        data: data.data
                    })
                } else {
                    self.$store.dispatch('syncSettings', {
                        isSync: false,
                        data: []
                    })
                }
            }, function (error) {
                self.$store.dispatch('syncSettings', {
                    isSync: false,
                    data: []
                });
            })
        },
        editUser(id) {
            let self = this
            
            self.loadingState.userForm = true
            API_USER.get(self.$axios, {id: id}, function(data){
                self.loadingState.userForm = false
                if (data.status) {
                    self.form.profile.id = data.data.id;
                    self.form.profile.first_name = data.data.first_name;
                    self.form.profile.last_name = data.data.last_name;
                    self.form.profile.gender = data.data.gender;
                    self.form.profile.phone = data.data.phone;
                    self.form.profile.address = data.data.address;
                    self.form.profile.birthday = moment(data.data.birthday).format('YYYY-MM-DD');

                    self.dialogVisible.profile = true;
                } else {
                    self.showMessage('error', 'Không tìm thấy !')
                }
            }, function (error) {
                console.log(error);
            });
        },
        submitEditProfile() {
            let self = this
            self.$validator.validateAll().then((result) => {
                if (result) {
                    self.loadingState.userForm = true
                    API_USER.update(self.$axios, self.form.profile, function (data) {
                        self.loadingState.userForm = false
                        if (data.status) {
                            self.$store.state.app_user.user.full_name = self.form.profile.first_name + ' ' +self.form.profile.last_name;
                            self.showMessage('success', 'Chỉnh sửa profile thành công!')
                            self.dialogVisible.profile = false

                            if(self.$route.name == 'users') {
                                self.$store.dispatch('setFlagChange', true);
                            } else {
                                self.$store.dispatch('setFlagChange', false);
                            }

                        } else {
                            if (data.error_code == 103) {
                                self.showInValidMessage(data.data)
                            }
                        }
                    }, function (error) {
                        console.log(error);
                    });
                }
            })
        },
    }
}
</script>
