
const   REPORT_GET_LIST        = '/api/reports/get-list'
const   REPORT_SAVE            = '/api/reports/save'
const   REPORT_UPDATE            = '/api/reports/update'



export default {

    getList(axios, condition, success, fail) {
        let query = {
            params: condition
        }
        axios.get(REPORT_GET_LIST, query)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    save(axios, form, success, fail) {
        axios.post(REPORT_SAVE, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },
    update(axios, form, success, fail) {
        axios.post(REPORT_UPDATE, form)
            .then((response) => {
                let data = response.data
                success(data)
            })
            .catch((error) => {
                fail(error)
            })
    },

}