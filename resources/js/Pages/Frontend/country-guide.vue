<template>
    <!-- <Banner pageName="Country Guide" /> -->
    <div class="bnrsec inerbnrsec">
        <img :src="country?.banner_image_url ?? '/frontend_assets/images/nat-park-bnr.jpg'" alt="bannerimg" class="bnrimg">
        <div class="container">
            <div class="bnrcontent">
                <h1 class="inerbnrhead cmnhd-design">{{ country?.name }}</h1>
                <p>
                    {{ country?.subtitle ?? 'One of Africa`s most iconic reserves — where safaris are wild,vast, and unforgettable.'}}
                </p>
            </div>
        </div>
    </div>

    <div class="prv-res-bnr new-np-prv-res-bnr">
        <div class="container">
            <div class="bnr-wrapper">
                <div class="content">
                    <h2>{{ country?.middle_sec_title ?? 'NA' }}</h2>
                    <div class="csw-strong" v-html="country?.middle_sec_subtitle">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <div class="whttuskari-sec cgpage-tp" v-if="JSON.parse(country?.content_details)?.[0]">
        <div class="container">
            <div class="row whttuskari-row">
                <div class="col-md-6 whttuskari-rgtcol">
                    <div class="whttuskari-contentbx">
                        <div class="cmn-heading">

                            <h2 v-html="JSON.parse(country?.content_details)[0]?.title ?? 'NA'"></h2>
                        </div>
                        <p>
                            {{ JSON.parse(country?.content_details)[0]?.subtitle ?? 'NA' }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6 whttuskari-lftcol">
                    <figure class="whttuskari-imgbx">
                        <img :src="JSON.parse(country?.content_details)[0]?.image ?? '/frontend_assets/images/saf-was-born.jpg'" alt="image">
                    </figure>
                </div>
            </div>
        </div>
    </div> -->

    <div class="whr-to-sty cmn-gap cgpage top-np">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <h2>Top National Parks</h2>
                    </div>

                    <div class="feat-exp-slidr-arwwrpr" v-if="duplicatedNationalparks.length > 0">
                            <div class="featrprgr">
                                <div class="featrprgrsafter">
                                    <div class="featrprgrsfill"></div>
                                </div>
                            </div>

                            <div class="feat-slidrbtn-wrapr">
                                <button class="ftslickarw ftslickarw-prev">
                                    <img :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow" />
                                </button>
                                <button class="ftslickarw ftslickarw-next">
                                    <img :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow" />
                                </button>
                            </div>
                        </div>

                </div>

                <div class="feat-exp-slidr-outr pri-rev">
                            <div class="prvt-reserve-mini" v-if="duplicatedNationalparks.length > 0">
                                <div class="ft-exp-slide" v-for="(item, index) in duplicatedNationalparks" :key="index">
                                    <div class="privt-resrt-card">
                                        <img :src="item?.full_thumbnail_url??'/frontend_assets/images/tnp-1.jpg'" alt="private slider" />
                                        <div class="bottom-cont">
                                            <div class="h3-title">{{ item?.name ?? 'NA' }}</div>
                                            <p>{{ item?.subtitle ?? 'NA'}}</p>
                                            <Link :href="route('frontend.national-park-details',  item.name?.toLowerCase().replace(/\s+/g, '-'))" class="ft-exp-itmbutn">Read more</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else>
                                <div style="text-align: center;">
                                    <p>No National Parks found.</p>
                                </div>
                            </div>
                        </div>
                <!-- <div class="row whr-to-row">
                    <div class="left-col">
                        <div class="title-box">
                            <h2>Top National Parks</h2>
                        </div>
                    </div>
                    <div class="right-col">

                        
                        <div class="feat-exp-slidr-outr pri-rev">
                            <div class="prvt-reserve-mini" v-if="duplicatedNationalparks.length > 0">
                                <div class="ft-exp-slide" v-for="(item, index) in duplicatedNationalparks" :key="index">
                                    <div class="privt-resrt-card">
                                        <img :src="item?.full_thumbnail_url??'/frontend_assets/images/tnp-1.jpg'" alt="private slider" />
                                        <div class="bottom-cont">
                                            <div class="h3-title">{{ item?.name ?? 'NA' }}</div>
                                            <p>{{ item?.subtitle ?? 'NA'}}</p>
                                            <Link :href="route('frontend.national-park-details',  item.name?.toLowerCase().replace(/\s+/g, '-'))" class="ft-exp-itmbutn">Read more</Link>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div v-else>
                                <div style="text-align: center;">
                                    <p>No National Parks found.</p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> -->
            </div>

        </div>
    </div>


    <!-- <div class="whttuskari-sec cgpage  midd  cmn-gap pb-0" v-for="(item, index) in JSON.parse(country?.content_details)" :key="index">
        <div class="container">
            <div class="row whttuskari-row cmn-gap pt-0">
                <div class="col-md-6 whttuskari-lftcol">
                    <figure class="whttuskari-imgbx region-imgbx">
                        <img :src="item.image ?? 'frontend_assets/images/main-operatorimg.jpeg'" alt="image" />
                    </figure>
                </div>
                <div class="col-md-6 whttuskari-rgtcol">
                    <div class="whttuskari-contentbx">
                        <div class="cmn-heading">
                            <h2 >hfghgfh</h2>
                        </div>
                        <p>
                            {{ item?.subtitle }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> -->


    <div class="nphghlight-yearsec pb-0 cmn-gap">
        <div class="container">

            <div class="np-hglghtwrp-outr" v-if="JSON.parse(country?.content_details)?.length > 0">
                <div class="np-hglghtwrp" v-for="(item, index) in JSON.parse(country?.content_details)" :key="index">
                    <div class="row np-hglght-row">
                        <div class="col-md-6 np-hglght-lftcol">
                            <div class="np-hglghtimgbx"><img :src="item?.image ?? '/frontend_assets/images/saf-for-u.jpg'" alt="img"></div>
                        </div>
                        <div class="col-md-6 np-hglght-rgtcol">
                            <div class="np-hglght-contbx">
                                <div class="h2-title np-hglghthd">{{ item?.title ?? 'NA' }}</div>
                                <div class="np-hglght-contpara">
                                    <p>
                                        {{ item?.subtitle ?? 'NA' }}
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <p>No Highlights</p>
            </div>

        </div>
    </div>

    <div class="feat-exp-sec wl-hlts cmn-gap pt-5 cgpage">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <div class="ttl-wrap ue-sec">
                            <h2>{{ country?.unique_experience_title ?? 'Unique Experiences' }}</h2>
                            <p>
                              {{ country?.unique_experience_desc ?? 'Let travellers imagine themselves here.' }}  
                            </p>
                        </div>
                    </div>
                    <div class="feat-exp-slidr-arwwrpr">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>

                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev">
                                <img :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow" />
                            </button>
                            <button class="ftslickarw ftslickarw-next">
                                <img :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow" />
                            </button>
                        </div>
                    </div>
                </div>
                <div class="feat-exp-slidr-outr pri-rev cngd-unexp-sldr">
                    <div class="prvt-reserve" v-if="duplicatedExperiences.length > 0">
                        <div class="ft-exp-slide" v-for="(item, index) in duplicatedExperiences" :key="index">
                            <div class="privt-resrt-card">
                                <img :src="item?.image ??'/frontend_assets/images/ues-1.jpg'" alt="private slider" />
                                <div class="bottom-cont">
                                    <div class="h3-title cmnhd-design">{{ item?.title }}</div>
                                    <p>
                                        {{ item?.subtitle }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div style="text-align: center;">
                            <p>No Unique Experiences found.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="the-wild-sec py-0 safari-for-you tusk-impctsec">
        <div class="wild-wrap" v-if="JSON.parse(country?.bottom_banner)?.[0]">
            <img :src="JSON.parse(country?.bottom_banner)?.[0]?.image ??'frontend_assets/images/saf-for-u.jpg'" alt="img" class="wild-img" />
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">{{ JSON.parse(country?.bottom_banner)?.[0]?.title }}</h2>
                    <p>
                        {{ JSON.parse(country?.bottom_banner)?.[0]?.subtitle ?? 'NA' }}
                    </p>
                    <div class="tusk-impct-butn">
                        <a href="javascript:void(0)" class="cmn-butn yl-btn">Get in touch with us</a>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--feature experience-->
    <div class="feat-exp-sec cmn-gap npbtm cgpage-bot">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading">
                        <h2>Suggested Trips & Itineraries</h2>
                    </div>
                    <div class="btns-bx">
                        <ul class="listingfiltermenu-list">
                            <li class="active">
                                <a class="cmn-butn" href="javascript:void(0)">Classic Mara & Amboseli</a>
                            </li>
                            <li>
                                <a class="cmn-butn" href="javascript:void(0)">Northern Explorer</a>
                            </li>
                            <li><a class="cmn-butn" href="javascript:void(0)">Bush & Beach</a></li>
                        </ul>
                    </div>
                </div>
                <div class="row ft-saf-kr">
                    <div class="col-lg-3 col-md-4 ft-exp-slide" v-for="(feature, index) in props.featuredSafaris" :key="index">
                        <div class="ft-exp-item">
                            <SafariCard :safari="feature" />
                        </div>
                    </div>
                    <div class="no-safari text-center" v-if="featuredSafaris.length === 0">
                        <p>No similar safaris found.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--    faq-->

    <div class="faq-section-cont">
        <div class="container">
            <div class="h2-title">Frequently Asked Questions</div>
            <div class="faq faqoutwrpr">
                <div class="row faq-row">
                    <h3 class="text-center" v-if="faqPart1.length === 0 && faqPart2.length === 0">No FAQ found</h3>
                    <div class="col-md-6">
                        <div class="faq-container" v-for="faq1 in faqPart1" :key="faq1">
                            <div class="qution">
                                <h3>{{ faq1?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
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
                        <div class="faq-container" v-for="faq2 in faqPart2" :key="faq2">
                            <div class="qution">
                                <h3>{{ faq2?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
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
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { homeJs } from "@/custom.js";
import SafariCard from '@/components/Frontend/SafariCard.vue';

const props = defineProps({
    country: Object,
    nationalParks: Object,
    featuredSafaris: Object
});

const parsedFaqs = computed(() => {
    try {
        return JSON.parse(props.country?.faqs || '[]');
    } catch (e) {
        console.error("Error parsing FAQs:", e);
        return [];
    }
});

const [faqPart1, faqPart2] = (() => {
    const faqs = parsedFaqs.value.map(f => ({ question: f.question, answer: f.answer }));
    const half = Math.ceil(faqs.length / 2);
    return [computed(() => faqs.slice(0, half)), computed(() => faqs.slice(half))];
})();

const duplicatedNationalparks = computed(() => {
    let parks = [...props.nationalParks];

    if (parks.length === 0) {
        return [];
    }

    while (parks.length < 5) {
        parks = [...parks, ...props.nationalParks];
    }

    return parks.slice(0, 5);
});

const duplicatedExperiences = computed(() => {
  let experiences = [];
  try {
    experiences = JSON.parse(props.country?.unique_experience || '[]');
  } catch (e) {
    return [];
  }

  if (experiences.length > 0 && experiences.length < 4) {
    return [...experiences, ...experiences];
  }
  return experiences;
});
onMounted(() => {
    homeJs();
})
</script>