<template>
    <a-layout-sider :theme="navTheme" :width="208" id="basic-layout-side" :collapsedWidth="48" :collapsed="collapsed" :trigger="null" collapsible>
        <div class="logo" :style="slidePadding" >
            <a>
                <img src="https://store.antdv.com/pro/preview/img/logo.59818776.png"/>
                <h1 :class="{'theme-light-title': navTheme == 'light'}" v-if="!collapsed">{{ $store.getters.initConfig.siteTitle}}</h1>
            </a>
        </div>
        <a-menu :mode="mode" :theme="navTheme" @click="onClick" v-model:open-keys="openKeys" v-model:selected-keys="selectedKeys">
            <template v-for="(item,index) in menus" :key="index">
                <template v-if="!item.children">
                    <a-menu-item :key="item.name">
                        <template #icon>
                            <component :is="item.meta.icon"/>
                        </template>
                        {{ item.meta.title }}
                    </a-menu-item>
                </template>
                <template v-else>
                    <sub-menu :menu-info="item" :key="item.key"/>
                </template>
            </template>
        </a-menu>
    </a-layout-sider>

</template>

<script>
import {defineComponent, ref, watch} from 'vue'
import {useStore} from "vuex";
import {useRoute, useRouter} from "vue-router";
import SubMenu from "@/components/Menu/SubMenu";
import { mixin } from '@/utils/mixin'
export default defineComponent({
    name: 'SideMenu',
    props: {
        collapsed: {
            type: Boolean,
            required: false,
            default: false
        },
        mode: {
            type: String,
            default: 'inline'
        }
    },
    mixins: [mixin],
    computed: {
        slidePadding () {
            return {padding: `16px ${!this.collapsed ? 16 : 8}px`}
        }
    },
    setup(props) {
        const menus = ref([])
        const state = useStore()
        const routes = state.getters.addRouters.find(item => item.path === '/')
        menus.value = (routes && routes.children) || []
        const router = useRouter()
        const currentRoute = useRoute()
        const selectedKeys = ref([currentRoute.name])

        // 获取当前打开的子菜单
        const getOpenKeys = () => {
            if (currentRoute.matched.length > 3) {
                let keys = []
                for (let i = 1; i < currentRoute.matched.length - 1; i++) {
                    keys.push(currentRoute.matched[i].name)
                }
                return keys
            } else {
                return [currentRoute.matched[1]?.name]
            }
        }
        const openKeys = ref(getOpenKeys())
        const onClick = ({key}) => {
            if (/http(s)?:/.test(key)) {
                window.open(key)
            } else {
                router.push({name: key})
            }
        }
        // 监听菜单收缩状态
        watch(
            () => props.collapsed,
            (newVal) => {
                openKeys.value = newVal ? [] : getOpenKeys()
                selectedKeys.value = [currentRoute.name]
            }
        )

        // 跟随页面路由变化，切换菜单选中状态
        watch(
            () => currentRoute.fullPath,
            () => {
                openKeys.value = getOpenKeys()
                selectedKeys.value = [currentRoute.name]
            }
        )
        return {
            openKeys,
            selectedKeys,
            menus,
            onClick
        };
    },
    components: {
        SubMenu
    }
})
</script>
<style scoped lang="less">
@import "~ant-design-vue/es/style/themes/default.less";
#basic-layout-side {
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    box-shadow: 2px 0 8px 0 rgb(29 35 41 / 5%);
    z-index: 10;
    overflow: auto;
}

.logo {
    position: relative;
    display: flex;
    align-items: center;
    line-height: 32px;
    cursor: pointer;
    overflow: hidden;
    -webkit-transition: all 0.3s;
    a {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 32px;
    }

    h1 {
        margin: 0 0 0 12px;
        overflow: hidden;
        color: #fff;
        font-weight: 600;
        font-size: 15px;
        line-height: 32px;
        -webkit-animation: fade-in;
        animation: fade-in;
        -webkit-animation-duration: .2s;
        animation-duration: .2s;
        white-space:nowrap;
    }

    img {
        display: inline-block;
        height: 32px;
        vertical-align: middle;
    }
}
.theme-light-title {
    color: @primary-color !important;
}
</style>
