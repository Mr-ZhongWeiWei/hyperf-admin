/**
 * bootstrap
 * @author MrZhong [ 799149346@qq.com / http://github.com/Mr-ZhongWeiWei ]
 * @date 2022/4/5
 */
const storage   =   require('store')
import {DEFAULT_COLOR, DEFAULT_LAYOUT_MODE, DEFAULT_THEME} from "@/store/mutation-types";
import store from '@/store'

export default function Initializer() {
    storage.get(DEFAULT_THEME) && store.dispatch('ToggleTheme', storage.get(DEFAULT_THEME))
    storage.get(DEFAULT_COLOR) && store.dispatch('ToggleColor', storage.get(DEFAULT_COLOR))
    storage.get(DEFAULT_LAYOUT_MODE) && store.dispatch('ToggleLayoutMode', storage.get(DEFAULT_LAYOUT_MODE))
}