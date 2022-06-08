<template>
    <div>
        <a-form :model="formState" :rules="rules" ref="formRef" style="max-width: 500px; margin: 40px auto 0;">
            <a-alert :closable="true" message="确认转账后，资金将直接打入对方账户，无法退回。" style="margin-bottom: 24px;"/>
            <a-form-item label="付款账户" :labelCol="labelCol" :wrapperCol="wrapperCol" class="stepFormText">
                ant-design@alipay.com
            </a-form-item>
            <a-form-item label="收款账户" :labelCol="labelCol" :wrapperCol="wrapperCol" class="stepFormText">
                test@example.com
            </a-form-item>
            <a-form-item label="收款人姓名" :labelCol="labelCol" :wrapperCol="wrapperCol" class="stepFormText">
                Alex
            </a-form-item>
            <a-form-item label="转账金额" :labelCol="labelCol" :wrapperCol="wrapperCol" class="stepFormText">
                ￥ 5,000.00
            </a-form-item>
            <a-divider/>
            <a-form-item label="支付密码" :labelCol="labelCol" :wrapperCol="wrapperCol" class="stepFormText" name="paymentPassword">
                <a-input-password style="width: 80%;" v-model:value="formState.paymentPassword"/>
            </a-form-item>
            <a-form-item :wrapperCol="{span: 19, offset: 5}">
                <a-button :loading="loading" type="primary" @click="nextStep">提交</a-button>
                <a-button style="margin-left: 8px" @click="prevStep">上一步</a-button>
            </a-form-item>
        </a-form>
    </div>
</template>

<script>
import {defineComponent, reactive, ref} from 'vue'
export default defineComponent({
    setup(prop,context){
        const formRef = ref();
        const formState = reactive({
            paymentPassword: '123456',
        });

        const labelCol  =   ref({lg: {span: 5}, sm: {span: 5}})
        const wrapperCol=   ref({lg: {span: 19}, sm: {span: 19}})
        const loading   =   ref(false)
        const timer     =   ref(0)

        const rules = {
            paymentPassword: [
                {
                    required: true,
                    message: '请输入支付密码！',
                    trigger: 'blur',
                },
            ],
        };
        const nextStep  =   () => {
            formRef.value.validate().then(()=>{
                context.emit('nextStep')
            }).catch(() => {
                return false
            })
        }

        const prevStep  =   () => {
            context.emit('prevStep')
        }

        return { formRef, formState, labelCol, wrapperCol, loading, timer, nextStep, prevStep, rules}
    },
})
</script>

<style lang="less" scoped>
.stepFormText {
    margin-bottom: 24px;

    .ant-form-item-label,
    .ant-form-item-control {
        line-height: 22px;
    }
}

</style>
