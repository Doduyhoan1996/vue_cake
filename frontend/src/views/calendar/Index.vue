<template>
    <div id="pcm-calendar-view">
        <div class="container-fluid">
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Calendar (Beta)</h1>
            </div>
            <div>
                <FullCalendar v-loading="loadingState.calendarList"
                :header="{
                    center:'dayGridMonth,dayGridWeek,dayGridDay'
                }"
                :defaultView="config.view"
                :buttonText="config.bntText"
                :plugins="config.calendarPlugins"
                :locales="config.locale"
                selectable="true"
                @select="selectDate" 
                @eventClick="eventClick"
                :events="calendar.list"
                :datesRender="handleMonthChange"
                
                />
            </div>
           
            <div class="ibox-footer">
                <span class="badge btn-info badge-counter">Xin Nghỉ</span>
                &nbsp;
                <span class="badge btn-primary badge-counter">Lịch Họp</span>
                <br>
                <span class="badge text-warning badge-counter">Đợi Duyệt</span>
                &nbsp;
                <span class="badge text-danger badge-counter">Từ Chối</span>
                &nbsp;
                <span class="badge text-dark badge-counter">Chấp Nhận</span>
            
            </div>
        </div>
        


       <!-- Dialog - Calendar -->
        <form role="form" @submit.prevent="submitFormCalendar">
            <el-dialog title="Đăng ký lịch"
                       custom-class="el-dialog-body-gray"
                       :visible.sync="dialogVisible.calendar" 
                       width="500px"
            >
                <div v-loading="loadingState.calendarForm">
                     <div class="row form-group" v-if="form.calendar.full_name != ''">
                        <div class="col-sm-12">
                            <label class="required">Người đặt lịch</label>
                            <div>
                                <el-input size="small"
                                    v-model="form.calendar.full_name"
                                    :disabled="true"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Đặt lịch</label>
                            <div>
                                <el-select v-model="form.calendar.type" placeholder="Lựa chọn" @change="changeType">
                                    <el-option
                                        v-for="item in CalendarModel.getOptionType()"
                                        :key="item.key"
                                        :label="item.value"
                                        :value="item.key"
                                        :disabled="dialogVisible.editVisible"
                                            
                                    />
                                </el-select>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Ngày</label>
                            <div class="block" v-if="form.calendar.type == CalendarModel.type.DAY_OFF" >
                                <el-date-picker 
                                    v-model="form.calendar.dates"
                                    v-validate="'required'" 
                                    data-vv-name="dates" data-vv-as="khoảng ngày" 
                                    type="daterange"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :disabled="dialogVisible.editVisible"
                                    
                                >
                                </el-date-picker>
                            </div>
                            <div class="block" v-else>
                                <el-date-picker
                                    v-model="form.calendar.day_metting"
                                    data-vv-name="day_metting" data-vv-as="ngày nghỉ" 
                                    type="date"
                                    placeholder="chọn ngày"
                                    format="dd/MM/yyyy"
                                    value-format="yyyy-MM-dd"
                                    :disabled="dialogVisible.editVisible"
                                    v-validate="'required'" 
                                >
                                </el-date-picker>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group" v-if="form.calendar.type == CalendarModel.type.MEETING_ROOM 
                    || form.calendar.dates[0] == form.calendar.dates[1]">
                        <div class="col-sm-12">
                            <label class="required">Giờ</label>
                            <div>
                                <el-time-picker
                                    is-range
                                    v-model="form.calendar.times"
                                    range-separator="-"
                                    start-placeholder="Giờ bắt đầu"
                                    end-placeholder="Giờ kết thúc"
                                    format="HH:mm"
                                    value-format="HH:mm"
                                    :disabled="dialogVisible.editVisible"
                                    >
                                </el-time-picker>
                            </div>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <label class="required">Ghi chú</label>
                            <div>
                                <el-input size="small"
                                    type="textarea"
                                    v-model="form.calendar.title"
                                    v-validate="'required'"
                                    data-vv-name="title" data-vv-as="Mô tả" 
                                    :class="{'is-invalid': errors.has('title')}"
                                    :disabled="dialogVisible.editVisible"
                                />
                            </div>
                        </div>
                    </div>

                    <div class="row form-group" v-if="$acl.check('isAdmin') && form.calendar.type == CalendarModel.type.DAY_OFF">
                        <div class="col-sm-12">
                            <label class="required">Trạng Thái</label>
                            <div>
                                <el-select v-model="form.calendar.status" placeholder="Lựa chọn">
                                    <el-option
                                        v-for="item in CalendarModel.getOptionStatus()"
                                        :key="item.key"
                                        :label="item.value"
                                        :value="item.key"
                                        
                                            
                                    />
                                </el-select>
                            </div>
                        </div>
                    </div>

                    <div slot="footer" class="dialog-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-sm btn-primary btn-icon-split"  :disabled="loadingState.calendarForm">
                            <span class="icon text-white-50">
                                <i class="fas fa-save"></i>
                            </span>
                            <span class="text">Lưu</span>
                        </button>
                    </div>

                </div>
         
            </el-dialog>
        </form>

    </div>
</template>

<script>

import '@fullcalendar/core/main.css'
import '@fullcalendar/daygrid/main.css'
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction';
import '@fullcalendar/core/locales/vi';
import CalendarModel from '@/model/calendar'
import moment from 'moment'
import API_CALENDAR from '@/utils/api/calendar'

export default {
  components: {
    FullCalendar // make the <FullCalendar> tag available
  },
  name: 'pcm-calendar-view',
  data() {
    return {
      config:{
          calendarPlugins: [ 
              interactionPlugin,
              dayGridPlugin,
              timeGridPlugin,
            ],
          locale: 'vi',
          bntText:{
               today:'Hôm nay',
               month:'Tháng',
               week:'Tuần',
               day:'Ngày'
            },
          view: 'dayGridMonth'

      },
      
      loadingState: {
          calendarList: false,
          calendarForm: false
      },
      dialogVisible: {
          calendar: false,
          editVisible: false
      },
      CalendarModel : CalendarModel,
      form: {
          filter:{
            activeStart: '' ,
            activeEnd: ''
          },
          calendar: {
              id: 0,
              type: CalendarModel.type.DAY_OFF,
              title: '',
              status: CalendarModel.status.WAITING,
              confirm_by: '',
              dates: [],
              times: [],
              full_name: '',
              day_metting :''
              }
      },
      calendar:{
          list: [],
      }
    }
  },
  created() {
        let self = this;
        this.$emit('activeMenu', 'calendar');
        self.loadData();
        setInterval(() => {
            API_CALENDAR.getList(self.$axios, self.form.filter, function(data) {
                if (data.status) {
                    self.calendar = data.data
                }
            }, function(error) {
            })
        }, 5000);
    },
  methods: {
      loadData(){
           let self = this
            self.loadingState.calendarList = true
            API_CALENDAR.getList(self.$axios, self.form.filter, function(data) {
                self.loadingState.calendarList = false
                if (data.status) {
                    self.calendar = data.data
                } else {
                    self.showMessage('error', data.error_message)
                }
            }, function(error) {
                self.loadingState.calendarList = false
            })
        },
      selectDate(info) {
        let self = this
        self.resetFormCalendar()
        self.dialogVisible.editVisible = false
        self.dialogVisible.calendar = true
        var infoEndDate = new Date(info.end.getFullYear(), info.end.getMonth(), info.end.getDate() - 1)
        var endDate = moment(infoEndDate).format('YYYY-MM-DD');
        if(info.startStr == endDate){
            self.form.calendar.dates = [info.startStr, info.startStr]
            self.form.calendar.day_metting = info.startStr
        } else{
            self.form.calendar.dates = [info.startStr, endDate]
            self.form.calendar.day_metting = info.endDate
        } 

      },
      eventClick(avg){
          let self = this
          if(avg.event.extendedProps.user_id == self.$store.state.app_user.user.id && avg.event.extendedProps.status == 0){
            self.dialogVisible.editVisible = false
          } else self.dialogVisible.editVisible = true
          self.editCalendar(avg.event)
 
      },
      editCalendar(row) {
          let self = this
          if (self.form.calendar.id == row.id) {
              return self.dialogVisible.calendar = true
          }
          self.resetFormCalendar()
          self.form.calendar.id = row.id
          self.form.calendar.type = row.extendedProps.type
          self.form.calendar.title = row.title
          self.form.calendar.status = row.extendedProps.status
          self.form.calendar.confirm_by = row.extendedProps.confirm_by
          self.form.calendar.full_name = row.extendedProps.user.full_name
          self.form.calendar.day_metting = moment(row.start).format('YYYY-MM-DD')
          self.form.calendar.dates = [
              moment(row.start).format('YYYY-MM-DD') , 
              moment(row.end).format('YYYY-MM-DD')
              ]
          self.form.calendar.times = [
              moment(row.start).format('HH:mm') , 
              moment(row.end).format('HH:mm')
            ]
          self.dialogVisible.calendar = true 
        },
        changeType(){
            let self = this
          
        },

        submitFormCalendar(){
           let self = this
            self.$validator.validateAll().then((result) => {
                if (result) {
                    self.loadingState.calendarForm = true
                    if (self.form.calendar.id == 0) {
                        API_CALENDAR.save(self.$axios, self.form.calendar, function (data) {
                            self.loadingState.calendarForm = false
                            if (data.status) {
                                self.showMessage('success', 'Thêm mới lịch thành công!')
                                self.resetFormCalendar()
                                self.dialogVisible.calendar = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.calendarForm = false
                        })
                    } else {
                        API_CALENDAR.update(self.$axios, self.form.calendar, function (data) {
                            self.loadingState.calendarForm = false
                            if (data.status) {
                                self.showMessage('success', 'Chỉnh sửa lịch thành công!')
                                self.resetFormCalendar()
                                self.dialogVisible.calendar = false
                                self.loadData()
                            } else {
                                if (data.error_code == 103) {
                                    self.showInValidMessage(data.data)
                                }
                            }
                        }, function (error) {
                            self.loadingState.calendarForm = false
                        })
                    } 
                }
            })

        },
      resetFormCalendar() {
            this.$validator.reset()
            this.form.calendar = {
                id: 0,
                type: CalendarModel.type.DAY_OFF,
                title: '',
                status: CalendarModel.status.WAITING,
                confirm_by: '',
                dates: [],
                times: ['08:30','17:30'],
                full_name: '',
                day_metting :''
                }
        },
        handleMonthChange(avg){
            let self = this
            self.form.filter.activeStart = moment( avg.view.activeStart).format('YYYY-MM-DD');
            self.form.filter.activeEnd = moment( avg.view.activeEnd).format('YYYY-MM-DD');
            self.loadData()
          
        }
  }
}
</script>