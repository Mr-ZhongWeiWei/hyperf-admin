<template>
    <a-modal :visible="visible" :maskClosable="false" :title="formState.id ? '编辑' : '新增'" @ok="handleOk" :confirmLoading="confirmLoading" @cancel="()=>$emit('cancel')">
        <a-form :model="formState" :rules="rules" ref="formRef">
            <a-form-item name="name" label="角色名称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.name" placeholder="请输入" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="status" :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" label="状态" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-radio-group v-model:value="formState.status">
                    <a-radio :value="1">启用</a-radio>
                    <a-radio :value="0">禁用</a-radio>
                </a-radio-group>
            </a-form-item>
            <a-form-item name="remark" label="备注说明" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-textarea v-model:value="formState.remark" placeholder="请输入" :rows="4" :maxlength="100"/>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script lang="ts">
import {defineComponent, ref, toRaw, watch, getCurrentInstance} from 'vue';
import {FormInstance, message} from "ant-design-vue";
import {save} from "@/api/role";

interface FormState {
    id?: number,
    name?: string,
    remark?: string,
    status?: number
}
export default defineComponent({
    props:{
        visible: {
            type: Boolean,
            default: false
        },
        data: {
            type: Object,
            default: {}
        }
    },
    emits:['ok','cancel'],
    setup(props, {emit}) {
        const formRef = ref<FormInstance>();
        const confirmLoading = ref<boolean>(false);
        const formState = ref<FormState>({
            id: undefined,
            name:'',
            remark: '',
            status: 1
        })
        const rules =   {
            name: {required: true,message: '请输入角色名称！',trigger: 'blur'},
        }

        watch(()=>props.visible,(val)=>{
            const data = {...toRaw(props).data}
            if (val && Object.keys(data).length > 0){
                formState.value = data
            }else {
                formState.value = {
                    id: undefined,
                    name:'',
                    remark: '',
                    status: 1
                }
            }
            formRef.value?.clearValidate()
        })

        const handleOk = () => {
            formRef.value?.validateFields().then(values => {
                confirmLoading.value = true
                values.id = formState.value.id
                save(values).then((res:any)=>{
                    message.success(res.message)
                    confirmLoading.value = false
                    emit('cancel')
                    emit('ok')
                }).catch(()=>confirmLoading.value = false)
            }).catch(()=>confirmLoading.value = false);
        };
        return {
            handleOk,
            formRef,
            rules,
            labelCol: {span: 6},
            wrapperCol: {span:15},
            formState,
            confirmLoading
        };
    },
});
</script>

