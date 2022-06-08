<template>
    <a-card :body-style="{padding: '24px 32px'}" :bordered="false">
        <a-form :model="formState">
            <a-form-item label="标题" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }">
                <a-input v-model:value="formState.name" placeholder="请输入标题" />
            </a-form-item>
            <a-form-item label="起始日期" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }">
                <a-range-picker v-model:value="formState.date1"/>
            </a-form-item>
            <a-form-item label="目标描述" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }">
                <a-textarea rows="4" placeholder="描述"/>
            </a-form-item>
            <a-form-item label="衡量标准" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }">
                <a-textarea rows="4" placeholder="请输入"/>
            </a-form-item>
            <a-form-item label="客户" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }">
                <a-input placeholder="请输入"/>
            </a-form-item>
            <a-form-item label="邀评人" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }" :required="false">
                <a-input placeholder="请输入" />
            </a-form-item>
            <a-form-item label="权重" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }" :required="false">
                <a-input-number :min="0" :max="100" />
                <span> %</span>
            </a-form-item>
            <a-form-item label="目标公开" :labelCol="{lg: {span: 7}, sm: {span: 7}}" :wrapperCol="{lg: {span: 10}, sm: {span: 17} }" :required="false" help="客户、邀评人默认被分享">
                <a-radio-group v-model:value="formState.redio">
                    <a-radio :value="1">公开</a-radio>
                    <a-radio :value="2">部分公开</a-radio>
                    <a-radio :value="3">不公开</a-radio>
                </a-radio-group>
            </a-form-item>
            <a-form-item :wrapperCol="{ span: 24 }" style="text-align: center">
                <a-button htmlType="submit" type="primary">提交</a-button>
                <a-button style="margin-left: 8px">保存</a-button>
            </a-form-item>
        </a-form>
    </a-card>
</template>

<script>
import { defineComponent, reactive, toRaw } from 'vue';

export default defineComponent({
    setup() {
        const formState = reactive({
            name: '',
            region: undefined,
            date1: undefined,
            delivery: false,
            type: [],
            resource: '',
            desc: '',
            redio:undefined
        });
        const onSubmit = () => {
            console.log('submit!', toRaw(formState));
        };
        return {
            labelCol: { span: 4 },
            wrapperCol: { span: 14 },
            formState,
            onSubmit,
        };
    },
});
</script>
