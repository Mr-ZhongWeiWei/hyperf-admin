import {mapState} from 'vuex'

const mixin = {
    computed: {
        ...mapState({
            layoutMode: (state:any) => state.app.layout,
            navTheme: (state:any) => state.app.theme,
            primaryColor: (state:any) => state.app.color,
        })
    }
}

export {mixin}
