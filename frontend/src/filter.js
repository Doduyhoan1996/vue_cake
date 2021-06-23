import Vue from 'vue'
import moment from 'moment'
import numeral from 'numeral'
import _ from 'lodash'
import 'moment/locale/vi'

Vue.filter('formatDate', function (value, format) {
    if (value) {
        format = format ? format : 'DD/MM/YYYY';
        return moment(value).format(format)
    } else {
        return '--'
    }
});

Vue.filter('momentFormat', function (value,format) {
    if (value) {
        return moment(value).format(format);
    }
})

Vue.filter('formatCurrency', function (value) {
    return numeral(value).format('0,0') + 'đ'
})

Vue.filter('formatNumber', function (value) {
    return numeral(value).format('0,0');
})

Vue.filter('limitText', function (value,number) {
        if(!_.isEmpty(value)){
            let body = value.replace('/(<([^>]+)>)/ig', '');
            return body.length > number ? body.substring(0, number) + '...' : body;
        }
        return value;
})
Vue.filter('formatCheckOut', function (value, check_date) {
    if (value) {
        var format = 'HH:mm'
        if (moment(value).format('YYYY-MM-DD') != check_date.split(" ")[0]) {
            format =  'DD/MM/YYYY HH:mm'
        }
        return moment(value).format(format)
    } else {
        return '--'
    }
});

Vue.filter('formatOvertime', function (value, check_date) {
    if (value) {
        var format = 'HH:mm'
        if (moment(value).format('YYYY-MM-DD') != moment(check_date).format('YYYY-MM-DD')) {
            format =  'DD/MM/YYYY HH:mm'
        }
        return moment(value).format(format)
    } else {
        return '--'
    }
});

Vue.filter('formatHourOT', function (hour, minute) {
    var time = hour +':'+ minute
    var duration = moment.duration(time);
    var str = moment(duration._data).format("HH:mm");
    return str
});