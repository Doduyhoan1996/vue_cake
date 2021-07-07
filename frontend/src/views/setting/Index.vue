<template>
    <div id="pcm-setting-view">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Cấu Hình Cài Đặt (đang phát triển)</h1>
            </div>

            <!-- Content Row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card mb-4 border-left-primary" v-loading="loadingState.settingList">
                        <el-table :data="setting.List.filter(data => !search || data.setting_key.toLowerCase().includes(search.toLowerCase()))" style="width: 100%">
                            <el-table-column label="Setting Key" prop="setting_key"> </el-table-column>

                            <el-table-column label="Setting Value" prop="setting_value"> </el-table-column>

                            <el-table-column align="right">

                                <template slot="header" slot-scope="scope">
                                    <el-input
                                    v-model="search"
                                    size="mini"
                                    placeholder="Type to search"/>
                                </template>

                                <template slot-scope="scope">
                                    <el-button size="mini" @click="edit(scope.row)">Sửa</el-button>
                                </template>

                            </el-table-column>

                        </el-table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Dialog - Setting -->
        <form role="form" @submit.prevent="submitFormSetting">
            <el-dialog title="Thông tin tài khoản"
                custom-class="el-dialog-body-gray" 
                :visible.sync="dialogVisible.setting" 
                width="500px"
            >
                <div v-loading="loadingState.settingForm">
                    <div class="row form-group">
                        <div class="col-sm-6 col-form-label">
                            <label>{{form.setting.setting_key}}</label>
                        </div>
                        <div class="col-sm-6">
                            <div>
                                <el-input size="small"  v-model="form.setting.setting_value"
                                    v-validate="'required'"
                                    data-vv-name="setting_value" data-vv-as="setting_value"
                                    :class="{'is-invalid': errors.has('setting_value')}"
                                />
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer" class="dialog-footer">  
                     
                    <button type="submit" class="btn btn-sm btn-primary btn-icon-split" :disabled="loadingState.settingForm">
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

import API_SETTING from '@/utils/api/setting'
export default {
    name: 'pcm-setting-view',
    data() {
        return {
            loadingState: {
                settingList: false,
                settingForm: false
            },
            dialogVisible: {
                setting: false
            },
            setting: {
                List: []
            },
            search: '',
            form: {
                setting: {
                    id : '',
                    setting_key : '',
                    setting_value: ''
                },
                
            },
        }
    },
    created() {
        let self = this;
        this.$emit('activeMenu', 'setting_custom.setting');
        self.loadData();
    },
    methods: {
        loadData(){
           let self = this;
           self.loadingState.settingList = true;
            API_SETTING.getList(self.$axios, function (data) {
                if (data.status) {
                    self.setting.List = data.data
                    self.loadingState.settingList = false;
                } else {
                    self.loadingState.settingList = false;
                }
            }, function (error) {
                self.loadingState.settingList = false;
                console.log(error)
            })
        },
        edit( row) {
            let self = this;
            self.form.setting.id = row.id
            self.form.setting.setting_key = row.setting_key
            self.form.setting.setting_value = row.setting_value
            self.dialogVisible.setting = true;
        },
        submitFormSetting() {
            let self = this;
            API_SETTING.update(self.$axios, self.form.setting, function (data) {
                self.loadingState.settingForm = false
                if (data.status) {
                    self.showMessage('success', 'Thay đổi thành công!')
                    self.dialogVisible.setting = false
                    self.loadData()
                } else {
                    if (data.error_code == 103) {
                        self.showInValidMessage(data.data)
                    }
                }
            }, function (error) {
                self.loadingState.settingForm = false
            })
        }
        
    }
}
</script>

