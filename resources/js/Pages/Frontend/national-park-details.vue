<template>
    <div class="bnrsec inerbnrsec">
        <img :src="props.park?.full_banner_url ?? '/frontend_assets/images/nat-park-bnr.jpg'" alt="bannerimg" class="bnrimg">
        <div class="container">
            <div class="bnrcontent">
                <h1 class="inerbnrhead cmnhd-design">{{ props?.park?.name }}</h1>
                <p>
                    {{ props.park?.short_description ?? 'One of Africa`s most iconic reserves — where safaris are wild,vast, and unforgettable.'}}
                </p>
            </div>
        </div>
    </div>
    <div class="prv-res-bnr new-np-prv-res-bnr">
        <div class="container">
            <div class="bnr-wrapper">
                <div class="content">
                    <h2>{{ props.park?.subtitle ?? 'This is raw Africa.'}}</h2>
                   
                    <div class="desc" v-html="props.park?.description">
                    </div>
                </div>
            </div>
        </div>
    </div>

       <div v-if="props.park?.location && props.park?.lat && props.park?.long" class="map-section">
                        <div class="map-container np-map-container">
                            <MapBoxView :location="location" :zoom="8"/>
                        </div>
                </div>
    <div class="feat-exp-sec np-krugersec wl-hlts">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <h2> {{ props.park?.title ?? 'Top Areas to Visit' }}</h2>
                    </div>
                    <div class="feat-exp-slidr-arwwrpr" v-if="modifiedExperience.length > 0">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>

                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev">
                                <img :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow">
                            </button>
                            <button class="ftslickarw ftslickarw-next">
                                <img :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="feat-exp-slidr-outr pri-rev nppark-topslider">
                            <div class="prvt-reserve">
                                <div class="ft-exp-slide" v-for="unique in modifiedExperience" :key="unique.id">
                                    <div class="privt-resrt-card">
                                        <img :src="unique.image ?? '/frontend_assets/images/slbg-1.jpg'" alt="private slider">
                                        <div class="bottom-cont">
                                            <div class="h3-title">{{ unique?.title ?? 'Leopards Galore' }}</div>
                                            <p>{{ unique?.subtitle ?? 'Experience the wild in comfort with our handpicked lodges and camps.' }}</p>
                                            <!-- <a href="javascript:void(0)" class="ft-exp-itmbutn">View Lodge</a> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                <!-- <div class="row whr-to-row">
                    <div class="left-col">
                    </div>
                    <div class="right-col">
                       
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <div class="nphghlight-yearsec pb-0 cmn-gap">
        <div class="container">
            <div class="cmn-heading text-center">
                <h2>{{ props.park?.wildlife_highlights_title ?? 'Highlights Throughout the Year' }}</h2>
                <p>{{ props.park?.wildlife_highlights_desc ?? 'Experience the wild in comfort with our handpicked lodges and camps.' }}</p>
            </div>
            <div class="np-hglghtwrp-outr">
                <div class="np-hglghtwrp" v-for="timeVisit in JSON.parse(props.park?.best_visit_time ?? '[]')" :key="timeVisit.id">
                    <div class="row np-hglght-row">
                        <div class="col-md-6 np-hglght-lftcol">
                            <div class="np-hglghtimgbx"><img :src="timeVisit?.image ?? '/frontend_assets/images/saf-for-u.jpg'" alt="img"></div>
                        </div>
                        <div class="col-md-6 np-hglght-rgtcol">
                            <div class="np-hglght-contbx">
                                <div class="h2-title np-hglghthd" v-html="timeVisit?.title"></div>
                                <div class="np-hglght-contpara" v-html="timeVisit?.subtitle ?? 'NA'"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="feat-exp-sec np-only-krugersec wl-hlts cmn-gap" v-if="props?.park?.type=='national_park'">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <h2> {{ props.park?.title_2 ?? 'Only in National Park'}}</h2>
                        <p>{{ props.park?.subtitle_2 ?? 'Experience the wild in comfort with our handpicked lodges and camps.' }}</p>
                    </div>
                    <div class="feat-exp-slidr-arwwrpr" v-if="modifiedCategory.length > 0">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>

                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev">
                                <img :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow">
                            </button>
                            <button class="ftslickarw ftslickarw-next">
                                <img :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow">
                            </button>
                        </div>
                    </div>
                </div>
                <div class="feat-exp-slidr-outr pri-rev nppark-topslider">
                    <div class="prvt-reserve">
                        <div class="ft-exp-slide" v-for="category in modifiedCategory" :key="category.id">
                            <div class="privt-resrt-card">
                                <img :src="category.image ?? '/frontend_assets/images/slbg-1.jpg'" alt="private slider">
                                <div class="bottom-cont">
                                    <div class="h3-title">{{ category?.title ?? 'Leopards Galore' }}</div>
                                    <p>{{ category?.subtitle ?? 'Experience the wild in comfort with our handpicked lodges and camps.' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div class="safari-for-you np-explrekurgrsec cmn-gap pt-0">
        <div class="wild-wrap">
            <img :src="props.park?.full_impact_image_url ?? '/frontend_assets/images/saf-for-u.jpg'" alt="img" class="wild-img">
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">Tuskari’s Impact in {{ props.park?.name }}</h2>
                    <p>
                        {{ props.park?.impact ?? 'We are committed to making a positive impact in the Kruger National Park. Through our sustainable tourism practices.' }}
                    </p>
                    <a :href="route('frontend.safari-listings', { 'location[name]': props.park?.name, 'location[type]': 'park' })" class="cmn-butn yellow-btn">View Trips in {{ props.park?.name }}</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted , computed, ref} from 'vue'
import { homeJs } from "@/custom.js";
import MapBoxView from '@/components/Frontend/MapBoxView.vue';

onMounted(() => {
    homeJs();
});

const props = defineProps({
   park: Object,
   accommodations: Array,
   wild_lives: Array,
   featuredSafaris: Array,
});

const location = ref({
    name: props.park?.location || 'Unknown Location',
    lat: props.park?.lat || 0,
    lng: props.park?.long || 0,
    description: props.park?.location || 'No description available.'
});

const parsedFaqs = computed(() => {
    try {
        return JSON.parse(props.park?.faqs || '[]');
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


const modifiedCategory = computed(() => {
    let category = [];

    if (props?.park?.category) {
        try {
            const parsed = typeof props.park.category == 'string'
                ? JSON.parse(props.park.category)
                : props.park.category;

            category = Array.isArray(parsed) ? [...parsed] : [];
        } catch (e) {
            console.error("Error parsing category:", e);
            category = [];
        }
    }

    if (category.length < 5) {
        while (category.length < 5) {
            category = [...category, ...category];
            if (category.length == 0) break;
        }
        category = category.slice(0, 5);
    }

    return category;
});


const modifiedExperience = computed(() => {
    let uniqueExp = [];

    if (props?.park?.unique_experience) {
        try {
            const parsed = typeof props.park.unique_experience == 'string'
                ? JSON.parse(props.park.unique_experience)
                : props.park.unique_experience;

            uniqueExp = Array.isArray(parsed) ? [...parsed] : [];
        } catch (e) {
            console.error("Error parsing unique_experience:", e);
            uniqueExp = [];
        }
    }

    if (uniqueExp.length < 5) {
        while (uniqueExp.length < 5) {
            uniqueExp = [...uniqueExp, ...uniqueExp];
            if (uniqueExp.length == 0) break;
        }
        uniqueExp = uniqueExp.slice(0, 5);
    }
    return uniqueExp;
});


</script>