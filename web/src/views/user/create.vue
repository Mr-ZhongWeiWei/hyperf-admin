<template>
    <a-modal :visible="visible" :width="700" :maskClosable="false" :title="formState.id ? '编辑' : '新增'" @ok="handleOk" :confirmLoading="confirmLoading" @cancel="()=>$emit('cancel')">
        <a-form :model="formState" :rules="rules" ref="formRef">
            <a-form-item name="login_name" label="账号" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.login_name" placeholder="请输入账号" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="roles" label="角色" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-select v-model:value="formState.roles" :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" mode="multiple" placeholder="请选择角色">
                    <a-select-option v-for="item in roles" :value="item.id">{{item.name}}</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item name="password" label="登录密码" :rules="[{required: !formState.id,message: '请输入登录密码！',trigger: 'blur'},{
                    min: 6,
                    max: 12,
                    message: '请输入长度为6~12的账号密码！',
                    trigger: 'blur',
                }]"  :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.password" placeholder="请输入密码" @change="onPasswordChange" />
            </a-form-item>
            <a-form-item name="nickname" label="昵称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.nickname" placeholder="请输入昵称" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="user_name" label="用户名" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.user_name" placeholder="请输入姓名" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="mobile" label="手机号" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.mobile" placeholder="请输入" :maxlength="20"/>
            </a-form-item>
            <a-form-item name="sex" label="性别" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-radio-group v-model:value="formState.sex">
                    <a-radio :value="3">保密</a-radio>
                    <a-radio :value="1">男</a-radio>
                    <a-radio :value="2">女</a-radio>
                </a-radio-group>
            </a-form-item>
            <a-form-item name="status" label="状态" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-radio-group v-model:value="formState.status">
                    <a-radio :value="1">启用</a-radio>
                    <a-radio :value="0">禁用</a-radio>
                </a-radio-group>
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script lang="ts">
import {defineComponent, ref, toRaw, watch} from 'vue';
import {FormInstance, message} from "ant-design-vue";
import {save} from "@/api/user";

interface FormState {
    id?: number,
    login_name: string,
    nickname: string,
    user_name: string,
    password: string,
    mobile: string,
    sex: number,
    status: number
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
        },
        roles: {
            type: Array,
            default: ()=>[]
        }
    },
    emits:['ok','cancel'],
    setup(props, {emit}) {
        const formRef = ref<FormInstance>();
        const confirmLoading = ref<boolean>(false);
        const formState = ref<FormState>({
            id: undefined,
            login_name:'',
            nickname: '',
            user_name: '',
            mobile: '',
            password:'',
            sex: 3,
            status: 1
        })
        const rules =   {
            login_name: {required: true,message: '请输入账号！',trigger: 'blur'},
            roles: {required: true,message: '请选择角色！',trigger: 'blur'},
            nickname: {required: true,message: '请输入昵称！',trigger: 'blur'},
        }

        watch(()=>props.visible,(val)=>{
            const data = {...toRaw(props).data}
            if (val && Object.keys(data).length > 0){
                (formState as any).value = data
            }else {
                formState.value = {
                    id: undefined,
                    login_name:'',
                    nickname: '',
                    user_name: '',
                    password: '',
                    mobile: '',
                    sex: 3,
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

