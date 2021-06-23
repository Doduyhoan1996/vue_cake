export default {
    status: {
        ACCEPT: 1,
        WAITING : 0,
        DENY  : -1
    },
    type: {
        DAY_OFF  : 0,
        MEETING_ROOM: 1,
        INTERN_TIME: 2,
    },
    getOptionStatus() {
        return [
            {
                key: this.status.ACCEPT,
                value: 'Chấp nhận'
            },
            {
                key: this.status.DENY,
                value: 'Từ chối'
            },
            {
                key: this.status.WAITING,
                value: 'Đợi Duyệt'
            }
            
        ]
    },
    getOptionType() {
        return [
            {
                key: this.type.INTERN_TIME,
                value: 'Thực tập'
            },
            {
                key: this.type.MEETING_ROOM,
                value: 'Phòng họp'
            },
            {
                key: this.type.DAY_OFF,
                value: 'Nghỉ phép'
            }
            
        ]
    },

}