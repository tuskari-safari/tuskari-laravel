<template>
    <header class="main-head" :class="loginRegisterClass">
        <div class="container ful-container">
            <nav class="navbar navbar-expand-lg">
                <a class="navbar-brand" :href="route('frontend.index')">
                    <img :src="logo" alt="logo"
                        :style="loginRegisterClass ? 'filter: brightness(0) saturate(100%) invert(30%) sepia(13%) saturate(545%) hue-rotate(71deg) brightness(96%) contrast(86%);' : ''">
                </a>

                <button class="navbar-toggler navbar-toggler-main outer-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <!-- <span class="navbar-toggler-icon"></span> -->
                    <span class="stick"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <button class="navbar-toggler navbar-toggler-main" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <!-- <span class="navbar-toggler-icon"></span> -->
                        <span class="stick"></span>
                    </button>
                    <!-- mega menu -->

                    <ul class="navbar-nav ms-auto">
                        <!-- <li class="current-menu-item"><a href="#url">Individuals</a></li> -->
                        <li class="menu-item-has-children first-child">
                            <span class="click-li">Find Your Safari</span>

                            <div class="megha-menu">
                                <ul class="sub-menu flex">
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0)">Top Countries</a>
                                        <div class="inr-nav-flex">
                                            <ul class="sub-menu">
                                                <li v-for="country in countries" :key="country.id">
                                                    <a :href="route('frontend.safari-listings', { 'location[name]': country?.name, 'location[type]': 'country' })"
                                                        :class="{
                                                            'current-menu-item-text': $page.url.includes('location[name]=' + encodeURIComponent(country?.name))
                                                                && $page.url.includes('location[type]=country')
                                                        }">
                                                        {{ country.name ?? 'NA' }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="down-all">
                                                <a :href="route('frontend.country-guides')">View All Countries</a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0)">Top National Parks</a>
                                        <div class="inr-nav-flex">
                                            <ul class="sub-menu">
                                                <li v-for="park in nationalParks" :key="park.id">
                                                    <a :href="route('frontend.safari-listings', { 'location[name]': park?.name, 'location[type]': 'park' })"
                                                        :class="{
                                                            'current-menu-item-text': $page.url.includes('location[name]=' + encodeURIComponent(park?.name))
                                                                && $page.url.includes('location[type]=park')
                                                        }">
                                                        {{ park.name ?? 'NA' }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="down-all">
                                                <a
                                                    :href="route('frontend.national-park-reserves')">
                                                    View All National Parks
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0)">Safari Types</a>
                                        <div class="inr-nav-flex">
                                            <ul class="sub-menu">
                                                <li v-for="type in types" :key="type.id">
                                                    <a :href="route('frontend.safari-listings') + '?safariTypeIds[]=' + type.id"
                                                        :class="{
                                                            'current-menu-item-text': $page.url.includes('safariTypeIds%5B0%5D=' + type.id)
                                                        }">
                                                        {{ type.title ?? 'NA' }}
                                                    </a>
                                                </li>
                                            </ul>
                                            <div class="down-all">
                                                <a :href="route('frontend.safari-types')">View All Safari Types ​</a>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="menu-item-has-children">
                            <span class="click-li">Travel Insights</span>
                            <div class="megha-menu small">
                                <ul class="sub-menu single-menu">
                                    <li v-for="blogCategory in blogCategories"><a
                                            :href="route('frontend.blogs.category', { category: blogCategory.title })">{{
                                                blogCategory.title }}</a></li>
                                </ul>
                            </div>
                        </li>
                        <!--  -->
                        <li class="menu-item-has-children">
                            <span class="click-li">Why Tuskari</span>
                            <div class="megha-menu small">
                                <ul class="sub-menu single-menu">
                                    <li><a :href="route('frontend.our-story')">Our Story</a></li>
                                    <li><a :href="route('frontend.how-it-works')">How It Works</a></li>
                                    <li><a :href="route('frontend.why-its-different')">Why It’s Different</a></li>
                                    <li><a :href="route('frontend.responsible-travel')">Responsible Travel</a></li>
                                    <li><a :href="route('frontend.faqs')">FAQs</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                    <!-- megha menu -->
                    <div class="hdbutn-mb">
                        <a :href="$page?.props.auth.user?.role == 'TRAVELLER' ? route('frontend.traveller-profile') : route('frontend.operator-my-profile')"
                            class="cmn-butn fsthdbutn" v-if="$page?.props.isLogin">My Tuskari</a>
                        <a :href="route('frontend.login')" class="cmn-butn wht-butn" v-else>Join today</a>
                    </div>
                </div>

                <div class="hderrgt-pnl hdbutn-dsktp">
                    <a :href="$page?.props.auth.user?.role == 'TRAVELLER' ? route('frontend.traveller-profile') : route('frontend.operator-my-profile')"
                        class="cmn-butn fsthdbutn" v-if="$page?.props.isLogin">My Tuskari</a>
                    <a :href="route('frontend.login')" class="cmn-butn wht-butn" v-else>Join today</a>
                </div>

            </nav>
        </div>

        <button class="navbar-toggler" id="navoverlay" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation"></button>
    </header>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const page = usePage();
const currentComponent = computed(() => page.component);

const countries = ref([]);
const nationalParks = ref([]);
const types = ref([]);
const blogCategories = ref([]);

const pageName = [
    'Frontend/log-in',
    'Frontend/register',
    'Frontend/forgot-password',
    'Frontend/otp-verification',
    'Frontend/reset-password',
    'Frontend/safari-operator-register-one',
    'Frontend/safari-operator-register-two',
];

const loginRegisterClass = computed(() =>
    pageName.includes(currentComponent.value) ? 'dashboard_header' : ''
);

const logo = computed(() =>
    pageName.includes(currentComponent.value) ? '/frontend_assets/Final Tuskari Logo Black.svg' : '/frontend_assets/Final Tuskari Logo White.svg'
);

onMounted(async () => {
    getCounties();
    getNationalParks();
    getTypes();
    getBlogCategories();
});

const getCounties = async () => {
    try {
        let response = await axios.get('/header-countries')
        countries.value = response.data
    } catch (error) {
        console.error("Error fetching countries:", error)
    }
};

const getNationalParks = async () => {
    try {
        let response = await axios.get('/header-national-parks')
        nationalParks.value = response.data
    } catch (error) {
        console.error("Error fetching nationalParks:", error)
    }
};

const getTypes = async () => {
    try {
        let response = await axios.get('/header-safari-types')
        types.value = response.data
    } catch (error) {
        console.error("Error fetching types:", error)
    }
};

const getBlogCategories = async () => {
    try {
        let response = await axios.get('/header-blog-categories')
        blogCategories.value = response.data
    } catch (error) {
        console.error("Error fetching blog categories:", error)
    }
};

</script>

<style scoped>
.current-menu-item-text {
    color: #C49A6C !important;
}
</style>