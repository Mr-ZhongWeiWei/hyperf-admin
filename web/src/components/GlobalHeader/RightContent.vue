<template>
    <div :class="wrpCls" id="right-content">
        <ul>
            <li>
                <a-tooltip placement="bottom">
                    <template #title>
                        <span>设置</span>
                    </template>
                    <SettingOutlined :style="{color: color()}" @click="()=>{$emit('toggle')}" class="font16"/>
                </a-tooltip>
            </li>
            <li>
                <a-dropdown placement="bottomRight">
                    <div class="ant-pro-account-avatar">
                        <template v-if="$store.getters.userInfo.photo">
                            <a-avatar size="small" :src="$store.getters.userInfo.photo" class="antd-pro-global-header-index-avatar"/>
                        </template>
                        <template v-else>
                            <a-avatar size="small" v-if="$store.getters.userInfo.sex == '1' || $store.getters.userInfo.sex == '2'" :src="$store.getters.userInfo.sex == '1' ? male : female" class="antd-pro-global-header-index-avatar"/>
                            <a-avatar size="small" v-else class="antd-pro-global-header-index-avatar" style="color: #314659">
                                <template #icon><UserOutlined /></template>
                            </a-avatar>
                        </template>
<!--                      <span :style="{color: color(),'fontSize':'14px'}">{{ $store.getters.userInfo.nickname }}</span>-->
                    </div>
                    <template #overlay>
                        <a-menu class="ant-pro-drop-down menu">
                            <a-menu-item key="settings" @click="visible = true">
                                个人设置
                            </a-menu-item>
                            <a-menu-item key="editpassword" @click="editVisible = true">
                                修改密码
                            </a-menu-item>
                            <a-menu-item key="logout" @click="handleLogout">
                                退出登录
                            </a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>
            </li>
        </ul>
        <PersonalSettings :visible="visible" @cancel="visible = false" @ok="onOk" :data="$store.getters.userInfo"/>
        <EditPassword :visible="editVisible" @cancel="editVisible = false"/>
    </div>
</template>

<script>
import {createVNode, ref, defineComponent} from "vue";
import {ExclamationCircleOutlined} from "@ant-design/icons-vue";
import {mixin} from "../../utils/mixin";
import PersonalSettings from "@/views/user/PersonalSettings";
import EditPassword from "@/views/user/EditPassword";
import male from "@/assets/sex-1.png"
import female from "@/assets/sex-2.png"
import unknown from "@/assets/sex-3.jpg"

export default defineComponent({
    name: 'RightContent',
    emits:['toggle'],
    mixins: [mixin],
    components:{
        PersonalSettings,
        EditPassword,
    },
    setup() {
        const visible = ref(false)
        const editVisible = ref(false)
        return {visible,editVisible}
    },
    data(){
      return {
          male,
          female,
          unknown,
      }
    },
    computed: {
        wrpCls() {
            return {
                'ant-pro-global-header-index-right': true,
                [`ant-pro-global-header-index-light`]: true
            }
        },

    },
    methods: {
        onOk(){
            this.$store.dispatch('GetInfo')
        },
        handleLogout() {
            this.$confirm({
                title: '信息',
                icon: createVNode(ExclamationCircleOutlined),
                content: '您确定要注销吗？',
                onOk: () => {
                    return this.$store.dispatch('Logout').then(() => {
                        this.$router.push({name: 'login'})
                    })
                },
                onCancel() {
                }
            })
        },
        color() {
            return (this.layoutMode !== 'sidemenu' && this.navTheme === 'dark') ? '#fff' : '#262626'
        }
    }
})
</script>
<style scoped>
#right-content {
    float: right;
    cursor: pointer
}
.font16 { font-size: 16px}

#right-content li {
    display: inline-block;
    margin: 0 15px;
    font-size: 16px;
}
</style>
