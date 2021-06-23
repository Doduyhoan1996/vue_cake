const   CALENDAR_GET_LIST           = '/api/calendars/get-list'
const   CALENDAR_GET                = '/api/calendars/get'
const   CALENDAR_SAVE               = '/api/calendars/save'
const   CALENDAR_UPDATE               = '/api/calendars/update'


export default {
    getList(axios, conditions, success, fail) {
        let query = {
            params: conditions
        }
        axios.get(CALENDAR_GET_LIST, query)
            .then((response) => {
                let dataJson = response.data
                success(dataJson)
            })
            .catch((error) => {
                fail(error)
            })
    },
    get(axios, params, success, fail) {
        let query = {
            params: params
        }
        axios.get(CALENDAR_GET, query)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    save(axios, form, success, fail) {
        axios.post(CALENDAR_SAVE, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    update(axios, form, success, fail) {
        axios.post(CALENDAR_UPDATE, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },

   
   
   
}