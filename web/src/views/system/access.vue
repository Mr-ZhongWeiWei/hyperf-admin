<template>
    <a-card :bordered="false" class="h-100">
        <template #title>
            <a-button v-auth="'access@create'" type="primary" @click="openModal()">
                <template #icon>
                    <PlusOutlined />
                </template>
                新增
            </a-button>
        </template>

        <a-table ref="table" size="small" rowKey="key" :columns="columns" :dataSource="tableData">
            <template #bodyCell="{ column, text, record, index }">
                <template v-if="column.dataIndex === 'action'">
                    <a-space>
                        <a v-auth="'access@update'" @click="openModal(record)">编辑</a>
                        <a v-auth="'access@delete'" @click="del(record.id)">删除</a>
                    </a-space>
                </template>
                <template v-if="column.dataIndex === 'type'">
                    {{ text == 1 ? '权限值' : '权限组' }}
                </template>
                <template v-if="column.dataIndex === 'rule'">
                    {{ text ? text : '--' }}
                </template>
            </template>
        </a-table>
        <a-modal :title="formState.id ? '编辑' : '新增'" :width="600" :visible="visible" :confirmLoading="confirmLoading" @ok="onSubmit" @cancel="visible = false">
            <a-form :model="formState" ref="formRef">
                <a-form-item name="type" label="类型" :labelCol="labelCol" :wrapperCol="wrapperCol">
                    <a-radio-group :disabled="formState.id ? true : false" v-model:value="formState.type">
                        <a-radio :value="1">权限值</a-radio>
                        <a-radio :value="2">权限分组</a-radio>
                    </a-radio-group>
                </a-form-item>
                <a-form-item name="name" :rules="{required: true,message: '请输入名称！',trigger: 'blur'}" label="名称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                    <a-input v-model:value="formState.name" placeholder="请输入" />
                </a-form-item>
                <a-form-item v-if="formState.type == 1" name="parent_id" :rules="{required: true,message: '请输入选择所属分组！',trigger: 'blur'}" label="所属分组" :labelCol="labelCol" :wrapperCol="wrapperCol">
                    <a-select v-model:value="formState.parent_id" placeholder="请选择">
                        <template v-for="item in tableData">
                            <a-select-option v-if="item.type == 2" :value="item.id">{{item.name}}</a-select-option>
                        </template>
                    </a-select>
                </a-form-item>
                <a-form-item v-if="formState.type == 1" name="rule" :rules="{required: true,message: '请输入权限值！',trigger: 'blur'}" label="权限值" :labelCol="labelCol" :wrapperCol="wrapperCol">
                    <a-input v-model:value="formState.rule" placeholder="请输入" />
                </a-form-item>
            </a-form>
        </a-modal>
    </a-card>
</template>

<script lang="ts">
import {defineComponent, ref, onMounted, createVNode, watch} from 'vue'
import { accessList, accessSave, delAccess } from '@/api/menu'
import {FormInstance, message, Modal} from "ant-design-vue";
import { ExclamationCircleOutlined } from "@ant-design/icons-vue"
import {checkAccess} from "@/utils/util";
interface FormState {
    id: number,
    name: string,
    rule: string,
    type: number,
    parent_id?: number
}
export default defineComponent({
    name: 'TableList',
    setup(){
        const visible           =   ref<boolean>(false)
        const confirmLoading    =   ref<boolean>(false)
        const tableData         =   ref([])
        const openModal =   (record: FormState)=>{
            visible.value = true
            if (record){
                formState.value = record
            }else {
                resetFormState()
            }
        }

        const init      =   ()=>{
            accessList().then((res:any)=>{
                tableData.value = res.data
            })
        }

        const formRef = ref<FormInstance>();
        const loading = ref<boolean>(false)
        const formState = ref<FormState>({
            id: 0,
            name: '',
            rule: '',
            type: 1,
            parent_id:undefined,
        });

        onMounted(()=>{
            init()
        })

        watch(()=>visible.value,()=>{
            formRef.value?.clearValidate()
        })

        const resetFormState    =   ()=>{
            formState.value =   {
                id: 0,
                name: '',
                rule: '',
                type: 1,
                parent_id:undefined,
            }
        }

        const onSubmit = () => {
            formRef.value?.validateFields().then(() => {
                confirmLoading.value = true
                    accessSave(formState.value).then((res:any)=>{
                        confirmLoading.value = false
                        message.success(res.message)
                        init()
                        visible.value = false
                        resetFormState()
                    }).catch(()=>confirmLoading.value = false)
                })
        }

        const del   =   (id: string | number)=>{
            Modal.confirm({
                title: '您确定要删除吗?',
                icon: createVNode(ExclamationCircleOutlined),
                content: 'Some descriptions',
                okText: '确定',
                okType: 'danger',
                cancelText: '考虑一下',
                onOk() {
                    delAccess(id).then((res:any)=>{
                        message.success(res.message)
                        init()
                    })
                }
            });
        }
        const columns   =   ref([
            {
                title: '名称',
                dataIndex: 'name'
            },
            {
                title: '规则',
                dataIndex: 'rule',
                width: 150,
                align: 'center'
            },
            {
                title: '类型',
                dataIndex: 'type',
                width: 80,
                align: 'center'
            },
            {
                title: '创建时间',
                dataIndex: 'created_at',
                width: 150,
                align: 'center'
            },
            {
                title: '操作',
                dataIndex: 'action',
                width: '150px',
                align: 'center'
            }
        ])
        if (!checkAccess(['access@update', 'access@delete'])){
            columns.value    =   columns.value.slice(0,-1)
        }

        return {
            visible,
            confirmLoading,
            openModal,
            loadData: () => accessList(),
            columns,
            formState,
            onSubmit,
            formRef,
            loading,
            labelCol: {lg: {span: 6}, sm: {span: 6}},
            wrapperCol: {lg: {span: 10}, sm: {span: 17} },
            init,
            tableData,
            resetFormState,
            del
        }
    }
})
</script>
