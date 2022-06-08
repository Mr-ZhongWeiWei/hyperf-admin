<template>
    <a-card :bordered="false">
        <a-steps class="steps" :current="currentTab">
            <a-step title="填写转账信息"/>
            <a-step title="确认转账信息"/>
            <a-step title="完成"/>
        </a-steps>
        <div class="content">
            <step1 v-if="currentTab === 0" @nextStep="nextStep"/>
            <step2 v-if="currentTab === 1" @nextStep="nextStep" @prevStep="prevStep"/>
            <step3 v-if="currentTab === 2" @prevStep="prevStep" @finish="finish"/>
        </div>
    </a-card>
</template>

<script>
import Step1 from './Step1'
import Step2 from './Step2'
import Step3 from './Step3'
import {defineComponent, ref } from 'vue'

export default defineComponent({
    components: {
        Step1,
        Step2,
        Step3
    },

    setup(){
        const currentTab = ref(0)

        const nextStep = ()=>{
            if (currentTab.value < 2) {
                currentTab.value += 1
            }
        }

        const prevStep = ()=>{
            if (currentTab.value > 0) {
                currentTab.value -= 1
            }
        }

        const finish = ()=>{
            currentTab.value = 0
        }

        return { currentTab, nextStep, prevStep, finish}
    }
})
</script>

<style lang="less" scoped>
.steps {
    max-width: 750px;
    margin: 16px auto;
}
</style>
