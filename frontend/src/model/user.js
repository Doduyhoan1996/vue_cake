export default {
    gender: {
        MALE: 1,
        FEMALE: 2
    },
    role: {
        ADMIN: 1,
        HR: 2,
        USER: 9
    },
    getOptionRole() {
        return [
            {
                key: this.role.ADMIN,
                value: 'Quản trị'
            },
            {
                key: this.role.HR,
                value: 'Hành chính'
            },
            {
                key: this.role.USER,
                value: 'Thành viên'
            }
        ]
    },
    type: {
        OFFICIAL: 1,
        TRAINEE: 9
    },
    getOptionType() {
        return [
            {
                key: this.type.OFFICIAL,
                value: 'Chính thức'
            },
            {
                key: this.type.TRAINEE,
                value: 'Thực tập'
            }
        ]
    },
    status: {
        ACTIVE: 1,
        BLOCK: 0
    },

}