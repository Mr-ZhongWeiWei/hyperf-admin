import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store/'
import {setupAntd, setupAntIcon} from "@/core/lazy_use";
import './permission'
import "./global.less"
import {setupDirective} from "@/core/directive";

const app = createApp(App)
setupDirective(app)
setupAntd(app)
setupAntIcon(app)
app.use(router)
app.use(store)
app.mount('#app')
