<template>
    <div class="setting-drawer">
        <a-drawer width="300" placement="right" @close="onClose" :closable="false" v-model:visible="visible" :drawer-style="{ position: 'absolute' }">
            <div class="setting-drawer-index-content">
                <div :style="{ marginBottom: '24px' }">
                    <h3 class="setting-drawer-index-title">整体风格设置</h3>

                    <div class="setting-drawer-index-blockChecbox">
                        <a-tooltip>
                            <template #title>
                                暗色菜单风格
                            </template>
                            <div class="setting-drawer-index-item" @click="handleMenuTheme('dark')">
                                <img src="https://gw.alipayobjects.com/zos/rmsportal/LCkqqYNmvBEbokSDscrm.svg" alt="dark">
                                <div class="setting-drawer-index-selectIcon" v-if="navTheme === 'dark'">
                                    <CheckOutlined />
                                </div>
                            </div>
                        </a-tooltip>

                        <a-tooltip>
                            <template #title>
                                亮色菜单风格
                            </template>
                            <div class="setting-drawer-index-item" @click="handleMenuTheme('light')">
                                <img src="https://gw.alipayobjects.com/zos/rmsportal/jpRkZQMyYRryryPNtyIC.svg" alt="light">
                                <div class="setting-drawer-index-selectIcon" v-if="navTheme !== 'dark'">
                                    <CheckOutlined />
                                </div>
                            </div>
                        </a-tooltip>
                    </div>
                </div>

                <div :style="{ marginBottom: '24px' }">
                    <h3 class="setting-drawer-index-title">主题色</h3>

                    <div style="height: 20px">
                        <a-tooltip class="setting-drawer-theme-color-colorBlock" v-for="(item, index) in colorList" :key="index">
                            <template #title>
                                {{ item.key }}
                            </template>
                            <a-tag :color="item.color" @click="changeColor(item.color)">
                                <CheckOutlined v-if="item.color === primaryColor"/>
                            </a-tag>
                        </a-tooltip>
                    </div>
                </div>
                <a-divider/>

<!--                <div :style="{ marginBottom: '24px' }">-->
<!--                    <h3 class="setting-drawer-index-title">导航模式</h3>-->

<!--                    <div class="setting-drawer-index-blockChecbox">-->
<!--                        <a-tooltip>-->
<!--                            <template #title>-->
<!--                                侧边栏导航-->
<!--                            </template>-->
<!--                            <div class="setting-drawer-index-item" @click="handleLayout('sidemenu')">-->
<!--                                <img src="https://gw.alipayobjects.com/zos/rmsportal/JopDzEhOqwOjeNTXkoje.svg" alt="sidemenu">-->
<!--                                <div class="setting-drawer-index-selectIcon" v-if="layoutMode === 'sidemenu'">-->
<!--                                    <CheckOutlined />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a-tooltip>-->

<!--                        <a-tooltip>-->
<!--                            <template #title>-->
<!--                                顶部栏导航-->
<!--                            </template>-->
<!--                            <div class="setting-drawer-index-item" @click="handleLayout('topmenu')">-->
<!--                                <img src="https://gw.alipayobjects.com/zos/rmsportal/KDNDBbriJhLwuqMoxcAr.svg" alt="topmenu">-->
<!--                                <div class="setting-drawer-index-selectIcon" v-if="layoutMode !== 'sidemenu'">-->
<!--                                    <CheckOutlined />-->
<!--                                </div>-->
<!--                            </div>-->
<!--                        </a-tooltip>-->
<!--                    </div>-->
<!--                </div>-->
            </div>
            <template #handle>
                <div v-if="visible" class="setting-drawer-index-handle" @click="toggle">
                    <CloseOutlined/>
                </div>
            </template>

        </a-drawer>
    </div>
</template>

<script>
import SettingItem from './SettingItem'
import {updateTheme, colorList} from './settingConfig'
import { mixin } from '@/utils/mixin'
export default {
    components: {
        SettingItem
    },
    mixins: [ mixin ],
    data() {
        return {
            visible: false,
            colorList
        }
    },
    watch: {},
    mounted() {
        updateTheme(this.primaryColor)
    },
    methods: {
        showDrawer() {
            this.visible = true
        },
        onClose() {
            this.visible = false
        },
        toggle() {
            this.visible = !this.visible
        },
        handleMenuTheme(theme) {
            this.$store.dispatch('ToggleTheme', theme)
        },

        handleLayout(mode) {
            this.$store.dispatch('ToggleLayoutMode', mode)
        },

        changeColor(color) {
            if (this.primaryColor !== color) {
                this.$store.dispatch('ToggleColor', color)
                updateTheme(color)
            }
        },
    }
}
</script>

<style lang="less" scoped>

.setting-drawer-index-content {

    .setting-drawer-index-blockChecbox {
        display: flex;

        .setting-drawer-index-item {
            margin-right: 16px;
            position: relative;
            border-radius: 4px;
            cursor: pointer;

            img {
                width: 48px;
            }

            .setting-drawer-index-selectIcon {
                position: absolute;
                top: 0;
                right: 0;
                width: 100%;
                padding-top: 15px;
                padding-left: 24px;
                height: 100%;
                color: #1890ff;
                font-size: 14px;
                font-weight: 700;
            }
        }
    }

    .setting-drawer-theme-color-colorBlock {
        width: 20px;
        height: 20px;
        border-radius: 2px;
        float: left;
        cursor: pointer;
        margin-right: 8px;
        padding-left: 0px;
        padding-right: 0px;
        text-align: center;
        color: #fff;
        font-weight: 700;

        i {
            font-size: 14px;
        }
    }
}

.setting-drawer-index-handle {
    position: absolute;
    top: 240px;
    background: #1890ff;
    width: 48px;
    height: 48px;
    right: 300px;
    display: flex;
    justify-content: center;
    align-items: center;
    cursor: pointer;
    pointer-events: auto;
    z-index: 1001;
    text-align: center;
    font-size: 16px;
    border-radius: 4px 0 0 4px;

    span {
        color: rgb(255, 255, 255);
        font-size: 20px;
    }
}
</style>
