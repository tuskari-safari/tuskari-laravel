<template lang="">
<!-- <loading v-model:active="isLoading" :can-cancel="true" :is-full-page="true" /> -->
        <div class="dashboard_frame" v-bind="$attrs">
                <Nav/>
            <div class="dashboard_right_panel">
                <Header/>
                <slot />
            </div>
            
            <ScrollToggle v-if="!isNoScroll" />
        </div>
        <div class="srch_ovrlay">
            <div class="ovrly_srch_hldr">
                <div class="hder_srch_box">
                    <input type="text" placeholder="Search">
                    <button class="srch_flter sf-filterbutn" data-target="popup1"><img :src="'frontend_assets/images/sttngs_icon.svg'" alt=""></button>
                </div>
                <a href="javascript:void(0)" class="srch_close_bttn"><img :src="'frontend_assets/images/close.svg'" alt=""></a>
            </div>
        </div>

       <!--log out filter popup-->
        
        
</template>
<script setup>
import Nav from "../Auth/Nav.vue";
import Header from "../Auth/Header.vue";
import Loading from "vue-loading-overlay";
import "vue-loading-overlay/dist/css/index.css";
import { ref, onMounted, onBeforeUnmount,computed } from 'vue'
import { usePage, router } from "@inertiajs/vue3";
import ScrollToggle from "@/components/Frontend/ScrollToggle.vue";
const isLoading = ref(false);

const isNoScroll = ref(false)
let observer = null

onMounted(() => {
    isNoScroll.value = document.body.classList.contains('no-scroll')

    observer = new MutationObserver(() => {
        isNoScroll.value = document.body.classList.contains('no-scroll')
    });

    observer.observe(document.body, {
        attributes: true,
        attributeFilter: ['class'],
    });
})

onBeforeUnmount(() => {
    if (observer) observer.disconnect()
});

router.on("start", () => {
    isLoading.value = true;
});

router.on("finish", () => {
    isLoading.value = false;
});

const flash = computed(() => {
    return usePage().props.flash;
});

const props = defineProps(["flash"]);
</script>

<style>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/brands.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/fontawesome.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/regular.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/solid.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css";
@import "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css";
@import "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css";
@import "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css";
@import "https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css";
@import "../../../../../public/frontend_assets/style.css";
</style>