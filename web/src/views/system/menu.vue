<template>
    <a-card class="h-100">
        <a-row :gutter="16">
            <a-col :span="8" >
                <a-card size="small" :bordered="false">
                    <template #extra>
                        <a-space>
                            <a-button v-auth="'menu@create'" type="primary" @click="resetForm"><plus-outlined />添加菜单</a-button>
                            <a-button v-auth="'menu@delete'" type="primary" danger @click="delMenu"><delete-outlined />删除菜单</a-button>
                        </a-space>
                    </template>
                    <a-tree
                        :field-names="{key: 'id',value: 'id'}"
                        v-model:expandedKeys="expandedKeys"
                        v-model:selectedKeys="selectedKeys"
                        showLine
                        draggable
                        :tree-data="treeData"
                        @select="onSelect"
                        @drop="onDrop"
                    />
                </a-card>
            </a-col>
            <a-col :span="16">
                <a-card v-auth:and="['menu@create','menu@update']" :title="formState.id ? '编辑菜单' : '添加菜单'" size="small" :bordered="true">
                    <a-alert message="具体使用参考vue-router路由注册结构！！！" type="info" show-icon style="margin-bottom: 20px"/>
                    <a-form :model="formState" :rules="rules" ref="formRef" @finish="onSubmit">
                        <a-form-item name="parent_id" :rules="{required: true,message: '请输入菜单名称！',trigger: 'blur',}" label="菜单名称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-tree-select v-model:value="formState.parent_id"
                                placeholder="请选择"
                                :tree-line="true"
                                :tree-data="treeSelectData"
                                :field-names="{key: 'id',value: 'id'}"
                            />
                        </a-form-item>
                        <a-form-item name="title" label="菜单名称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-input v-model:value="formState.title" placeholder="请输入" />
                        </a-form-item>
                        <template v-if="formState.parent_id == 0">
                            <a-form-item name="icon" label="图标" :labelCol="labelCol" :wrapperCol="wrapperCol">
                                <a-input v-model:value="formState.icon" placeholder="请选择图标" style="width: 200px;margin-right: 5px"/>
                                <a-button type="primary" @click="iconVisible = true">选择</a-button>
                            </a-form-item>
                        </template>
                        <a-form-item name="name" label="路由名称" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-input v-model:value="formState.name" placeholder="请输入名称(英文)" />
                        </a-form-item>
                        <a-form-item name="path" label="路由规则" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-input v-model:value="formState.path" placeholder="请输入规则" />
                        </a-form-item>
                        <a-form-item :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <template #label>
                                <span style="margin-right: 5px">Layout</span>
                                <a-tooltip>
                                    <template #title>
                                        <p>RouterView：左侧父级菜单使用。</p>
                                        <p>UserLayout：登录页布局方式。</p>
                                        <p>BasicLayout：后台基础布局。</p>
                                    </template>
                                    <question-circle-outlined />
                                </a-tooltip>
                            </template>
                            <a-radio-group v-model:value="formState.layout">
                                <a-radio :value="1">是</a-radio>
                                <a-radio :value="0">否</a-radio>
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item v-if="formState.layout == 1" name="component" label="组件" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-radio-group v-model:value="formState.component">
                                <template v-for="item in layoutArr">
                                    <a-radio :value="item">{{ item }}</a-radio>
                                </template>
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item v-if="formState.layout == 0" name="component" label="组件" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-input v-model:value="formState.component" placeholder="请输入页面路径">
                                <template #prefix>
                                    @/views/
                                </template>
                                <template #suffix>
                                    .vue
                                </template>
                            </a-input>
                        </a-form-item>
                        <a-form-item name="redirect" label="重定向地址" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-input v-model:value="formState.redirect" placeholder="请输入" />
                        </a-form-item>
                        <a-form-item name="status" label="是否启用" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <a-radio-group v-model:value="formState.status">
                                <a-radio :value="1">启用</a-radio>
                                <a-radio :value="0">禁用</a-radio>
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item name="is_show" :labelCol="labelCol" :wrapperCol="wrapperCol">
                            <template #label>
                                <span style="margin-right: 5px">显示</span>
                                <a-tooltip>
                                    <template #title>
                                        是否在左侧菜单显示
                                    </template>
                                    <question-circle-outlined />
                                </a-tooltip>
                            </template>
                            <a-radio-group v-model:value="formState.is_show">
                                <a-radio :value="1">是</a-radio>
                                <a-radio :value="0">否</a-radio>
                            </a-radio-group>
                        </a-form-item>
                        <a-form-item :wrapperCol="{offset:6}">
                            <a-space>
                                <a-button htmlType="submit" type="primary" :loading="loading">保存</a-button>
                                <a-button type="primary" v-if="formState.id" @click="visible = true">设置权限</a-button>
                            </a-space>
                        </a-form-item>
                    </a-form>
                </a-card>
            </a-col>
            <set-access :visible="visible" :confirm-loading="confirmLoading" :default-checked-keys="formState.access" @ok="onOk" @cancel="visible = false"/>
            <select-icon :visible="iconVisible" @ok="onSelectIconOk" @cancel="iconVisible = false"/>
        </a-row>
    </a-card>
</template>
<script lang="ts">
import { DownOutlined } from '@ant-design/icons-vue';
import {defineComponent, ref, toRaw} from 'vue';
import type { FormInstance } from 'ant-design-vue';
import type {AntTreeNodeDropEvent,} from 'ant-design-vue/es/tree';
import { Tree as ATree, TreeSelect as ATreeSelect, message } from 'ant-design-vue'
import { PlusOutlined, DeleteOutlined } from '@ant-design/icons-vue'
import {list, save, del, sort} from "@/api/menu";
import * as layout from "@/layouts/index";
import setAccess from "@/views/system/setAccess.vue";
import selectIcon from '@/views/system/selectIcon.vue'
interface FormState {
    id: number,
    name: string,
    path: string,
    title:string,
    icon:string,
    component: string,
    redirect: string,
    status: number,
    layout: number,
    is_show: number,
    parent_id: number,
    access: number[] | string[]
}

interface ThreeData extends FormState{
    key: number,
    children?: ThreeData[]
}
export default defineComponent({
    components: {
        DownOutlined,
        ATree,
        PlusOutlined,
        DeleteOutlined,
        ATreeSelect,
        setAccess,
        selectIcon
    },
    setup() {
        const expandedKeys = ref<number[]>([]);
        const selectedKeys = ref<number[]>([]);
        const treeData = ref<ThreeData[]>([]);
        const treeSelectData = ref<ThreeData[]>([]);
        const formRef = ref<FormInstance>();
        const loading = ref<boolean>(false)
        const visible = ref<boolean>(false)
        const iconVisible = ref<boolean>(false)
        const confirmLoading = ref<boolean>(false)
        const formState = ref<FormState>({
            id: 0,
            name: '',
            path: '',
            title:'',
            icon:'',
            component: '',
            redirect: '',
            status: 1,
            layout: 0,
            is_show: 1,
            parent_id: 0,
            access:[]
        });
        const rules =   {
            title : {required: true,message: '请输入菜单名称！',trigger: 'blur'},
            name: {required: true,message: '请输入路由名称！',trigger: 'blur'},
            path: {required: true,message: '请输入路由规则！',trigger: 'blur'},
            component: {required: true,message: formState.value.layout == 1 ? '请选择组件！' : '请输入组件路径!'}
        }
        const layoutArr =   ref<string[]>(Object.keys(layout))
        const init = ()=>{
            list().then(({data})=>{
                const one   =   [{
                    id: 0,
                    key: '0',
                    title:'一级菜单',
                }]
                treeData.value = data
                treeSelectData.value = [...one,...data]
                if (expandedKeys.value.length == 0){
                    let keys: number[] = []
                    data.map((item: ThreeData)=>{
                        keys.push(item.id)
                    })
                    expandedKeys.value = keys
                }
            })
        }

        init()

        const resetForm = ()=>{
            formState.value = {
                id: 0,
                name: '',
                path: '',
                title:'',
                icon:'',
                component: '',
                redirect: '',
                status: 1,
                layout: 0,
                is_show: 1,
                parent_id: 0,
                access:[]
            }
        }

        const delMenu = ()=>{
            if (selectedKeys.value.length == 0){
                return message.error('请选择要删除的数据！')
            }
            del(selectedKeys.value).then((res:any)=>{
                message.success(res.message)
                init()
            })
        }

        const onSubmit = () => {
            loading.value = true
            if (formState.value.parent_id == 0){
                formState.value.icon = ''
            }
            save(formState.value).then((res:any)=>{
                loading.value = false
                message.success(res.message)
                init()
                resetForm()
                selectedKeys.value = []
            }).catch(()=>loading.value = false)
        };

        const onSelect = (keys: number[], e:any)=>{
            if (keys.length > 0){
                const info: FormState = toRaw(e.selectedNodes[0])
                formState.value = info
                if (layoutArr.value.includes(info.component)){
                    formState.value.layout = 1
                }else {
                    formState.value.layout = 0
                }
            }else {
                resetForm()
            }
        }

        const onDrop = (info: AntTreeNodeDropEvent) => {
            const dropKey = info.node.key;
            const dragKey = info.dragNode.key;
            // @ts-ignore
            const dropPos = info.node.pos.split('-');
            const dropPosition = info.dropPosition - Number(dropPos[dropPos.length - 1]);
            sort({dropKey, dragKey, dropPosition}).then((res:any)=>{
                message.success(res.message)
                init()
            })
        }

        const onOk  =   (keys: number[])=>{
            const access    =   toRaw(keys)
            if (access.length == 0){
                return message.error('请选择权限！')
            }
            confirmLoading.value = true
            save({...formState.value,access}).then((res:any)=>{
                confirmLoading.value = false
                message.success(res.message)
                visible.value = false
                init()
                resetForm()
            }).catch(()=>confirmLoading.value = false)
        }

        const onSelectIconOk    =   (icon:string) => {
            iconVisible.value = false
            formState.value.icon = icon
        }

        return {
            expandedKeys,
            selectedKeys,
            treeData,
            labelCol: {lg: {span: 6}, sm: {span: 6}},
            wrapperCol: {lg: {span: 10}, sm: {span: 17} },
            formState,
            onSubmit,
            layoutArr,
            formRef,
            rules,
            loading,
            treeSelectData,
            onSelect,
            init,
            resetForm,
            delMenu,
            onDrop,
            visible,
            confirmLoading,
            onOk,
            iconVisible,
            onSelectIconOk
        };
    }
});
</script>

