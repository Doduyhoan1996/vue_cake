const USER_AUTH             = '/api/users/auth'
const USER_CHECK_SESSION    = '/api/users/check-session'
const USER_GET_LIST         = '/api/users/get-list'
const USER_GET_OPTION       = '/api/users/get-option'
const USER_REGISTER         = '/api/users/register'
const USER_UPDATE           = '/api/users/update'
const USER_GET              = '/api/users/get'
const USER_CHANGE_PASSWORD  = '/api/users/change-password'

export default {
    auth(axios, form, success, fail) {
        axios.post(USER_AUTH, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    checkSession(axios, success, fail) {
        axios.get(USER_CHECK_SESSION)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    getList(axios, condition, success, fail) {
        let query = {
            params: condition
        }
        axios.get(USER_GET_LIST, query)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    register(axios, form, success, fail) {
        axios.post(USER_REGISTER, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    update(axios, form, success, fail) {
        axios.post(USER_UPDATE, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    changePassword(axios, form, success, fail) {
        axios.post(USER_CHANGE_PASSWORD, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    get(axios, params, success, fail) {
        let query = {
            params: params
        }
        axios.get(USER_GET, query)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    getOption(axios, condition, success, fail) {
        axios.get(USER_GET_OPTION, condition)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
}