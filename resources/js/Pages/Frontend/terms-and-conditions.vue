<template>
    <Banner pageName="Story" />
    <div class="default-pg cmn-gap">
        <div class="container">
        <div class="dfult-content" v-html="cms?.text_content"></div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import Banner from '@/components/Frontend/Banner.vue';

const props = defineProps({
    cms: Object
});

onMounted(() => {
    homeJs();
})

</script>