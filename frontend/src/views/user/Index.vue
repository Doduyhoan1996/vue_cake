<template>
    <div id="pcm-user-view">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Quản trị Nhân sự</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4 border-left-primary">
                    <div class="card-body">
                        <h6>Điều kiện lọc</h6>
                        <div>
                            <el-tabs v-model="form.filter.type" @tab-click="loadData()">
                                <el-tab-pane name="active">
                                    <span slot="label">Hoạt động <span class="badge btn-success  badge-counter">{{users.count.count_hd}}</span></span>
                                </el-tab-pane>
                                <el-tab-pane name="1">
                                    <span slot="label">Quản trị <span class="badge btn-danger badge-counter">{{users.count.count_ad}}</span></span>
                                </el-tab-pane>
                                <el-tab-pane name="2">
                                    <span slot="label">Hành chính <span class="badge btn-primary badge-counter">{{users.count.count_hc}}</span></span>
                                </el-tab-pane>
                                <el-tab-pane name="9">
                                    <span slot="label">Thành viên <span class="badge btn-warning badge-counter">{{users.count.count_tv}}</span></span>
                                </el-tab-pane>
                                <el-tab-pane name="block">
                                    <span slot="label">Khóa <span class="badge badge-secondary badge-counter">{{users.count.count_k}}</span></span>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                        <div>
                            <el-input placeholder="Nhập tên hoặc Email" v-model="form.filter.keyword" clearable>
                                <i slot="prefix" class="el-input__icon el-icon-search"></i>
                            </el-input>
                        </div>
                    </div>
                </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="card shadow mb-4" v-loading="loadingState.userList">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h5 class="m-0 font-weight-bold text-primary">Danh sách nhân sự</h5>
                            <div class="dropdown no-arrow">
                                <el-button size="mini" type="success" @click="resetFormUser(); dialogVisible.user = true">Thêm mới</el-button>
                            </div>
                        </div>
                        <div class="card-body pt-1">
                            <el-table :data="users.list" style="width: 100%" @row-dblclick="rowDbClick">
                                <el-table-column prop="id" label="Tên">
                                    <template slot-scope="scope">
                                        <div class="d-flex">
                                            <div class="pr-2">
                                                <avatar :username="scope.row.first_name + ' ' + scope.row.last_name" :size="45"></avatar>
                                            </div>
                                            <div class="user-item-name">
                                                <span class="font-weight-bold">
                                                    {{scope.row.full_name}} 
                                                    <span v-if="scope.row.role == UserModel.role.ADMIN" class="badge badge-danger">Quản trị</span>
                                                    <span v-if="scope.row.role == UserModel.role.HR" class="badge badge-primary">Hành chính</span>
                                                </span>
                                                <br>
                                                <span>{{ scope.row.email }}</span>
                                            </div>
                                        </div>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="total_project" label="Số dự án"  width="180">
                                </el-table-column>
                                <el-table-column label="Ngày tạo" width="180">
                                    <template slot-scope="scope">
                                        <span>{{scope.row.created | formatDate}}</span>
                                        <br>
                                        <span>{{scope.row.created | formatDate('HH:mm')}}</span>
                                    </template>
                                </el-table-column>
                                <el-table-column label="#" fixed="right" width="100">
                                    <template slot-scope="scope">
                                        <el-button size="mini" @click="editUser(scope.row.id)">Sửa</el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dialog - User -->
        <form role="form" @submit.prevent="submitFormUser">
            <el-dialog title="Thông tin tài khoản"
                custom-class="el-dialog-body-gray" 
                :visible.sync="dialogVisible.user" 
                width="500px"
            >
                <div v-loading="loadingState.userForm">
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Họ</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.user.first_name"
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
                                    v-model="form.user.last_name"
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
                                <el-radio v-model="form.user.gender" :label="1">Nam</el-radio>
                                <el-radio v-model="form.user.gender" :label="2">Nữ</el-radio>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Ngày sinh</label>
                            <div>
                                <el-date-picker 
                                    v-model="form.user.birthday"
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
                                    v-model="form.user.phone"
                                />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Nơi ở hiện nay</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.user.address"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Email</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.user.email"
                                    v-validate="'required|email'"
                                    data-vv-name="email" data-vv-as="Email"
                                    :class="{'is-invalid': errors.has('email')}"
                                />
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Mật khẩu</label>
                            <div>
                                <el-input size="small"
                                    type="password" 
                                    placeholder=">= 6 ký tự"
                                    v-model="form.user.password"
                                    v-validate="'required|min:6'"
                                    data-vv-name="password" data-vv-as="Mật khẩu"
                                    :class="{'is-invalid': errors.has('password')}"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Quyền hạn</label>
                            <div>
                                <el-select size="small" v-model="form.user.role" placeholder="Lựa chọn">
                                    <el-option
                                        v-for="item in UserModel.getOptionRole()"
                                        :key="item.key"
                                        :label="item.value"
                                        :value="item.key"
                                    />
                                </el-select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Tạo dự án mới</label>
                            <div>
                                <el-radio v-model="form.user.can_create_project" :label="1">Có</el-radio>
                                <el-radio v-model="form.user.can_create_project" :label="0">Không</el-radio>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="required">Loại nhân sự</label>
                            <div>
                                <el-select size="small" v-model="form.user.type" placeholder="Lựa chọn">
                                    <el-option
                                        v-for="item in UserModel.getOptionType()"
                                        :key="item.key"
                                        :label="item.value"
                                        :value="item.key"
                                    />
                                </el-select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label class="required">Lương</label>
                            <div>
                                <el-input size="small" 
                                    v-model="form.user.salary"
                                    v-validate="'required|numeric'"
                                    data-vv-name="salary" data-vv-as="Lương"
                                    :class="{'is-invalid': errors.has('salary')}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer" class="dialog-footer d-flex justify-content-between">  
                    <div>
                        
                        <el-switch v-model="form.user.status"
                            inactive-text="Hoạt động"
                            inactive-color = "#13ce66"
                            active-color = "#C0CCDA"
                            :active-value = "0"
                            :inactive-value = "1"

                        >
                        </el-switch> 
                    </div>  
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
import Avatar from 'vue-avatar'
import API_USER from '@/utils/api/user'
import moment from 'moment'
import UserModel from '@/model/user'
export default {
    name: 'pcm-user-view',
    components: {
        Avatar
    },
    data() {
        return {
            loadingState: {
                userList: false,
                userForm: false
            },
            pickerOptions: {
                disabledDate(time) {
                return time.getTime() > Date.now();
                }
            },
            tabs: {
                activeName: 'active'
            },
            dialogVisible: {
                user: false
            },
            UserModel: UserModel
            ,
            form: {
                filter: {
                    type: 'active',
                    keyword: ''
                },
                user: {
                    id: 0,
                    first_name: '',
                    last_name: '',
                    gender: UserModel.gender.MALE,
                    birthday: '',
                    phone: '',
                    address: '',
                    email: '',
                    password: '',
                    role: UserModel.role.USER,
                    can_create_project: 0,
                    type: UserModel.type.OFFICIAL,
                    salary: 0,
                    status : UserModel.status.ACTIVE
                }
            },
            users: {
                count: [],
                list: []
            }
        }
    },
    created() {
        let self = this;
        this.$emit('activeMenu', 'users');
        self.loadData()
    },
    watch: {
        'form.filter.keyword': _.debounce(function(newVal, oldVal) {
                                    this.loadData()
                                    },
                                    500 
                                ),
        '$store.state.flag_change' : function() {
            let self = this;
            if(self.$store.state.flag_change) {
                self.loadData();
                self.$store.dispatch('setFlagChange', false);
            }
        }
    },
    methods: {
        loadData() {
            let self = this
            self.loadingState.userList = true
            API_USER.getList(self.$axios, self.form.filter, function(data) {
                self.loadingState.userList = false
                if (data.status) {
                    self.users = data.data
                } else {
                    self.showMessage('error', data.error_message)
                }
            }, function(error) {
                self.loadingState.userList = false
            })
        },
        rowDbClick(row) {
            this.editUser(row.id)
        },
        submitFormUser() {
            let self = this
            self.$validator.validateAll().then((result) => {
                if (result) {
                    self.loadingState.userForm = true
                    if (self.form.user.id == 0) {
                        API_USER.register(self.$axios, self.form.user, function (data) {
                            self.loadingState.userForm = false
                            if (data.status) {
                                self.showMessage('success', 'Thêm mới nhân sự thành công!')
                                self.resetFormUser()
                                self.dialogVisible.user = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.userForm = false
                        })
                    } else {
                        API_USER.update(self.$axios, self.form.user, function (data) {
                            self.loadingState.userForm = false
                            if (data.status) {
                                if(self.form.user.id == self.$store.state.app_user.user.id ) {
                                    self.$store.state.app_user.user = self.form.user;
                                    self.$store.state.app_user.user.full_name = self.form.user.first_name + ' ' +self.form.user.last_name;
                                }
                                self.showMessage('success', 'Chỉnh sửa nhân sự thành công!')
                                self.resetFormUser()
                                self.dialogVisible.user = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.userForm = false
                        })
                    }
                }
            })
        },
        resetFormUser() {
            this.$validator.reset()
            this.form.user = {
                id: 0,
                first_name: '',
                last_name: '',
                gender: UserModel.gender.MALE,
                birthday: '',
                phone: '',
                address: '',
                email: '',
                password: '',
                role: UserModel.role.USER,
                can_create_project: 0,
                type: UserModel.type.OFFICIAL,
                salary: 0,
                status : UserModel.status.ACTIVE
            }
        },
        editUser(id) {
            let self = this
            if (self.form.user.id == id && self.$store.state.app_user.user.id != id) {
                return self.dialogVisible.user = true
            }
            self.resetFormUser()
            self.loadingState.userList = true
            API_USER.get(self.$axios, {id: id}, function(data){
                self.loadingState.userList = false
                if (data.status) {
                    self.form.user = data.data
                    self.form.user.birthday = moment(self.form.user.birthday).format('YYYY-MM-DD')
                    self.dialogVisible.user = true
                } else {
                    self.showMessage('error', 'Không tìm thấy !')
                }
            }, function(error){
                self.loadingState.userList = false
            })
        }
    }
}
</script>