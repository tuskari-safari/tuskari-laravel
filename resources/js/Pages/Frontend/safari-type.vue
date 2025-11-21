<template>
    <Banner pageName="Safari Style" />
    <div class="safari-style-sec cmn-gap">
        <div class="container">
            <div class="top-head">
                <h2>There’s no one way to go on safari</h2>
                <!-- <a href="#url" class="cmn-butn">View trips</a> -->
            </div>
            <div class="safari-style-outer">
                <div class="row">
                    <template v-if="safariTypes.length == 0">
                        <div class="col-lg-3 col-md-4 custom-col">
                            <div class="populr-safr-item">
                                <div class="ppulr-safr-itm-top">
                                    <figure class="ppulr-safrimgbx">
                                        <img :src="'frontend_assets/images/lux-safa.jpg'" alt="Safari Image">
                                    </figure>
                                </div>
                                <div class="ppulr-safr-contbx">
                                    <div class="cont">
                                        <h3 class="ppulr-safrhd">
                                            <a href="javascript:void(0)">Luxury Safaris</a>
                                        </h3>
                                        <p>
                                            Travel in style. Private guides, infinity pools, and
                                            sundowners under baobabs. This is Africa without
                                            compromise — just you and the wild.
                                        </p>
                                    </div>
                                    <div class="link">
                                        <a href="javascript:void(0)" class="ft-exp-itmbutn">Explore luxury safaris</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 custom-col">
                            <div class="populr-safr-item">
                                <div class="ppulr-safr-itm-top">
                                    <figure class="ppulr-safrimgbx">
                                        <img :src="'frontend_assets/images/suf-bx-2.jpg'" alt="Safari Image">
                                    </figure>
                                </div>
                                <div class="ppulr-safr-contbx">
                                    <div class="cont">
                                        <h3 class="ppulr-safrhd">
                                            <a href="javascript:void(0)">Budget-Friendly Safaris</a>
                                        </h3>
                                        <p>
                                            Big on experience, not on price. Camp under stars,
                                            travel light, and discover that true magic needs no
                                            five-star label.
                                        </p>
                                    </div>
                                    <div class="link">
                                        <a href="javascript:void(0)" class="ft-exp-itmbutn">View affordable adventures</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 custom-col">
                            <div class="populr-safr-item">
                                <div class="ppulr-safr-itm-top">
                                    <figure class="ppulr-safrimgbx">
                                        <img :src="'frontend_assets/images/suf-bx-3.jpg'" alt="Safari Image">
                                    </figure>
                                </div>
                                <div class="ppulr-safr-contbx">
                                    <div class="cont">
                                        <h3 class="ppulr-safrhd">
                                            <a href="javascript:void(0)">Private Safaris</a>
                                        </h3>
                                        <p>
                                            Just you, your guide, and the bush. Total privacy,
                                            tailored pacing, and no one else’s schedule — just space
                                            to breathe.
                                        </p>
                                    </div>
                                    <div class="link">
                                        <a href="javascript:void(0)" class="ft-exp-itmbutn">See private safari itineraries
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="col-lg-3 col-md-4 custom-col" v-for="safariType in safariTypes"
                            :key="safariType.id">
                            <div class="populr-safr-item">
                                <div class="ppulr-safr-itm-top">
                                    <figure class="ppulr-safrimgbx">
                                        <img :src="safariType.thumbnail_url" alt="Safari Image">
                                    </figure>
                                </div>
                                <div class="ppulr-safr-contbx">
                                    <div class="cont">
                                        <h3 class="ppulr-safrhd">
                                            <a href="javascript:void(0)">{{ safariType.title }}</a>
                                        </h3>
                                        <p>
                                            {{ safariType.subtitle }}
                                        </p>
                                    </div>
                                    <div class="link">
                                        <a :href="route('frontend.safari-listings') + '?safariTypeIds[]=' + safariType.id"
                                            class="ft-exp-itmbutn">
                                            {{ safariType.button_text }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <div class="the-wild-sec pt-0 safari-for-you">
        <div class="wild-wrap">
            <img :src="footerBanner?.full_image ?? 'frontend_assets/images/wild-img.jpg'" alt="img" class="wild-img">
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">
                        {{ footerBanner?.title }}
                    </h2>
                    <p>
                        {{ footerBanner?.subtitle }}
                    </p>
                    <div class="btn-bx">
                        <a href="javascript:void(0)" class="cmn-butn yl-btn">Take the safari style quiz</a>
                        <a href="javascript:void(0)" class="cmn-butn trs-btn">Talk to a safari expert</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Banner from '@/components/Frontend/Banner.vue';
import { homeJs } from "@/custom.js";
import { onMounted } from 'vue';

const props = defineProps({
    safariTypes: Object,
    footerBanner: Object,
});

onMounted(() => {
    homeJs();
});

</script>