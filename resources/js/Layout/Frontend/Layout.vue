<template>
    <div :class="[layoutWrapperClass, { 'safaridtls-mainwrpr': isSafariDetails }]" v-bind="$attrs">
        <Header />
        <slot />
        <ScrollToggle />
        <Footer />
    </div>
</template>

<script setup>
import Footer from "./Footer.vue";
import Header from "./Header.vue";
import ScrollToggle from "../../components/Frontend/ScrollToggle.vue";

import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const page = usePage();

const currentComponent = computed(() => page.component);
const currentUrl = computed(() => page.url);

const pageName = [
    'Frontend/log-in',
    'Frontend/register',
    'Frontend/forgot-password',
    'Frontend/otp-verification',
    'Frontend/reset-password',
    'Frontend/safari-operator-register-one',
    'Frontend/safari-operator-register-two',
];

const isLoginOrRegister = computed(() =>
    pageName.includes(currentComponent.value)
);

const isSafariDetails = computed(() =>
    currentComponent.value.startsWith("Frontend/safari-details")
);

const layoutWrapperClass = computed(() => {
    if (isLoginOrRegister.value) {
        return "main_wrppr_alt";
    } else if (currentUrl.value.includes("/blog")) {
        return "main-wrapr blog-listing-wrppr";
    } else {
        return "main-wrapr";
    }
});
</script>

<style>
@import "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css";
@import "https://cdnjs.cloudflare.com/ajax/libs/ion-rangeslider/2.3.1/css/ion.rangeSlider.min.css";
@import "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css";
@import "https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css";
@import "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css";
@import "https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css";
@import "../../../../public/frontend_assets/style.css";
</style>
