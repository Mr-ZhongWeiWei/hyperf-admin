<template>
        <div class="logo">
            <a>
                <img src="https://store.antdv.com/pro/preview/img/logo.59818776.png"/>
                <h1 :class="{'theme-light-title': navTheme == 'light'}">Admin Pro</h1>
            </a>
        </div>
        <div class="top-menu">
            <a-menu mode="horizontal" :theme="navTheme" @click="onClick" v-model:selected-keys="selectedKeys">
                <template v-for="(item,index) in menus" :key="index">
                    <template v-if="!item.children">
                        <a-menu-item :key="item.name">
                            <template #icon>
                                <PieChartOutlined/>
                            </template>
                            {{ item.meta.title }}
                        </a-menu-item>
                    </template>
                    <template v-else>
                        <sub-menu :menu-info="item" :key="item.key"/>
                    </template>
                </template>
            </a-menu>
        </div>

</template>

<script>
import {defineComponent, ref, watch} from 'vue'
import {useStore} from "vuex";
import {useRoute, useRouter} from "vue-router";
import SubMenu from "@/components/Menu/SubMenu";
import { mixin } from '@/utils/mixin'
export default defineComponent({
    name: 'TopMenu',
    props: {

    },
    mixins: [mixin],
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
.theme-light-title {
    color: @primary-color !important;
}

.logo {
    display: inline-block;
    margin-left: 20px;
    line-height: 48px;
    position: relative;
    align-items: center;
    line-height: 32px;
    cursor: pointer;
    overflow: hidden;
    -webkit-transition: all 0.3s;
    a {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
    }

    h1 {
        margin: 0 0 0 12px;
        overflow: hidden;
        color: #fff;
        font-weight: 600;
        font-size: 16px;
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

.top-menu {
    display: inline-block;
    margin-left: 20px;
}

:global(#basic-layout-breadcrumb) {
    padding-left: 20px!important;
}

:global(#right-content .ant-badge-count) {
    box-shadow: 0 0 0 1px #ff4d4f;
}
</style>
