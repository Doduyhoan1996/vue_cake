import * as mutation_types from '@/store/mutation_type'
import moment from 'moment'
import _ from 'lodash'
//Init module state
let state = {
    isSync: false,
    list: []
}

let getters = {
    getSettings: state => {
        return {
            isSync: state.isSync,
            data: state.list
        }
    }
}

let mutations = {
    [mutation_types.SETTING_AUTOLOAD](state, data) {
        state.isSync = data.isSync
        state.list = data.data
    },
    [mutation_types.SETTING_UPDATE](state, data) {
        let index = _.findIndex(state.list, function (item) {
            return item.setting_key == data.setting_key;
        });
        if (index != null) {
            state.list.splice(index, 1, data)
        }
        state.syncTime = moment().unix();
    },
}

let actions = {
    syncSettings({commit}, data) {
        commit(mutation_types.SETTING_AUTOLOAD, data)
    },
    updateSettingFromList({commit}, data) {
        commit(mutation_types.SETTING_UPDATE, data)
    },
}

export default {
    state,
    actions,
    getters,
    mutations
}