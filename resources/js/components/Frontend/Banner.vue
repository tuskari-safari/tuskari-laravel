<template>
    <div class="bnrsec inerbnrsec">
        <!-- Show loader -->
        <SpinnerOverlay v-if="isLoading" />

        <!-- Show banner -->
        <template v-else-if="bannerData">
            <img :src="bannerData.full_image" alt="bannerimg" class="bnrimg" />
            <div class="container">
                <div class="bnrcontent">
                    <div v-html="bannerData.title ?? 'NA'"></div>
                    <p>{{ bannerData.short_description ?? 'NA' }}</p>
                </div>
            </div>
        </template>

        <template v-else>
            <img :src="'/frontend_assets/images/safari-dtl-banner.jpg'" alt="bannerimg" class="bnrimg" />
        </template>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import service from "@/helpers/service";
import SpinnerOverlay from "@/components/Frontend/Loader.vue";

const props = defineProps({
    pageName: String,
});

const bannerData = ref(null);
const isLoading = ref(false);

watch(() => props.pageName,
    async (newVal) => {
        try {
            isLoading.value = true;
            bannerData.value = null;
            const response = await service.getData(route("frontend.get-banner", { pageName: newVal }));
            bannerData.value = response && Object.keys(response).length > 0 ? response : null;
        } catch (error) {
            console.error("Failed to fetch banner data:", error);
            bannerData.value = null;
        } finally {
            isLoading.value = false;
        }
    }, { immediate: true }
);
</script>
