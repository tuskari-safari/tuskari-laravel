<template>
    <Banner pageName="Story" />
    <div class="whttuskari-sec">
        <div class="container">
            <div class="row whttuskari-row">
                <div class="col-md-6 whttuskari-lftcol">
                    <figure class="whttuskari-imgbx">
                        <img :src="props.cms?.['what-is-tuskari']?.thumbnail_full_url ??'frontend_assets/images/tuskari-abtimg.jpg'" alt="image">
                    </figure>
                </div>
                <div class="col-md-6 whttuskari-rgtcol">
                    <div class="whttuskari-contentbx">
                        <div class="cmn-heading">
                            <span class="cmn-subhd cmn-subhd-mb">{{ props.cms?.['what-is-tuskari']?.tag }}</span>
                            <h2 v-html="props.cms?.['what-is-tuskari']?.title">
                            </h2>
                        </div>
                        <p>
                            {{ props.cms?.['what-is-tuskari']?.subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- your gateway section end  -->

    <!-- why travel with us section start  -->
    <div class="sty-travel-us cmn-gap">
        <div class="container">
            <div class="text-center">
                <span class="cmn-subhd">{{ props.cms?.['why-travel-with-us']?.tag }}</span>
                <h2 v-html="props.cms?.['why-travel-with-us']?.title"></h2>
                <div class="head-desc" v-html="props.cms?.['why-travel-with-us']?.text_content">
                </div>
            </div>
            <div class="why-us-details-wrapper">
                <div class="row why-details-row">
                    <div class="col-lg-4 col-md-6 why-us-col" v-for="(feature, index) in JSON.parse(props.cms?.['why-travel-with-us']?.features || '[]')" :key="index">
                        <div class="why-details-box">
                            <div class="why-dtl-logo">
                                <img :src="feature?.logo ??'frontend_assets/images/why-dtl-1.png'" alt="Why Us Image">
                            </div>
                            <div class="content-box">
                                <h3>{{ feature?.title }}</h3>
                                <p>
                                    {{ feature?.subtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- why travel with us section end  -->

    <!-- why tuskai excist sec start  -->
    <div class="hw-it-wrk-sec story-page">
        <div class="container">
            <div class="hw-it-wrk-wrpr cmn-gap"
               :style="{ backgroundImage: `url(${props.cms?.['why-tuskari-exists']?.thumbnail_full_url ?? '/frontend_assets/images/why-tuskari-exsist-bg.jpg'})` }">
                <div class="hw-it-wrk-contnt">
                    <div class="cmn-heading">
                        <span class="cmn-subhd cmn-subhd-mb cmn-subhd-wht">{{ props.cms?.['why-tuskari-exists']?.tag }}</span>
                        <h2 class="whth2" v-html="props.cms?.['why-tuskari-exists']?.title">
                        </h2>
                        <p>
                            {{ props.cms?.['why-tuskari-exists']?.subtitle }}
                        </p>
                    </div>
                    <div class="hw-it-wrklstbx" v-html="props.cms?.['why-tuskari-exists']?.text_content">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- why tuskai excist sec end  -->

    <div class="testimorial-sec cmn-gap">
        <div class="container">
            <div class="cmn-heading text-center">
                <span class="cmn-subhd">Testimonials</span>
                <h2>Real Travellers. <br><span class="cmnhd-design">Real Stories.</span></h2>
            </div>
        </div>
        <div class="wrap-test-al">
            <div class="wrap-slider-cir">
                <div class="slider-testimorial" v-if="props.testimonials?.length > 0">
                    <div class="slider-testimorial-item" v-for="(testimonial, index) in props.testimonials" :key="index">
                        <div class="test-wrap">
                            <div class="up-testimorial">
                                <div class="left-test">
                                    <div class="img-wraptest">
                                        <img :src="testimonial?.user?.profile_photo_url ?? 'frontend_assets/images/test-1.jpg'" alt="img">
                                    </div>
                                    <h3 class="whth2">{{ testimonial?.user?.full_name ?? 'NA'}}</h3>
                                </div>
                                <div class="rgt-test">
                                    <div class="star-part">
                                        <img :src="'frontend_assets/images/star.png'" alt="img">
                                        {{ testimonial?.rating ?? '0' }}
                                    </div>
                                </div>
                            </div>
                            <div class="down-testimoril">
                                <h4>{{ testimonial?.title ?? 'Perfectly Tailored Experience' }}</h4>
                                <p v-html="testimonial?.description ?? 'We`ve done trips before but this was something else'"></p>
                            </div>
                        </div>
                    </div>
                   
                </div>
                <div v-else >
                    <div class="test-wrap" style="color: #ffffff; text-align: center">
                        No testimonial found
                    </div>
                </div>
                <div v-if="props.testimonials.length > 0" class="wrap-test">
                    <a href="javascript:void(0)" class="slick-btn prev"><img
                            :src="'frontend_assets/images/ftslick-arwlft.svg'" alt="img"></a>
                    <a href="javascript:void(0)" class="slick-btn next"><img
                            :src="'frontend_assets/images/ftslick-arwrgt.svg'" alt="img"></a>
                </div>
            </div>
        </div>
    </div>

    <div class="meet-sec story-page">
         <div class="wrap-meet">
            <div class="row row-meet">
                <div class="col-md-5 col-meet">
                    <div class="inr-meet">
                        <div class="cmn-subhd">{{ props.cms?.['operator-highlights']?.tag ?? 'Operator' }}</div>
                        <h2 v-html="props.cms?.['operator-highlights']?.title ?? 'Meet the People'"></h2>
                        <p> {{ props.cms?.['operator-highlights']?.subtitle ?? 'Planning your safari shouldn’t be overwhelming. With Tuskari, it’s easy, honest, and guided by people who know the land.' }}</p>
                        <a href="javascript:void(0)" class="cmn-butn">Partner with us</a>
                    </div>
                </div>
                <div class="col-md-7 col-meet">
                    <div class="man-img-wrap">
                        <img :src="props.cms?.['operator-highlights']?.thumbnail_full_url ?? 'frontend_assets/images/main-operatorimg.jpeg'" alt="img">
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import Banner from '@/components/Frontend/Banner.vue';

const props = defineProps({
    cms: Object,
    testimonials: Object,
});

onMounted(() => {
    homeJs();
})

</script>