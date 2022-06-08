<template>
    <a-modal :visible="visible" :width="500" :maskClosable="false" title="修改密码" @ok="handleOk" :confirmLoading="confirmLoading" @cancel="()=>$emit('cancel')">
        <a-form :model="formState" ref="formRef">
            <a-form-item name="OldPassword" label="登录密码" :rules="[
                {required: true,message: '请输入密码！',trigger: 'blur'},
                {min: 6,max: 12,message: '请输入长度为6~12的账号密码！',trigger: 'blur'}
                ]"  :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input-password v-model:value="formState.OldPassword" @change="onOldPasswordChange" placeholder="请输入密码" />
            </a-form-item>
            <a-form-item name="password" label="新密码" :rules="[
                {required: true,message: '请输入新密码！',trigger: 'blur'},
                {min: 6,max: 12,message: '请输入长度为6~12的账号密码！',trigger: 'blur'}
                ]"  :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input-password v-model:value="formState.password" @change="onPasswordChange" placeholder="请输入密码" />
            </a-form-item>
            <a-form-item name="ConfirmPassword" label="确认密码" :rules="[
                {required: true,message: '请输入确认密码！',trigger: 'blur'},
                {min: 6,max: 12,message: '请输入长度为6~12的账号密码！',trigger: 'blur'}
                ]"  :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input-password v-model:value="formState.ConfirmPassword" @change="onConfirmPasswordChange" placeholder="请输入密码" />
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script lang="ts">
import {defineComponent, ref, watch} from 'vue';
import {FormInstance, message} from "ant-design-vue";
import {edotPassword} from "@/api/user";

export default defineComponent({
    name: "PersonalSettings",
    props:{
        visible: {
            type: Boolean,
            default: false
        },
    },
    emits:['cancel'],
    setup(props, {emit}) {
        const formRef = ref<FormInstance>();
        const confirmLoading = ref<boolean>(false);
        const formState = ref<{OldPassword: string, password: string,ConfirmPassword: string}>({
            OldPassword: '',
            password: '',
            ConfirmPassword: ''
        })

        watch(()=>props.visible,()=>{
            formState.value = {
                OldPassword: '',
                password: '',
                ConfirmPassword: '',
            }
            formRef.value?.clearValidate()
        })

        const handleOk = () => {
            formRef.value?.validateFields().then(values => {
                confirmLoading.value = true
                edotPassword(values).then((res:any)=>{
                    message.success(res.message)
                    confirmLoading.value = false
                    emit('cancel')
                }).catch(()=>confirmLoading.value = false)
            }).catch(()=>confirmLoading.value = false);
        };

        const onOldPasswordChange  =   (e:any)=>{
            formState.value.OldPassword = e.target.value.replace(/[\u4E00-\u9FA5]/g,'')
        }
        const onPasswordChange  =   (e:any)=>{
            formState.value.password = e.target.value.replace(/[\u4E00-\u9FA5]/g,'')
        }
        const onConfirmPasswordChange  =   (e:any)=>{
            formState.value.ConfirmPassword = e.target.value.replace(/[\u4E00-\u9FA5]/g,'')
        }
        return {
            handleOk,
            formRef,
            labelCol: {span: 6},
            wrapperCol: {span:15},
            formState,
            confirmLoading,
            onPasswordChange,
            onOldPasswordChange,
            onConfirmPasswordChange
        };
    },
});
</script>

