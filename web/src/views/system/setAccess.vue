<template>
    <a-modal title="权限" :width="500" :visible="visible" :confirmLoading="confirmLoading" @ok="onOk" @cancel="$emit('cancel')">
        <a-tree
            v-model:checkedKeys="checkedKeys"
            checkable
            default-expand-all
            :tree-data="treeData"
        />
    </a-modal>
</template>

<script lang="js">
import { Tree as ATree } from 'ant-design-vue';
import {defineComponent, ref, toRefs, watch} from 'vue';
import { accessList } from "@/api/menu"

export default defineComponent({
    props:{
        visible: {
            type: Boolean,
            default: false
        },
        confirmLoading: {
            type: Boolean,
            default: false
        },
        defaultCheckedKeys: {
            default: ()=>[]
        }
    },
    emits:['ok','cancel'],
    components:{
        ATree
    },
    setup(props, { emit }){
        const { visible, defaultCheckedKeys } = toRefs(props)
        const checkedKeys = ref();
        const treeData  =   ref([]);
        const fieldNames = {
            title: 'name',
            key:'id'
        };
        accessList().then((res)=>{
            treeData.value = res.data
        })

        watch(visible,()=>{
            if (visible.value){
                checkedKeys.value = defaultCheckedKeys.value
            }
        })

        const onOk  =   ()=>{
            emit('ok',checkedKeys.value)
        }

        return {
            treeData,
            checkedKeys,
            fieldNames,
            onOk,
        }
    }
})
</script>
