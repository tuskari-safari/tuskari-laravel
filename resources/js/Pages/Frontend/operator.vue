<template>
    <Banner pageName="Operator" />

    <!-- why travel with us section start  -->
    <div class="sty-travel-us cmn-gap operatorpage pb-0">
        <div class="container">
            <div class="text-center">
                <h2 v-html="props.cms?.['why-join-tuskari']?.title ?? 'What join Tuskari ?'"></h2>
                <div class="head-desc">
                    <p>{{ props.cms?.['why-join-tuskari']?.subtitle ?? 'Real travellers. Direct bookings. Fairer margins.' }}</p>
                </div>
            </div>
            <div class="why-us-details-wrapper">
                <div class="row why-details-row">
                    <div class="col-lg-3 col-md-4 why-us-col"
                        v-for="(feature, index) in JSON.parse(props.cms?.['why-join-tuskari']?.features || '[]')"
                        :key="index">
                        <div class="why-details-box">
                            <div class="why-dtl-logo">
                                <img :src="feature.logo ?? 'frontend_assets/images/op-ic-1.svg'" alt="logo-1">
                            </div>
                            <div class="content-box">
                                <h3>Get discovered</h3>
                                <p> {{ feature.subtitle ?? 'Every trip vetted for quality & ethics.' }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- why travel with us section end  -->
    <!-- operator page details listing start -->
    <div class="ope-details cmn-gap">
        <div class="container">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-lg-6 left-col">
                        <figure class="optabtimg-outer">
                            <img :src="'frontend_assets/images/ope-abt-img.jpg'" alt="nature">
                        </figure>
                    </div>
                    <div class="col-lg-6 right-col">
                        <div class="con-box">
                            <div class="h2-title" v-html=" props.cms?.['what-you-can-list']?.title ?? 'What You Can List More than just safaris.'">
                               
                            </div>
                            <p>
                                {{ props.cms?.['what-you-can-list']?.subtitle ?? 'From safaris to stays, Tuskari is a platform for all local operators.' }}
                            </p>
                       
                            <div class="dfult-content" v-html="props.cms?.['what-you-can-list']?.text_content ?? 'We welcome a wide range of local travel businesses — as long as it’s genuine and locally run'">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- operator page details listing end -->

    <!-- how it works start  -->
    <div class="hw-it-wrk-sec opt-hwitwrk">
        <div class="container">
            <div class="hw-it-wrk-wrpr cmn-gap"
                :style="{ backgroundImage: `url(${props.cms?.['how-it-works']?.thumbnail_full_url ?? '/frontend_assets/images/howitworkimg.jpg'})` }">
                <div class="hw-it-wrk-contnt">
                
                    <div class="cmn-heading">
                        <span class="cmn-subhd cmn-subhd-mb cmn-subhd-wht">{{ props.cms?.['how-it-works']?.tag ?? 'How is work' }}</span>
                        <h2 class="whth2" v-html="props.cms?.['how-it-works']?.title ?? 'What is Tuskari'"></h2>
                        <p> {{ props.cms?.['how-it-works']?.subtitle ?? 'Planning your safari shouldn’t be overwhelming. With Tuskari, it’s easy, honest, and guided by people who know the land.' }}</p>
                    </div>


                    <div class="hw-it-wrklstbx">
                        <div class="hw-it-wrk-lstitm" v-for="(feature, index) in JSON.parse(props.cms?.['how-it-works']?.features || '[]')" :key="index">
                            <div class="hw-it-wrklin"><img :src="'/frontend_assets/images/dshoutlineimg.svg'"
                                    alt="line"></div>
                            <div class="hw-it-wrk-imgbx">
                                <img :src="feature.logo ?? '/frontend_assets/images/srchicon.svg'" alt="How it work icon">
                                
                            </div>
                            <div class="hw-it-wrk-itmcont">
                                <h3 class="whth2" >
                                {{ feature.title ?? 'Search' }}
                                </h3>
                                <p>{{ feature.subtitle ?? 'Title' }}</p>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- how it works end  -->

    <!-- bottom content sec start  -->
    <div class="bottom-content-sec ope-details optbulidsec cmn-gap">
        <div class="container">
            <div class="bottom-content-wrpr">
                <div class="row btm-cont-row">
                    <div class="col-lg-6 left-col">
                        <div class="img-outer">
                            <img :src="props.cms?.['built-for-you']?.thumbnail_full_url ??'frontend_assets/images/built-for-you.png'" alt="build for you">
                        </div>
                    </div>
                    <div class="col-lg-6 right-col">
                        <div class="con-box">
                            <div class="h2-title" v-html=" props.cms?.['built-for-you']?.title ?? 'Built for you'"></div>
                            <p class="h3-title">
                               {{ props.cms?.['built-for-you']?.subtitle ?? 'Tuskari is built for local operators, by local operators.' }}
                            </p>
                            <div class="dfult-content" v-html="props.cms?.['built-for-you']?.text_content">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="whttuskari-sec ope-page cmn-gap">
        <div class="container">
            <div class="row whttuskari-row">
                <div class="col-lg-6 whttuskari-lftcol">
                    <figure class="whttuskari-imgbx">
                        <img :src="props.cms?.['more-of-the-money-goes']?.thumbnail_full_url ?? 'frontend_assets/images/more-of-the-money.jpg'" alt="image">
                    </figure>
                </div>
                <div class="col-lg-6 whttuskari-rgtcol">
                    <div class="whttuskari-contentbx">
                        <div class="cmn-heading">
                            <h2 v-html="props.cms?.['more-of-the-money-goes']?.title ?? 'More of the money goes where it should'"></h2>
                        </div>
                        <p>
                           {{ props.cms?.['more-of-the-money-goes']?.subtitle ?? 'Tuskari was built to rebalance the safari industry. No layers of middlemen, no inflated margins. You earn more per booking, and travellers know their money supports the people making the trip happen' }}
                        </p>
                        <a href="#url" class="cmn-butn">Join Tuskari today</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- bottom content sec end  -->
    <!-- faq section start  -->
    <div class="faq-section-cont pt-0">
        <div class="container">
            <div class="h2-title">Still have questions? We’ve got answers.</div>
            <div class="faq faqoutwrpr">
                <div class="row faq-row">
                    <h3 class="text-center" v-if="!faqPart1 && !faqPart2">No Faq found</h3>
                    <div class="col-md-6">
                        <div class="faq-container" v-for="faq1 in faqPart1">
                            <div class="qution">
                                <h3>{{ faq1?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                </div>
                            </div>
                            <div class="anwers" style="display: block">
                                <p>
                                    {{ faq1?.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="faq-container" v-for="faq2 in faqPart2">
                            <div class="qution">
                                <h3>{{ faq2?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                </div>
                            </div>
                            <div class="anwers" style="display: block">
                                <p>
                                    {{ faq2?.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- faq section end  -->



    <div class="the-wild-sec pt-0 safari-for-you">
        <div class="wild-wrap">
            <img :src="footerBanner?.full_image ?? 'frontend_assets/images/wild-img.jpg'" alt="img" class="wild-img">
            <img :src="'frontend_assets/images/mb-trvelbetterimg1.jpg'" alt="img" class="wild-img wild-img-mb">
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">
                        {{ footerBanner?.title }}
                    </h2>
                    <p>
                        {{ footerBanner?.subtitle }}
                    </p>
                    <a href="javascript:void(0)" class="cmn-butn wht-butn">Get started free</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Banner from '@/components/Frontend/Banner.vue';
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";

const props = defineProps({
    cms: Object,
    banner: Object,
    faqPart1: Object,
    faqPart2: Object,
    footerBanner: Object
});
onMounted(() => {
    homeJs();
})



</script>