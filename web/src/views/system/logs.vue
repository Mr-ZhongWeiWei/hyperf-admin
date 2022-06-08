<template>
    <a-card>
        <s-table ref="table" :bordered="false" size="small" rowKey="id" :columns="columns" :data="loadData">
            <template #toolBarFilter>
                <a-space :size='10'>
                    <a-input allowClear placeholder="请输入关键字" v-model:value="queryParam.keyword"/>
                    <a-range-picker allowClear :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" v-model:value="date" @change="onChange"/>
                    <a-select allowClear :getPopupContainer="triggerNode => triggerNode.parentNode || document.body" v-model:value="queryParam.level" placeholder="日志级别" style="width: 130px" @change="$refs.table.refresh(true)">
                        <a-select-option v-for="(item,index) in level" :value="index">{{item}}</a-select-option>
                    </a-select>
                    <a-button type="primary" @click="$refs.table.refresh(true)">搜索</a-button>
                    <a-button @click="onReset">重置</a-button>
                </a-space>
            </template>
            <template #toolbarAction>
                <a-button v-auth="'logs@clear'" class="bg-orange" type="primary" @click="batchDelete(1)">
                    <template #icon>
                        <delete-outlined />
                    </template>
                    清空七天前日志
                </a-button>
                <a-button v-auth="'logs@clear'" type="primary" danger @click="batchDelete(2)">
                    <template #icon>
                        <delete-outlined />
                    </template>
                    清空日志
                </a-button>
            </template>
            <template #bodyCell="{ column, record, text }">
                <template v-if="column.dataIndex === 'context'">
                    <span>{{ text ? text : '--' }}</span>
                </template>
            </template>

        </s-table>
    </a-card>
</template>

<script>
import STable from '@/components/Table'
import {ref} from "vue";
import {list,clear} from "@/api/logs";
import {message, Modal} from "ant-design-vue";
export default {
    components:{
        STable,
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
                title: '日志信息',
                dataIndex: 'message',
                ellipsis: true,
            },
            {
                title: '日志上下文',
                dataIndex: 'context',
                ellipsis: true,
            },
            {
                title: '日志级别',
                dataIndex: 'level_name',
                width: '200px',
                align: 'center'
            },
            {
                title: '管道名称',
                dataIndex: 'channel',
                width: '200px',
                align: 'center'
            },

            {
                title: '写入时间',
                dataIndex: 'writetime',
                width: '200px',
                align:'center',
            },
        ]);
        const queryParam = ref({})
        const date = ref([])
        const level = ref([])

        const loadData  =   (parameter) => list(Object.assign(parameter, queryParam.value)).then(res=>{
            const { list, levels } = res.data
            level.value = levels
            list.map((item,index)=>{
                item.key    =   index+1
            })
            res.data = list
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

        const onReset = ()=>{
            queryParam.value = {}
            table.value.refresh(true)
        }


        const batchDelete   =   (type)=>{
            Modal.confirm({
                title: '系统提示',
                content: '您确定要执行此操作吗？',
                okText: '确定',
                okType: 'danger',
                cancelText: '考虑一下',
                async onOk() {
                    return await new Promise((resolve) => {
                        clear({type}).then((res)=>{
                            message.success(res.message)
                            table.value.refresh()
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
            onReset,
            batchDelete,
            level
        }
    }
}
</script>

<style scoped>

</style>