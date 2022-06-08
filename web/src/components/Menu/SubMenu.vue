<template>
    <a-sub-menu :key="menuInfo.name">
        <template #icon v-if="menuInfo.meta.icon">
            <component :is="$antIcons[menuInfo.meta.icon]"/>
        </template>
        <template #title>{{ menuInfo.meta.title }}</template>
        <template v-for="item in menuInfo.children" :key="item.name">
            <template v-if="!item.children">
                <a-menu-item :key="item.name">
                    <template #icon v-if="item.meta.icon">
                        <component :is="$antIcons[item.meta.icon]"/>
                    </template>
                    {{ item.meta.title }}
                </a-menu-item>
            </template>
            <template v-else>
                <sub-menu :menu-info="item" :key="item.name"/>
            </template>
        </template>
    </a-sub-menu>
</template>

<script>

import {defineComponent} from "vue";
import {MailOutlined, PieChartOutlined} from "@ant-design/icons-vue";

export default defineComponent({
    name: 'SubMenu',
    props: {
        menuInfo: {
            type: Object,
            default: () => ({}),
        },
    },
    components: {
        PieChartOutlined,
        MailOutlined,
    },

})
</script>
