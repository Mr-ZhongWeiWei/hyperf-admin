<template>
    <a-modal :visible="visible" :width="700" :maskClosable="false" title="个人设置" @ok="handleOk" :confirmLoading="confirmLoading" @cancel="()=>$emit('cancel')">
        <a-form :model="formState" :rules="rules" ref="formRef">
            <a-form-item name="nickname" label="昵称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.nickname" placeholder="请输入昵称" :maxlength="20"/>
            </a-form-item>
<!--            <a-form-item name="password" label="登录密码" :rules="{-->
<!--                    min: 6,-->
<!--                    max: 12,-->
<!--                    message: '请输入长度为6~12的账号密码！',-->
<!--                    trigger: 'blur',-->
<!--                }"  :labelCol="labelCol" :wrapperCol="wrapperCol">-->
<!--                <a-input v-model:value="formState.password" @change="onPasswordChange" placeholder="请输入密码" />-->
<!--            </a-form-item>-->
            <a-form-item name="user_name" label="用户名" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.user_name" placeholder="请输入姓名" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="mobile" label="手机号" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.mobile" placeholder="请输入" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="sex" label="性别" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-radio-group v-model:value="formState.sex">
                    <a-radio value="3">保密</a-radio>
                    <a-radio value="1">男</a-radio>
                    <a-radio value="2">女</a-radio>
                </a-radio-group>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script lang="ts">
import {defineComponent, ref, toRaw, watch} from 'vue';
import {FormInstance, message} from "ant-design-vue";
import {settings} from "@/api/user";

interface FormState {
    nickname: string,
    user_name: string,
    mobile: string,
    password: string,
    sex: number,
}
export default defineComponent({
    name: "PersonalSettings",
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
            nickname: '',
            user_name: '',
            mobile: '',
            password: '',
            sex: 3,
        })
        const rules =   {
            nickname: {required: true,message: '请输入昵称！',trigger: 'blur'},
        }

        watch(()=>props.visible,(val)=>{
            const data = {...toRaw(props).data}
            if (val && Object.keys(data).length > 0){
                (formState as any).value = {
                    nickname: data.nickname,
                    user_name: data.user_name,
                    mobile: data.mobile,
                    sex: data.sex
                }
            }else {
                formState.value = {
                    nickname: '',
                    user_name: '',
                    password: '',
                    mobile: '',
                    sex: 3,
                }
            }
            formRef.value?.clearValidate()
        })

        const handleOk = () => {
            formRef.value?.validateFields().then(values => {
                confirmLoading.value = true
                settings(values).then((res:any)=>{
                    message.success(res.message)
                    confirmLoading.value = false
                    emit('cancel')
                    emit('ok')
                }).catch(()=>confirmLoading.value = false)
            }).catch(()=>confirmLoading.value = false);
        };

        const onPasswordChange  =   (e:any)=>{
            formState.value.password = e.target.value.replace(/[\u4E00-\u9FA5]/g,'')
        }
        return {
            handleOk,
            formRef,
            rules,
            labelCol: {span: 6},
            wrapperCol: {span:15},
            formState,
            confirmLoading,
            onPasswordChange
        };
    },
});
</script>

