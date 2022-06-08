<template>
    <a-card>
        <s-table ref="table" :bordered="false" rowKey="id" :columns="columns" :data="loadData" :alert="options.alert" :rowSelection="options.rowSelection">
            <template #toolBarFilter>
                <a-space :size='10'>
                    <a-input allowClear placeholder="请输入关键字" :maxlength="50" v-model:value="queryParam.keyword"/>
                    <a-range-picker allowClear :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" v-model:value="date" @change="onChange"/>
                    <a-select allowClear :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" v-model:value="queryParam.status" placeholder="状态" style="width: 90px" @change="$refs.table.refresh(true)">
                        <a-select-option :value="0">禁用</a-select-option>
                        <a-select-option :value="1">启用</a-select-option>
                    </a-select>
                    <a-select allowClear :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" v-if="roles.length" v-model:value="queryParam.role_id" placeholder="角色" style="width: 120px" @change="$refs.table.refresh(true)">
                        <a-select-option v-for="item in roles" :value="item.id">{{item.name}}</a-select-option>
                    </a-select>
                    <a-button type="primary" @click="$refs.table.refresh(true)">搜索</a-button>
                    <a-button @click="onReset">重置</a-button>
                </a-space>
            </template>
            <template #toolbarAction>
                <a-button v-auth="'user@create'" type="primary" @click="visible = true">
                    <template #icon>
                        <PlusOutlined />
                    </template>
                    新增
                </a-button>
                <a-button v-auth="'user@delete'" type="primary" danger @click="batchDelete">
                    <template #icon>
                        <delete-outlined />
                    </template>
                    批量删除
                </a-button>
            </template>
            <template #bodyCell="{ column, record, text }">
                <template v-if="column.dataIndex === 'roleName'">
                    <a-tag v-for="(item,index) in text.split(',')" :key="index">{{ item }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'status'">
                    <template v-if="!checkAccess('user@update')">
                        <span v-if="record.status == 1" style="color: rgb(82, 196, 26)">启用</span>
                        <span v-else style="color: rgba(0, 0, 0, 0.45)">禁用</span>
                    </template>
                    <a-switch v-else :checked="record.status == 1 ? true : false" @change="onChangeSwitch($event,record)" checked-children="启用" un-checked-children="禁用" />
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <a-space>
                        <a v-auth="'user@update'" @click="onEdit(record)">编辑</a>
                        <a v-auth="'user@delete'" @click="onDelete(record.id)">删除</a>
                    </a-space>
                </template>
            </template>
        </s-table>
        <create :visible="visible" :roles="roles" @ok="$refs.table.refresh()" @cancel="onCancel" :data="info"/>
    </a-card>
</template>

<script>
import STable from '@/components/Table'
import {onMounted, ref} from "vue";
import {getAllRole, list, Delete, onSwitch} from "@/api/user";
import {message, Modal} from "ant-design-vue";
import {checkAccess} from '@/utils/util';
import create from "@/views/user/create";
export default {
    components:{
        STable,
        create
    },
    setup(){
        const table = ref()
        const columns = ref([
            {
                title: '序号',
                dataIndex: 'key',
                width: '80px',
                align: 'center'
            },
            {
                title: '账号',
                dataIndex: 'login_name',
            },
            {
                title: '角色',
                dataIndex: 'roleName',
            },
            {
                title: '昵称',
                dataIndex: 'nickname',
            },
            {
                title: '用户姓名',
                dataIndex: 'user_name',
                width: '200px',
                align: 'center'
            },
            {
                title: '手机号',
                dataIndex: 'mobile',
                width: '200px',
                align: 'center'
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
        const queryParam = ref({})
        const date = ref([])
        const roles = ref([])
        const visible = ref(false)
        const info  =   ref({})
        onMounted(()=>{
            getAllRole().then(({data})=>roles.value = data)
        })
        if (!checkAccess(['user@update','user@delete'])){
            columns.value    =   columns.value.slice(0,-1)
        }
        const onCancel  =   ()=>{
            visible.value = false
            info.value = {}
        }
        const loadData  =   (parameter) => list(Object.assign(parameter, queryParam.value)).then(res=>{
            res.data.map((item,index)=>{
                item.key    =   index+1
            })
            return res
        })
        const onChange = (date, dateString) => {
            if (dateString[0] && dateString[1]){
                queryParam.value.time = dateString.join(' - ')
            }else {
                queryParam.value.time = ''
            }
            table.value.refresh(true)
        }
        const onChangeSwitch    =   (checked,record) => {
            onSwitch({id:record.id}).then((res)=>{
                message.success(res.message)
                table.value.refresh()
            })
        }

        const onReset = ()=>{
            queryParam.value = {}
            table.value.refresh(true)
        }
        const onEdit    =   (record)=>{
            visible.value = true
            info.value = record
        }

        const selectedRowKeys   =   ref([])

        const onSelectChange = (selectedKeys)=>{
            selectedRowKeys.value = selectedKeys
        }

        const options   =   ref({
            alert: {
                show: true,
                clear: () => {
                    selectedRowKeys.value = []
                }
            },
            rowSelection: {
                selectedRowKeys: selectedRowKeys.value,
                onChange: onSelectChange
            }
        })

        const batchDelete   =   ()=>{
            if (selectedRowKeys.value.length == 0){
                return message.error('请选择要操作的数据！')
            }
            onDelete(selectedRowKeys.value)
        }

        const onDelete  =   (id)=>{
            Modal.confirm({
                title: '系统提示',
                content: '您确定要执行此操作吗？',
                okText: '确定',
                okType: 'danger',
                cancelText: '考虑一下',
                async onOk() {
                    return await new Promise((resolve) => {
                        Delete({id}).then((res)=>{
                            message.success(res.message)
                            table.value.refresh()
                            selectedRowKeys.value = []
                            resolve()
                        }).catch(()=>resolve())
                    });
                },
            });
        }
        return {
            columns,
            loadData,
            queryParam,
            date,
            onChange,
            table,
            onChangeSwitch,
            checkAccess,
            onReset,
            roles,
            visible,
            onCancel,
            info,
            onEdit,
            onDelete,
            batchDelete,
            onSelectChange,
            options
        }
    }
}
</script>

<style scoped>

</style>