<template>
    <a-modal :visible="visible" :width="700" :maskClosable="false" :title="formState.id ? '编辑' : '新增'" @ok="handleOk" :confirmLoading="confirmLoading" @cancel="()=>$emit('cancel')">
        <a-form :model="formState" :rules="rules" ref="formRef">
            <a-form-item name="name" label="参数唯一KEY" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.name" placeholder="请输入key" />
            </a-form-item>
            <a-form-item name="label" label="参数名" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input v-model:value="formState.label" placeholder="请输入" />
            </a-form-item>
            <a-form-item name="type" label="显示类型" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-select placeholder="请选择" v-model:value="formState.type" :getPopupContainer="triggerNode => triggerNode.parentNode || document.body">
                    <a-select-option value="number">数字</a-select-option>
                    <a-select-option value="text">文本</a-select-option>
                    <a-select-option value="textarea">文本域</a-select-option>
                    <a-select-option value="checkbox">多选</a-select-option>
                    <a-select-option value="radio">单选</a-select-option>
                    <a-select-option value="switch">开关</a-select-option>
                    <a-select-option value="select">下拉选择</a-select-option>
                    <a-select-option value="line">分割线</a-select-option>
                    <a-select-option value="singleimg">图片(单张)</a-select-option>
                    <a-select-option value="multipleimg">图片(多张)</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item name="group_id" label="所属分组" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-select placeholder="请选择" v-model:value="formState.group_id" :getPopupContainer="triggerNode => triggerNode.parentNode || document.body">
                    <a-select-option v-for="item in group" :value="item.key">{{ item.name }}</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item name="data" v-if="formState.type == 'multipleimg'" :rules="{required: true,message: '请输入最大张数！',trigger: 'blur'}" label="最大张数" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-input-number v-model:value="formState.data" placeholder="请输入" :min="1"/>
            </a-form-item>
            <a-form-item name="extra" label="提示说明" :labelCol="labelCol" :wrapperCol="wrapperCol">
                <a-textarea v-model:value="formState.extra" placeholder="请输入" :rows="4" />
            </a-form-item>
            <a-form-item name="data" v-if="formState.type == 'checkbox' || formState.type == 'radio' || formState.type == 'select'" label="选项" :labelCol="labelCol" :wrapperCol="wrapperCol" extra="格式:值=显示信息,一行一个 如:1=开启">
                <a-textarea v-model:value="formState.data" placeholder="请输入" :rows="4" />
            </a-form-item>
        </a-form>
    </a-modal>
</template>
<script lang="ts">
import {defineComponent, ref, toRaw, watch} from 'vue';
import {FormInstance, message} from "ant-design-vue";
import {save} from "@/api/configs";

interface FormState {
    id?: number,
    name: string,
    label: string,
    type?: string,
    group_id?: string | number,
    extra: string
    data: string
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
        group: {
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
            name:'',
            label: '',
            type: undefined,
            group_id: undefined,
            data: '',
            extra: '',
        })
        const rules =   {
            name: {required: true,message: '请输入参数名！',trigger: 'blur'},
            label: {required: true,message: '请输入中文名！',trigger: 'blur'},
            type: {required: true,message: '请选择显示类型！',trigger: 'blur'},
            group_id: {required: true,message: '请选择分组！',trigger: 'blur'},
        }

        watch(()=>props.visible,(val)=>{
            const data = {...toRaw(props).data}
            if (data.dataTextArea){
                data.data = data.dataTextArea
            }
            if (val && Object.keys(data).length > 0){
                (formState as any).value = data
            }else {
                formState.value = {
                    id: undefined,
                    name:'',
                    label: '',
                    type: undefined,
                    group_id: undefined,
                    data:'',
                    extra:''
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
            confirmLoading,
        };
    },
});
</script>

