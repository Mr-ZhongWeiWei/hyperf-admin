<template>
    <div class="clearfix">
        <a-upload
            v-model:file-list="fileList"
            :action="action"
            list-type="picture-card"
            @preview="handlePreview"
            :max-count="maxCount"
            @change="onChange"
        >
            <div v-if="fileList.length < maxCount">
                <plus-outlined />
                <div style="margin-top: 8px">上传</div>
            </div>
        </a-upload>
        <a-image style="display: none" v-if="visible" :preview="{ visible, onVisibleChange: vis => {
            visible = vis;
            previewUrl = ''
        } }" :src="previewUrl"/>
    </div>
</template>
<script lang="ts">
import { PlusOutlined } from '@ant-design/icons-vue';
import {defineComponent, onMounted, ref, toRaw} from 'vue';
import type {UploadChangeParam, UploadProps } from 'ant-design-vue';
import { REQUEST_SUCCESS_CODE } from '@/store/mutation-types';
import {message,Image as AImage,ImagePreviewGroup as AImagePreviewGroup} from "ant-design-vue";

export default defineComponent({
    components: {
        PlusOutlined,
        AImagePreviewGroup,
        AImage
    },
    props:{
        value: {
            type: Array,
            default: ()=>[]
        },
        maxCount: {
            type: Number,
            default: 1
        }
    },
    emits:['update:value'],
    setup(props,context) {
        const action = ref(process.env.VUE_APP_API_BASE_URL+'/upload');
        const fileList = ref<UploadProps['fileList']>([]);
        const visible   =   ref(false)
        const previewUrl   =   ref('')

        onMounted(()=>{
            (fileList as any).value  =   toRaw(props.value)
        })

        const onChange  =   (info: UploadChangeParam)=>{
            const {file} = info
            if (file.response){
                if (file.response.code == REQUEST_SUCCESS_CODE){
                    const item  =   {
                        uid: file.uid,
                        name: file.name,
                        status: file.status,
                        url: file.response.data
                    }
                    fileList.value?.splice(-1, 1)
                    fileList.value?.push(item)
                    context.emit("update:value",fileList.value)
                    message.success(file.response.message)
                }else {
                    message.error(file.response.message)
                }
            }
        }

        const handlePreview = (file:any)=>{
            previewUrl.value = file.url
            visible.value = true
        }

        return {
            fileList,
            action,
            onChange,
            handlePreview,
            visible,
            previewUrl
        };
    },
});
</script>
<style>
.ant-upload-select-picture-card i {
    font-size: 32px;
    color: #999;
}

.ant-upload-select-picture-card .ant-upload-text {
    margin-top: 8px;
    color: #666;
}
</style>
