<template>
    <a-layout id="basic-layout" :style="{ paddingLeft: layoutMode == 'sidemenu' ? (collapsed ? '48px' : '208px') : 0, transition: '0.2s all'}">
        <side-menu v-if="layoutMode == 'sidemenu'" mode="inline" :collapsed="collapsed"></side-menu>
        <a-layout>
            <global-header :collapsed="collapsed" @toggle="() => (collapsed = !collapsed)"></global-header>
            <a-page-header id="basic-layout-breadcrumb" :breadcrumb="{ routes }"/>
            <a-layout-content class="layout-content">
<!--                <router-view/>-->
                <router-view v-slot="{ Component }">
                    <transition name="fade" mode="out-in">
                        <component :is="Component" />
                    </transition>
                </router-view>
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue';
import SideMenu from "@/components/Menu/SideMenu.vue";
import { useRoute } from "vue-router";
import {mixin} from "../utils/mixin";
import GlobalHeader from "../components/GlobalHeader/GlobalHeader.vue";
import {useStore} from "vuex";

interface Breadcrumb{
  path: string,
  breadcrumbName: any
}
export default defineComponent({
    components: {
        SideMenu,
        GlobalHeader
    },
    mixins:[mixin],
    setup() {

        const currentRoute = useRoute()
        const routes =   ref<Breadcrumb[]>([])
        const name  =   ref<any>('')
        const collapsed = ref(false)
        const getBreadcrumb =   () => {
            routes.value    =   []
            name.value    =   currentRoute.name
            currentRoute.matched.forEach((item)=>  {
                if (item.meta.is_show == 1){
                    routes.value.push({
                        path: item.path,
                        breadcrumbName: item.meta.title
                    } as Breadcrumb)
                }
            })
        }

        return {
            collapsed,
            routes,
            name,
            getBreadcrumb,
        };
    },
    computed: {
        headerWidth () {
            // return `calc(100% - ${this.collapsed ? 48 : 208}px)`
            return `calc(100% - 208px)`
        }
    },
    watch: {
        $route () {
            this.getBreadcrumb()
        }
    },
    created () {
        this.getBreadcrumb()
    },
});
</script>
<style lang="less">
@import url('./BasicLayout.less');
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
#basic-layout {
    min-height: 100vh;
}

#basic-layout .trigger {
    font-size: 18px;
    line-height: 48px;
    padding: 0 24px;
    cursor: pointer;
    transition: color 0.2s;
}

#basic-layout .trigger:hover {
    color: #1890ff;
}



#basic-layout .logo .ant-layout-sider-collapsed {
    padding: 16px 8px;
}
.site-layout .site-layout-background {
    background: #fff;
}

#basic-layout-breadcrumb {
    background: #fff;
    padding: 10px;
    margin-top: 48px;
}

#basic-layout .header {
    line-height: 48px;
    height: 48px;
    padding: 0;
    background: #fff;
    -webkit-box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
    box-shadow: 0 1px 4px rgb(0 21 41 / 8%);
    position: fixed;
    z-index: 9;
    right: 0px;
}

#basic-layout .layout-content {
    margin: 20px;
    -webkit-box-flex: 1;
    -ms-flex: auto;
    flex: auto;
    min-height: 0;
    height: 100%;
}

.ant-layout-footer {
    text-align: center;
}

</style>
