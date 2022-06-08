<template>
    <a-layout-header class="header" :style="{ width: headerWidth, transition: '0.2s all', background: (navTheme !== 'dark' || layoutMode == 'sidemenu') ? '#fff' : '#001529' }">
        <top-menu v-if="layoutMode !== 'sidemenu'"></top-menu>
        <template v-if="layoutMode == 'sidemenu'">
            <menu-unfold-outlined v-if="collapsed" class="trigger" @click="() => $emit('toggle')"/>
            <menu-fold-outlined v-else class="trigger" @click="() => $emit('toggle')"/>
        </template>
        <right-content @toggle="()=>{$refs.SettingDrawer.toggle()}"></right-content>
    </a-layout-header>
    <setting-drawer ref="SettingDrawer"></setting-drawer>
</template>

<script>
import SettingDrawer from "../SettingDrawer/SettingDrawer";
import RightContent from "./RightContent";
import TopMenu from "../Menu/TopMenu";
import {mixin} from "../../utils/mixin";
export default {
    name: "GlobalHeader",
    props:{
        collapsed: {
            type: Boolean,
            default: false
        }
    },
    mixins:[mixin],
    components:{
        SettingDrawer,
        RightContent,
        TopMenu
    },
    emits:['toggle'],
    computed: {
        headerWidth () {
            return this.layoutMode == 'sidemenu' ? (`calc(100% - ${this.collapsed ? 48 : 208}px)`) : '100%'
        }
    },
}
</script>