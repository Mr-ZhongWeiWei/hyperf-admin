import {createStore} from 'vuex'

import user from './modules/user'

import app from './modules/app'

// default router permission control
import permission from './modules/permission'

// dynamic router permission control (Experimental)
// import permission from './modules/async-router'
import getters from './getters'

const Store = createStore({
    modules: {
        app,
        user,
        permission
    },
    state: {},
    mutations: {},
    actions: {},
    getters
})

export default Store
