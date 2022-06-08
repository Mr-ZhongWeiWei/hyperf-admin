<template>
    <div>
        <a-form :model="formState" :rules="rules" ref="formRef" style="max-width: 500px; margin: 40px auto 0;">
            <a-form-item label="付款账户" :labelCol="labelCol" :wrapperCol="wrapperCol" name="paymentUser">
                <a-select v-model:value="formState.paymentUser" placeholder="ant-design@alipay.com">
                    <a-select-option value="1">ant-design@alipay.com</a-select-option>
                </a-select>
            </a-form-item>
            <a-form-item label="收款账户" :labelCol="labelCol" :wrapperCol="wrapperCol" name="payType">
                <a-input :style="{width: 'calc(100% - 100px)'}" v-model:value="formState.payType">
                    <template #addonBefore>
                        <a-select defaultValue="alipay" style="width: 100px">
                            <a-select-option value="alipay">支付宝</a-select-option>
                            <a-select-option value="wexinpay">微信</a-select-option>
                        </a-select>
                    </template>
                </a-input>
            </a-form-item>
            <a-form-item label="收款人姓名" :labelCol="labelCol" :wrapperCol="wrapperCol" name="name">
                <a-input v-model:value='formState.name' />
            </a-form-item>
            <a-form-item label="转账金额" :labelCol="labelCol" :wrapperCol="wrapperCol" name="money">
                <a-input prefix="￥" v-model:value="formState.money" />
            </a-form-item>
            <a-form-item :wrapperCol="{span: 19, offset: 5}">
                <a-button type="primary" @click="nextStep">下一步</a-button>
            </a-form-item>
        </a-form>
        <a-divider/>
        <div class="step-form-style-desc">
            <h3>说明</h3>
            <h4>转账到支付宝账户</h4>
            <p>如果需要，这里可以放一些关于产品的常见问题说明。如果需要，这里可以放一些关于产品的常见问题说明。如果需要，这里可以放一些关于产品的常见问题说明。</p>
            <h4>转账到银行卡</h4>
            <p>如果需要，这里可以放一些关于产品的常见问题说明。如果需要，这里可以放一些关于产品的常见问题说明。如果需要，这里可以放一些关于产品的常见问题说明。</p>
        </div>
    </div>
</template>

<script>

import {defineComponent, reactive, ref} from 'vue'

export default defineComponent({
    setup(prop,context){
        const formRef = ref();
        const formState = reactive({
            paymentUser: '',
            payType: 'test@example.com',
            name: 'Alex',
            momey:'5000'
        });
        const labelCol  =   ref({})
        const wrapperCol=   ref({})
        labelCol.value  =   {lg: {span: 5}, sm: {span: 5}}
        wrapperCol.value=   {lg: {span: 19}, sm: {span: 19}}

        const rules = {
            paymentUser: [
                {
                    required: true,
                    message: '付款账户必须填写！',
                    trigger: 'blur',
                },
            ],
            payType: [
                {
                    required: true,
                    message: '收款账户必须填写！',
                    trigger: 'blur',
                },
            ],
            name: [
                {
                    required: true,
                    message: '收款人名称必须核对！',
                    trigger: 'blur',
                },
            ],
            money: [
                {
                    required: true,
                    message: '转账金额必须填写！',
                    trigger: 'blur',
                },
            ],
        };

        const nextStep  =   ()=>{
            formRef.value.validate().then(()=>{
                context.emit('nextStep')
            }).catch(() => {
                return false
            })
        }

        return { labelCol, wrapperCol, nextStep, formState, formRef, rules }
    },
})
</script>

<style lang="less" scoped>
.step-form-style-desc {
    padding: 0 56px;
    color: rgba(0, 0, 0, .45);

    h3 {
        margin: 0 0 12px;
        color: rgba(0, 0, 0, .45);
        font-size: 16px;
        line-height: 32px;
    }

    h4 {
        margin: 0 0 4px;
        color: rgba(0, 0, 0, .45);
        font-size: 14px;
        line-height: 22px;
    }

    p {
        margin-top: 0;
        margin-bottom: 12px;
        line-height: 22px;
    }
}
</style>
