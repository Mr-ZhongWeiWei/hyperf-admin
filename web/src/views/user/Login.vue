<template>
    <div class="main">
        <a-form :model="formState" :rules="rules" class="user-layout-login" ref="formRef">
            <a-form-item name="login_name">
                <a-input size="large" autocomplete="off" v-model:value="formState.login_name" type="text" placeholder="账号">
                    <template #prefix>
                        <user-outlined type="user" :style="{ color: 'rgba(0,0,0,.25)' }"/>
                    </template>
                </a-input>
            </a-form-item>

            <a-form-item name="password">
                <a-input-password size="large" autocomplete="off" v-model:value="formState.password" placeholder="密码" type="password">
                    <template #prefix>
                        <LockOutlined :style="{ color: 'rgba(0,0,0,.25)' }"/>
                    </template>
                </a-input-password>
            </a-form-item>

            <a-form-item>
                <a-button block size="large" type="primary" html-type="submit" :loading="loading" @click="onSubmit">登录</a-button>
            </a-form-item>
        </a-form>

    </div>
</template>

<script lang="ts">
import { UserOutlined, LockOutlined } from '@ant-design/icons-vue';
import { defineComponent, reactive, ref } from 'vue';
import store from '@/store'
import { useRouter } from 'vue-router'
import { message } from "ant-design-vue";

export default defineComponent({
    setup() {
        const formRef = ref();
        const formState = reactive({
            login_name: '',
            password: '',
        });
        const rules = {
            login_name: [
                {
                    required: true,
                    message: '请输入账号！',
                    trigger: 'blur',
                },
                {
                    min: 3,
                    max: 16,
                    message: '请输入长度为3~16的账号！',
                    trigger: 'blur',
                },
            ],
            password: [
                {
                    required: true,
                    message: '请输入账号密码！',
                    trigger: 'blur',
                },
                {
                    min: 6,
                    max: 12,
                    message: '请输入长度为6~12的账号密码！',
                    trigger: 'blur',
                },
            ]
        };
        const loading = ref();

        const router    =   useRouter()
        const onSubmit = () => {
            formRef.value.validate().then(() => {
                const loginParams = { ...formState }
                loading.value = true;
                store.dispatch('Login', loginParams).then((e) => {
                    loading.value = false;
                    router.push("/");
                    message.success(e.message);
                }).catch((e)=>{
                    loading.value = false;
                })
            }).catch(() => {
                loading.value = false;
            });
        };

        return {formState, rules, onSubmit, formRef, loading, store,};
    },
    components: {
        UserOutlined,
        LockOutlined
    },
});

</script>

<style lang="less" scoped>
.user-layout-login {
    label {
        font-size: 14px;
    }

    .getCaptcha {
        display: block;
        width: 100%;
        height: 40px;
    }

    .forge-password {
        font-size: 14px;
    }

    button.login-button {
        padding: 0 15px;
        font-size: 16px;
        height: 40px;
        width: 100%;
    }

    .user-login-other {
        text-align: left;
        margin-top: 24px;
        line-height: 22px;

        .item-icon {
            font-size: 24px;
            color: rgba(0, 0, 0, 0.2);
            margin-left: 16px;
            vertical-align: middle;
            cursor: pointer;
            transition: color 0.3s;

            &:hover {
                color: #1890ff;
            }
        }

        .register {
            float: right;
        }
    }
}
</style>
