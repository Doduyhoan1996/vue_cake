<template>
    <div id="pcm-issue-view">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Báo Cáo (Beta)</h1>
            </div>
             <!-- Content Row -->
             <div class="row">
                <div class="col-xl-12">
                    <div class="row">
                        <div class="col-xl-8">
                            <div class="card mb-4 border-left-primary">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-5 form-group">
                                            <h6 class="font-weight-bold">Tên nhân viên</h6>
                                            <el-input v-model="form.filter.keyword" size="small" placeholder="Nhập tên hoặc email">
                                                <i slot="prefix" class="el-input__icon el-icon-search"></i>
                                            </el-input>
                                        </div>
                                        <div class="col-md-5 form-group">
                                            <h6 class="font-weight-bold">Teams</h6>
                                            <div class=" m-auto">
                                                <el-checkbox-group v-model="checkListTeams">
                                                    <el-checkbox label="Android"></el-checkbox>
                                                    <el-checkbox label="IOS"></el-checkbox>
                                                    <el-checkbox label="PHP"></el-checkbox>
                                                </el-checkbox-group>
                                            </div>
                                        </div>
                                        <div class="col-md-2 m-auto">
                                            <el-button @click="loadData();" type="primary">Tìm kiếm</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        
                        <div class="col-xl-4">
                            <div class="card mb-4 border-left-primary">
                                <div class="card-body">
                                    <h6 class="font-weight-bold">Chọn ngày</h6>
                                    <div class="row">
                                        <div class="col-md-2 form-group">
                                         <el-date-picker
                                            size="mini"
                                            v-model="form.filter.dates"
                                            type="daterange"
                                            range-separator="-"
                                            start-placeholder="Start"
                                            end-placeholder="End"
                                            @change="loadData()"
                                            >
                                        </el-date-picker>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-12" v-loading="loadingState.reportList">
                    <div class="card shadow mb-4">
                        <!-- Card Header - Dropdown -->
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">Daily Report</h6>
                            <el-button size="small" type="success" @click="resetFormReport();  dialogVisible.report = true">Báo Cáo</el-button>
                        </div>
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="block">
                                <el-timeline :reverse="loadingState.reverse">
                                    <el-timeline-item  
                                        v-for="(reports, key_date) in reportList"
                                        :key="key_date"
                                        :timestamp="key_date" 
                                        placement="top">
                                        <div  
                                            v-for="(report, key) in reports"
                                            :key="key">
                                            <el-card>
                                                <i v-if="report.hight_light == ReportModel.hight_light.ACTIVE" class="el-icon-star-on float-right" style="font-size:50px;color:red"></i>
                                                <div class="d-flex">
                                                    <span class="pr-2">
                                                        <avatar :username="report.user.first_name + ' ' + report.user.last_name" :size="40"></avatar>
                                                    </span>
                                                    <div class="user-item-name">
                                                        <h6 class="font-weight-bold pt-2" >{{report.user.full_name}}</h6>
                                                    </div>
                                                </div>
                                               
                                                
                                                <p class="font-weight-bold m-0">1. Công việc ngày {{ report.date_report | formatDate('DD/MM/YYYY (dd)')}} </p>
                                                <pre v-html="report.work_content"></pre>
                                                <p class="font-weight-bold m-0">2. Công việc tiếp theo ngày {{ report.next_date_report | formatDate('DD/MM/YYYY (dd)')}} </p>
                                                <pre v-html="report.next_work_content"></pre>
                                                <div v-if="report.difficult != '' " >
                                                    <p class="font-weight-bold m-0">3. Khó khăn</p>
                                                    <pre v-html="report.difficult"></pre>
                                                </div>
                                                <el-button v-if="report.user_id == $store.state.app_user.user.id" size="mini" @click="editReport(report)">Sửa</el-button>
                                            </el-card>
                                        </div>
                                    </el-timeline-item>
                                </el-timeline>
                            </div>
                        </div>
                    </div>
                </div>
             </div>
        </div>

        <!-- Dialog - Report -->
        <form role="form" @submit.prevent="submitFormReport">
            <el-dialog title="Báo Cáo"
                custom-class="el-dialog-body-gray" 
                :visible.sync="dialogVisible.report" 
                width="500px"
            >
                <div v-loading="loadingState.reportForm">
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Ngày báo cáo</label>
                            <div>
                                <el-date-picker 
                                    v-model="form.report.date_report"
                                    v-validate="'required'" 
                                    data-vv-name="date_report"
                                    data-vv-as="Ngày báo cáo"  
                                    type="date"
                                    placeholder="Lựa chọn"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :class="{'is-invalid': errors.has('date_report')}"
                                    :picker-options="dateOptions"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Nội dung công viêc</label>
                            <div>
                                <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 4}"
                                    placeholder="Nội dung công việc"
                                    v-model="form.report.work_content"
                                    v-validate="'required'" 
                                    data-vv-name="work_content"
                                    data-vv-as="Nội dung công việc" 
                                    :class="{'is-invalid': errors.has('work_content')}"
                                    >
                                    
                                </el-input>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Ngày tiếp theo</label>
                            <div>
                                <el-date-picker 
                                    v-model="form.report.next_date_report"
                                    v-validate="'required'" 
                                    data-vv-name="next_date_report"
                                    data-vv-as="Ngày tiếp theo"  
                                    type="date"
                                    placeholder="Lựa chọn"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :class="{'is-invalid': errors.has('next_date_report')}"
                                />
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Nội dung công viêc dự kiến</label>
                            <div>
                                <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 4}"
                                    placeholder="Nội dung công viêc dự kiến"
                                    v-model="form.report.next_work_content"
                                    v-validate="'required'" 
                                    data-vv-name="next_work_content"
                                    data-vv-as="Nội dung công viêc dự kiến" 
                                    :class="{'is-invalid': errors.has('next_work_content')}"
                                    >
                                    
                                </el-input>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label>Khó khăn</label>
                            <div>
                                <el-input
                                    type="textarea"
                                    :autosize="{ minRows: 2, maxRows: 4}"
                                    placeholder="Khó khăn"
                                    v-model="form.report.difficult"
                                    >
                                    
                                </el-input>
                            </div>
                        </div>
                    </div>
                </div>
                <div slot="footer" class="dialog-footer d-flex justify-content-between"> 
                    <div>
                        <el-switch v-model="form.report.hight_light"
                            inactive-text="Hight light"
                            inactive-color = "#C0CCDA"
                            active-color = "#13ce66"
                            :active-value = "ReportModel.hight_light.ACTIVE"
                            :inactive-value = "ReportModel.hight_light.INACTIVE"

                        >
                        </el-switch> 
                    </div>  
                    <button type="submit" class="btn btn-sm btn-primary btn-icon-split" :disabled="loadingState.reportForm">
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

import moment from 'moment'
import Avatar from 'vue-avatar'
import ReportModel from '@/model/report'
import API_REPORT from '@/utils/api/report'

export default {
    name: 'pcm-report-view',
    components: {
        Avatar
    },
    data() {
        return {
            checkListTeams: ['Android', 'IOS' , 'PHP'],
            dialogVisible: {
                report: false
            },
            loadingState: {
                reportList: false,
                reportForm: false,
                reverse : true
            },
            dateOptions: {
                disabledDate(time) {
                    return time.getTime() > Date.now();
                }
            },
            ReportModel: ReportModel,
            form: {
                filter: {
                    // date: moment().format('YYYY-MM-DD'),
                    keyword: '',
                    dates:[
                        moment().format('YYYY-MM-01 h:mm:ss'),
                        moment().format('YYYY-MM-DD h:mm:ss')
                    ],
                },
                report:{
                    id: '',
                    date_report: moment().format('YYYY-MM-DD'),
                    work_content: '',
                    next_date_report: moment().add(1, 'days').format('YYYY-MM-DD'),
                    next_work_content: '',
                    difficult: '',
                    hight_light : ReportModel.hight_light.INACTIVE
                }
            },
            reportList: [],
            
        }
    },
    created() {
        let self = this;
        this.$emit('activeMenu', 'report.index');
        self.loadData();
        setInterval(() => {
            API_REPORT.getList(self.$axios, self.form.filter, function(data) {
                if (data.status) {
                    self.reportList = data.data
                } 
            }, function(error) {
            })
        }, 5000);
    },
    methods: {
        loadData() {
            let self = this
            self.loadingState.reportList = true
            API_REPORT.getList(self.$axios, self.form.filter, function(data) {
                self.loadingState.reportList = false
                if (data.status) {
                    self.reportList = data.data
                } else {
                    self.showMessage('error', data.error_message)
                }
            }, function(error) {
                self.loadingState.reportList = false
            })
        },
        submitFormReport() {
            let self = this
            self.$validator.validateAll().then((result) => {
                if (result) {
                    self.loadingState.reportForm = true
                    if (self.form.report.id == 0) {
                        API_REPORT.save(self.$axios, self.form.report, function (data) {
                            self.loadingState.reportForm = false
                            if (data.status) {
                                self.showMessage('success', 'Thêm mới báo cáo thành công!')
                                self.resetFormReport()
                                self.dialogVisible.report = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.reportForm = false
                        })
                    } else {
                        API_REPORT.update(self.$axios, self.form.report, function (data) {
                            self.loadingState.reportForm = false
                            if (data.status) {
                                self.showMessage('success', 'Chỉnh sửa báo cáo thành công!')
                                self.resetFormReport()
                                self.dialogVisible.report = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.reportForm = false
                        })
                    }
                }
            })
        },
        editReport(report){
            let self = this
            if (self.form.report.id == report.id) {
                return self.dialogVisible.report = true
            }
            self.resetFormReport()
            self.form.report.id = report.id
            self.form.report.date_report = report.date_report
            self.form.report.work_content = report.work_content
            self.form.report.next_date_report = report.next_date_report
            self.form.report.next_work_content = report.next_work_content
            self.form.report.difficult = report.difficult
            self.form.report.hight_light = report.hight_light
            self.dialogVisible.report = true
        },
        resetFormReport() {
            this.$validator.reset()
            this.form.report = {
                id: 0,
                date_report: moment().format('YYYY-MM-DD'),
                work_content: '',
                next_date_report: moment().add(1, 'days').format('YYYY-MM-DD'),
                next_work_content: '',
                difficult: '',
                hight_light : ReportModel.hight_light.INACTIVE
            }
        },

    }
}
</script>

