<template>
    <a-config-provider :locale="locale">
        <router-view/>
    </a-config-provider>
</template>

<script>
import Initializer from "@/core/bootstrap";
import zhCN from 'ant-design-vue/es/locale/zh_CN';
import dayjs from 'dayjs';
import 'dayjs/locale/zh-cn';
import request from "@/utils/request";
import store from "@/store";
dayjs.locale('zh-cn');

export default {
    data() {
        return {
            locale: zhCN,
        };
    },
    created: async () => {
        await Initializer()
        await request({url: '/getInitConfig', method: 'get'}).then(({data})=>store.dispatch('SetInitConfig', data))
    },
};
</script>

