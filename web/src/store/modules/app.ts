const storage = require('store')
import {
    DEFAULT_COLOR,
    DEFAULT_THEME,
    DEFAULT_LAYOUT_MODE
} from '../mutation-types'

const app = {
    state: {
        sidebar: true,
        theme: 'dark',
        layout: 'sidemenu',
        color: '#1890FF',
        initConfig: {}
    },
    mutations: {
        TOGGLE_THEME: (state: { theme: any }, theme: any) => {
            storage.set(DEFAULT_THEME, theme)
            state.theme = theme
        },
        TOGGLE_COLOR: (state: { color: any }, color: any) => {
            storage.set(DEFAULT_COLOR, color)
            state.color = color
        },
        TOGGLE_LAYOUT_MODE: (state: { layout: any }, mode: any) => {
            storage.set(DEFAULT_LAYOUT_MODE, mode)
            state.layout = mode
        },
        SET_INIT_CONFIG: (state: any, config: any) => state.initConfig    =   config
    },
    actions: {
        ToggleTheme({commit}: any, theme: any) {
            commit('TOGGLE_THEME', theme)
        },
        ToggleColor({commit}: any, color: any) {
            commit('TOGGLE_COLOR', color)
        },
        ToggleLayoutMode({ commit }: any, mode: any) {
            commit('TOGGLE_LAYOUT_MODE', mode)
        },
        SetInitConfig({ commit }: any, config: any) {
            commit('SET_INIT_CONFIG', config)
        }
    }
}

export default app
