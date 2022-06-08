<template>
    <a-modal :visible="visible" :width="700"  @cancel="$emit('cancel')" :footer="null">
        <a-tabs v-model:activeKey="activeKey">
            <a-tab-pane key="direction" tab="方向性图标"/>
            <a-tab-pane key="tips" tab="提示建议性图标"/>
            <a-tab-pane key="edit" tab="编辑类图标"/>
            <a-tab-pane key="datas" tab="数据类图标"/>
            <a-tab-pane key="brand" tab="品牌和标识"/>
            <a-tab-pane key="currency" tab="网站通用图标"/>
        </a-tabs>
        <a-row :gutter="[16,16]">
            <a-col @click="handleOk(item)" style="text-align: center;font-size: 25px;cursor: pointer" :span="2" v-for="(item,index) in icon[activeKey]" :key="index"><component :is="item"/></a-col>
        </a-row>
    </a-modal>
</template>
<script lang="ts">
import { defineComponent, ref } from 'vue';
import icon from "@/views/system/icon";
export default defineComponent({
    props:{
        visible: {
            type: Boolean,
            default: false
        }
    },
    emits:['ok','cancel'],
    setup(props, { emit }){
        const handleOk  =   (icon:string)=>{
            emit('ok',icon)
        }
        const activeKey = ref<string>('direction')
        return {
            icon,
            activeKey,
            handleOk
        }
    }
});
</script>