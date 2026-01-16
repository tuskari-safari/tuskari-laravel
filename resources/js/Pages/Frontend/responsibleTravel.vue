<template>
    <Meta :cms="props.meta" />
    <Banner pageName="responsible-travel" />
    <div class="default-pg cmn-gap">
        <div class="container">
            <div class="dfult-content" v-html="cms?.text_content" v-if="cms?.text_content"></div>
            <div v-else class="text-center">No Content found</div>
        </div>
    </div>
</template>

<script setup>
import Banner from '@/components/Frontend/Banner.vue';
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import Meta from '../../components/Frontend/Meta.vue';

const props = defineProps({
    cms: Object,
    meta: Object
});

onMounted(() => {
    homeJs();
})
</script>