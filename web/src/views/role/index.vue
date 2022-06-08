<template>
    <a-card>
        <s-table ref="table" :bordered="false" rowKey="id" :columns="columns" :data="loadData">
            <template #toolBarFilter>
                <a-space :size='10'>
                    <a-input allowClear placeholder="请输入关键字" v-model:value="queryParam.keyword" :maxlength="50"/>
                    <a-range-picker :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" allowClear v-model:value="date" @change="onChange"/>
                    <a-select :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" allowClear v-model:value="queryParam.status" placeholder="状态" style="width: 90px" @change="$refs.table.refresh(true)">
                        <a-select-option :value="0">禁用</a-select-option>
                        <a-select-option :value="1">启用</a-select-option>
                    </a-select>
                    <a-button type="primary" @click="$refs.table.refresh(true)">搜索</a-button>
                    <a-button @click="onReset">重置</a-button>
                </a-space>
            </template>
            <template #toolbarAction>
                <a-button v-auth="'role@create'" type="primary" @click="visible = true">
                    <template #icon>
                        <PlusOutlined />
                    </template>
                    新增
                </a-button>
            </template>
            <template #bodyCell="{ column, record }">
                <template v-if="column.dataIndex === 'status'">
                    <a-switch :checked="record.status == 1 ? true : false" @change="onChangeSwitch($event,record)" data-id="record.id" checked-children="启用" un-checked-children="禁用" />
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <a-space>
                        <a @click="onEdit(record)" v-auth="'role@update'">编辑</a>
                        <a @click="openSetAccess(record)" v-auth="'role@setAccess'">权限</a>
                        <a @click="onDelete(record.id)" v-auth="'role@delete'">删除</a>
                    </a-space>
                </template>
            </template>
        </s-table>
        <create :visible="visible" @ok="$refs.table.refresh()" @cancel="onCancel" :data="info"/>
        <set-access :visible="accessVisible" :confirm-loading="confirmLoading" :default-checked-keys="record.access" @ok="onSelectAccessOk" @cancel="accessVisible = false"/>
    </a-card>
</template>

<script lang="ts">
import STable from '@/components/Table'
import { list, onSwitch, Delete, setAccess as saveAccess } from "@/api/role";
import {ref, toRaw} from "vue";
import {message, Modal} from "ant-design-vue";
import create from "@/views/role/create.vue";
import setAccess from "@/views/system/setAccess.vue";
import {mixin} from "@/utils/mixin";
import {checkAccess} from "@/utils/util";

interface QueryParams {
    keyword?: string,
    time?: string,
    status?: number
}
export default {
    mixins:[mixin],
    components:{
        STable,
        create,
        setAccess
    },
    setup(){
        const columns = ref([
            {
                title: '序号',
                dataIndex: 'key',
                width: '80px',
                align: 'center'
            },
            {
                title: '角色名称',
                dataIndex: 'name',
            },
            {
                title: '备注说明',
                dataIndex: 'remark',
            },
            {
                title: '创建时间',
                dataIndex: 'created_at',
                width: '200px',
                align:'center',
                sorter: true,
            },
            {
                title: '状态',
                dataIndex: 'status',
                width: '100px',
                align: 'center'
            },
            {
                title: '操作',
                dataIndex: 'action',
                width: '150px',
                align: 'center'
            },
        ]);
        if (!checkAccess(['role@update','role@setAccess','role@delete'])){
            columns.value    =   columns.value.slice(0,-1)
        }
        const table = ref()
        const date = ref([])
        const queryParam = ref<QueryParams>({})
        const visible   =   ref<boolean>(false)
        const accessVisible   =   ref<boolean>(false)
        const confirmLoading   =   ref<boolean>(false)
        const info  =   ref({})
        const record    =   ref<{id:number,access?:number[] | string[]}>({
            id:0,
            access:[]
        })
        const loadData  =   (parameter?: any) => list(Object.assign(parameter, queryParam.value)).then(res=>{
            res.data.map((item:any,index:number)=>{
                item.key    =   index+1
            })
            return res
        })
        const onChangeSwitch    =   (checked: any,record:any) => {
            onSwitch({id:record.id}).then((res:any)=>{
                message.success(res.message)
                table.value.refresh()
            }).catch((e:any)=>message.success(e.message))
        }
        const onChange = (date: any, dateString: string[]) => {
            if (dateString[0] && dateString[1]){
                queryParam.value.time = dateString.join(' - ')
            }else {
                queryParam.value.time = ''
            }
            table.value.refresh(true)
        }
        const onReset = ()=>{
            queryParam.value = {}
            table.value.refresh(true)
        }

        const onEdit    =   (record:{id: number, name: string, remark: string, status: number})=>{
            visible.value = true
            info.value = record
        }

        const onDelete  =   (id:number)=>{
            Modal.confirm({
                title: '系统提示',
                content: '您确定要执行此操作吗？',
                okText: '确定',
                okType: 'danger',
                cancelText: '考虑一下',
                async onOk() {
                    return await new Promise<void>((resolve) => {
                        Delete({id}).then((res:any)=>{
                            message.success(res.message)
                            table.value.refresh()
                            resolve()
                        }).catch(()=>resolve())
                    });
                },
            });
        }

        const onCancel  =   ()=>{
            visible.value = false
            info.value = {}
        }

        const onSelectAccessOk  =   (keys: number[])=>{
            const access    =   toRaw(keys)
            if (access.length == 0){
                return message.error('请选择权限！')
            }
            confirmLoading.value = true
            saveAccess({id:record.value.id,access:keys.join(',')}).then((res:any)=>{
                message.success(res.message)
                accessVisible.value = false
                confirmLoading.value = false
            }).catch((e)=>{
                message.error(e.message)
                confirmLoading.value = false
            })
        }

        const openSetAccess = (data: {id: number,access: string})=>{
            let access:number[] = []
            if (data.access){
                data.access.split(',').map(val=>{
                    access.push(Number(val))
                })
            }
            record.value = {
                id: data.id,
                access
            }
            accessVisible.value = true
        }

        return {
            columns,
            loadData,
            onChangeSwitch,
            table,
            queryParam,
            onChange,
            date,
            onReset,
            onDelete,
            visible,
            confirmLoading,
            accessVisible,
            onEdit,
            info,
            onCancel,
            onSelectAccessOk,
            openSetAccess,
            record
        }
    }
}
</script>

<style scoped>

</style>