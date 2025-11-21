<template lang="">
    <div>
        <input type="file" v-bind="$attrs" @change="onFileChange" class="form-control border-gray-200" />
        <div v-if="fileType == 'application'">
            <output v-if="previewUrl" class="mt-3">  
                <!-- <a :href="previewUrl" title="download pdf" download="yoga.pdf">
                    <i class="fa fa-file-pdf fa-2x text-danger"> </i>
                </a> -->
                <object class="pdf" 
                    :data="previewUrl"
                    width="800"
                    height="300">
                </object>
            </output>
        </div>
        <div v-if="backendMsg != ''">
            <p class="text-danger">{{ backendMsg }}</p>
        </div>        
    </div> 
</template>
<script setup>
import { onMounted, ref } from "vue";
const previewUrl = ref('');
const fileType = ref('');
const backendMsg = ref('');

const props = defineProps(['fileUrl']);
setTimeout(() => {
    previewUrl.value = props.fileUrl;
}, "100");

onMounted(() => {
    emit.on('documentUploadMsg', function (error) {
        backendMsg.value = error;
    });
    setTimeout(() => {
        if (props.fileUrl != "") {
            fileType.value = 'application';
        }
    }, "100");
})

function onFileChange(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Reset preview
    previewUrl.value = '';
    backendMsg.value = '';

    if (file.type.match('application/pdf')) {
        fileType.value = 'application';
        previewUrl.value = URL.createObjectURL(file); // Use object URL
    } else if (file.type.match('audio.*') || file.type.match('image.*') || file.type.match('video.*')) {
        fileType.value = file.type.split('/')[0];
        backendMsg.value = "File not supported. Please upload a valid PDF file.";
    } else {
        backendMsg.value = "Unsupported file type.";
    }
}

</script>
<style scoped>
.pdf {
        width: 100%;
        aspect-ratio: 4 / 3;
    }
    </style>

