<template>
    <a-card class="h-100">
        <a-alert
            message="提示说明"
            type="info"
            show-icon
            style="margin-bottom: 10px"
            v-if="isDev"
        >
            <template #description>
                <p>1、当前参数管理扩展了框架自身的config组件（增加了数据库管理驱动），获取配置使用方法与框架保持一致 例如：config('sso.is_sso') 也可以 config('sso')返回数组。</p>
                <p>2、参数分组根据需求可自行在代码中增加或隐藏，关闭开发模式后会隐藏当前提示、新增参数、编辑、删除操作。</p>
            </template>
        </a-alert>
        <a-tabs v-model:activeKey="activeKey" @change="init">
            <a-tab-pane v-for="item in group" :key="item.key" :tab="item.name"/>
            <template #rightExtra>
                <a-space>
                    <a-button type="primary" v-auth="'config@save'" @click="onRelease" :loading="confirmLoading"><form-outlined />保存设置</a-button>
                    <a-button type="primary" v-if="isDev" @click="visible = true"><plus-outlined />新增参数</a-button>
                </a-space>
            </template>
        </a-tabs>
        <a-form ref="formRef">
            <template v-for="item in configs">
                <template v-if="item.type == 'line'">
                    <a-divider orientation="left" :orientationMargin="20"><span class="line">{{item.label}}</span></a-divider>
                </template>
                <a-form-item v-else :label="item.label" :labelCol="labelCol" :wrapperCol="wrapperCol" :extra="item.extra">
                    <a-input-number v-if="item.type == 'number'" v-model:value="item.value" placeholder="请输入"/>
                    <a-input v-if="item.type == 'text'" v-model:value="item.value" placeholder="请输入" show-count :maxlength="255"/>
                    <a-textarea v-if="item.type == 'textarea'" v-model:value="item.value" placeholder="请输入" :rows="4" />
                    <a-checkbox-group v-if="item.type == 'checkbox'" v-model:value="item.value" :options="item.data" />
                    <a-radio-group v-if="item.type == 'radio'" v-model:value="item.value" :options="item.data" />
                    <a-switch v-if="item.type == 'switch'" v-model:checked="item.value" />
                    <a-select v-if="item.type == 'select'" v-model:value="item.value" placeholder="请选择"  :options="item.data"/>
                    <template v-if="item.type == 'singleimg'">
                        <upload-image v-model:value="item.value"/>
                    </template>
                    <template v-if="item.type == 'multipleimg'">
                        <upload-image v-model:value="item.value" :max-count="item.data"/>
                    </template>
                    <a-space class="action" v-if="isDev">
                        <a-button type="link" @click="visible = true;info = item;">编辑</a-button>
                        <a-button type="link" @click="onDelete(item.id)">删除</a-button>
                    </a-space>
                </a-form-item>
            </template>
        </a-form>
        <create :visible="visible" @cancel="onCancel" @ok="init(activeKey)" :group="group" :data="info"/>
    </a-card>
</template>

<script>
import {defineComponent, ref, watch} from 'vue';
import create from "@/views/system/config/create.vue";
import {list,del,release} from "@/api/configs";
import {message, Modal} from "ant-design-vue";
import uploadImage from "./uploadImage";
import {useRoute} from "vue-router";
export default defineComponent({
    components:{
        create,
        uploadImage
    },
    setup() {
        const route =   useRoute()
        const visible = ref(false)
        const info  =   ref({})
        const group =   ref([])
        const configs  =   ref([])
        const activeKey = ref(null);
        const isDev =   ref(false)
        const confirmLoading = ref(false);
        const init  =   (group_id)=>{
            list({group_id,...route.query}).then(({data})=>{
                group.value =   data.group
                if (activeKey.value === null){
                    activeKey.value =   group.value[0].key
                }
                data.configs.map(item=>{
                    if (item.type == 'select' && !item.value){
                        item.value  =   undefined
                    }
                })
                isDev.value = data.is_dev
                configs.value   =   data.configs
            })
        }
        init()
        const onCancel  =   ()=>{
            visible.value = false
            info.value = {}
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
                        del({id}).then((res)=>{
                            message.success(res.message)
                            init(activeKey.value)
                            resolve()
                        }).catch(()=>resolve())
                    });
                },
            });
        }

        const onRelease =   ()=>{
            confirmLoading.value = true
            let params =   {}
            configs.value.map(item=>{
                params[item.name] = item.value
            })
            release({configs:params}).then(res=>{
                confirmLoading.value    =   false
                message.success(res.message)
            }).catch(()=>confirmLoading.value = false)
        }

        return {
            activeKey,
            labelCol: {span: 4},
            wrapperCol: {span:10},
            onCancel,
            visible,
            info,
            group,
            configs,
            init,
            onDelete,
            isDev,
            onRelease,
            confirmLoading
        };
    },
});
</script>

<style scoped lang="less">
.line {
    border-left: 4px solid #1890ff;
    padding-left: 10px
}
.ant-form-item-control-input-content{
    position: relative;
    .action {
        position: absolute;
        right: -200px;
    }
}
</style>