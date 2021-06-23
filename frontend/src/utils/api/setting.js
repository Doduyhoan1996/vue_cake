const GET_LIST = '/api/settings/autoload'
const GET_BY_KEY = '/api/settings/get-by-key'

let services = {
    getList(axios, success, fail) {
        axios.get(GET_LIST)
            .then((response) => {
                let dataJson = response.data
                success(dataJson)
            })
            .catch((error) => {
                fail(error)
            })
    },
    getByKey(axios, settingKey, success, fail) {
        let query = {
            params: {
                setting_key: settingKey
            }
        }
        axios.get(GET_BY_KEY, query)
            .then((response) => {
                let dataJson = response.data
                success(dataJson)
            })
            .catch((error) => {
                fail(error)
            })
    }
}

export default services