<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr serv-sf-dtls">
        <div class="trips_hder">
            <div class="hdr_lft_prt mb-0">
                <a :href="route('frontend.safari-manage-listing')" class="backarow"><i><img
                            :src="'/frontend_assets/images/back-blck-arrow.svg'" alt="Back Arrow"></i>
                    Back</a>
            </div>
            <div class="hdr_rght_prt extra">
                <a :href="route('frontend.safari-edit-listing', { id: safari.id })" class="cmn-butn edit"><img
                        :src="'/frontend_assets/images/pencil-line.svg'" alt="Edit" class="edit-img">
                    Edit</a>

                <a href="javascript:void(0)" @click="duplicateRecode(safari.id)" class="cmn-butn edit">
                    Duplicate</a>

                <a href="javascript:void(0)" @click="deleteRecode(safari.id)" class="cmn-butn delect"><img
                        :src="'/frontend_assets/images/trash-2.svg'" alt="Delete" class="edit-img">
                    Delete</a>
            </div>
        </div>
        <!-- safari overview sec start  -->
        <div class="safari-ov-sec usr-safa-dtl">
            <div class="container">
                <div class="safa-ov-row">
                    <div class="col-md-8 left-col">
                        <div class="safa-top-gal-outer">
                            <div class="row safa-top-gal-row" v-if="safari?.gallery.length">
                                <!-- First Image -->
                                <div class="col-md-6 safa-top-gal-col">
                                    <div class="img_box" v-if="safari?.gallery[0]">
                                        <img :src="safari?.gallery[0].full_image_url" alt="Gallery">
                                    </div>
                                </div>
                                <!-- Next Images -->
                                <div class="col-md-6 safa-top-gal-col">
                                    <div class="row safa-top-gal-row">
                                        <div class="col-md-6 safa-top-gal-col safa-top-gal-col-6 "
                                            v-for="(image, index) in safari?.gallery.slice(1, 5)" :key="index">
                                            <a href="javascript:void(0)" @click.prevent="showDialog = true"
                                                class="more-photos-outer"
                                                v-if="index === safari?.gallery.slice(1, 5).length - 1"> <span>View
                                                    photos</span> </a>
                                            <div class="img_box">
                                                <img :src="image.full_image_url" alt="Gallery">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <h5 class="text-center">No image found</h5>
                            </div>
                        </div>

                        <div class="safa-gal-bot">
                            <div class="left-rate">
                                <div class="rating-box">
                                    <div class="rtng-val">
                                        <div class="str-icn">
                                            <img :src="'/frontend_assets/images/star-green.svg'" alt="star icon">
                                        </div>
                                        <div class="rtn-num">{{ Number(safari?.safari_reviews_avg_rating).toFixed(1) ??
                                            'NA' }}</div>
                                    </div>
                                    <div class="verify-box">
                                        <div class="vrfy-icn">
                                            <img :src="'/frontend_assets/images/dti-tick.png'" alt="tick icon">
                                        </div>
                                        <div class="vrf-name">{{ safari?.is_approved == '1' ? 'Verified by Tuskari' :
                                            'Not Verified' }}</div>
                                    </div>
                                </div>
                            </div>
                            <SocialShare :share="share" />
                        </div>
                        <div class="safa-ov-wrap">
                            <div class="safa-ov-tttl safa-dtl-usr">
                                {{ safari?.title ?? 'NA' }}
                            </div>
                            <div class="usr-safa-dtl-info">
                                <div class="bnr-price-box">
                                    <ul>
                                        <li>
                                            <div class="bnr-price">
                                                <p>${{ Number(safari?.total_price).toLocaleString('en-GB') }}
                                                    <span>PP</span>
                                                </p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sty-dtl">
                                                <p>{{ safari?.day_duration ?? 'NA' }} Days {{ safari?.night_duration ??
                                                    'NA' }} Nights</p>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="location-dtl">
                                                <div class="lcn-icn">
                                                    <img :src="'/frontend_assets/images/map-green.svg'"
                                                        alt="location icon">
                                                </div>
                                                <p>
                                                    {{ safari?.location ?? 'NA' }}
                                                </p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="dtl-map-outer">
                                <MapBoxView :location="location" />
                            </div>
                            <div class="safa-dtl-main-outer">
                                <div class="safa-ov-tttl">Safari Highlights</div>
                                <p class="top_desc">
                                    {{ safari?.description ?? 'NA' }}
                                </p>
                                <div class="safa-ov-tttl" style="font-size: 24px">What makes this trip special:</div>
                                <p class="top_desc" v-html="safari?.short_description ?? 'NA'">
                                </p>
                            </div>
                        </div>
                        <div class="jrny-dy-sec inn-cmn-gap">
                            <div class="safa-ov-tttl pb-3">Your Journey, Day by Day</div>

                            <div class="faq faqoutwrpr tour-journey">
                                <div class="sfdtls-row" :class="{ 'row-open': index === 0 }"
                                    v-if="safari?.journeys?.length > 0" v-for="(journey, index) in safari.journeys"
                                    :key="index">
                                    <div class="sfdtls-left">
                                        <div class="sfdtls-lftpnl">
                                            <div class="sfdtls-numberhd">Day {{ index + 1 }}</div>
                                            <div class="sfdtls-dotlne"></div>
                                        </div>
                                    </div>
                                    <div class="sfdtls-rgt">
                                        <div class="faq-container" :class="{ 'is-open': index === 0 }">
                                            <div class="qution">
                                                <h3>{{ journey?.heading ?? 'NA' }}</h3>
                                                <div class="rightIcon">
                                                    <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                                </div>
                                            </div>
                                            <div class="anwers" style="display: block">
                                                <p>
                                                    {{ journey?.subtext ?? 'NA' }}
                                                </p>

                                                <div class="meallist">
                                                    <div class="ft-ttl acc-ttl">Today's highlights:</div>
                                                    <div v-if="journey?.today_highlights"
                                                        v-html="journey?.today_highlights"></div>
                                                    <div v-else>No today's highlights</div>
                                                </div>

                                                <div class="meallist">
                                                    <div class="ft-ttl acc-ttl">Meals & Drinks:</div>
                                                    <div v-if="journey?.meal" v-html="journey?.meal"></div>
                                                    <!-- <div v-if="journey?.meal">   </div> -->
                                                    <div v-else>No meals</div>
                                                </div>

                                                <p class="ft-ttl acc-ttl">{{ journey?.accommodation ?? 'NA' }}</p>
                                                <div class="acc-gal-outer">
                                                    <ul v-if="journey?.journey_images.length > 0">
                                                        <li v-for="(img, imgIndex) in journey?.journey_images"
                                                            :key="imgIndex">
                                                            <a href="">
                                                                <div class="img-wrap">
                                                                    <figure>
                                                                        <img :src="img.full_photo_url"
                                                                            alt="safari details">
                                                                    </figure>
                                                                </div>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="daywldlife-wrpr pt-3">

                                                    <div class="ft-ttl acc-ttl">Wildlife in {{ journey?.location?.name ??
                                                        'NA'}}</div>

                                                    <div class="icon-animal-wrap"
                                                        v-if="Array.isArray(journey.wildlife_highlights) && journey.wildlife_highlights.length">
                                                        <ul>
                                                            <li v-for="(wildlife, wIndex) in (
                                                                showAllWildlife[index]
                                                                    ? journey.wildlife_highlights
                                                                    : journey.wildlife_highlights.slice(0, 4)
                                                            )" :key="wIndex">
                                                                <div class="ani-box">
                                                                    <div class="icn-bx">
                                                                        <img :src="wildlife?.image" alt="lion">
                                                                    </div>
                                                                    <div class="cont-bx">
                                                                        <p class="ttl">{{ wildlife.animal_name ?? '' }}
                                                                        </p>
                                                                        <p class="abt">{{ wildlife.description ?? '' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        </ul>

                                                        <div class="daywldlife-selalbutnbx"
                                                            v-if="journey.wildlife_highlights.length > 4">
                                                            <a href="javascript:void(0)" class="daywldlife-selalbutn"
                                                                @click.prevent="toggleWildlife(index)">
                                                                {{ showAllWildlife[index] ? 'See Less' : 'See All' }}
                                                            </a>
                                                        </div>
                                                    </div>

                                                    <p v-else class="text-muted">No wildlife available</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="jrny-dy-sec inclde-notinclude-sec inn-cmn-gap" id="included-notincluded">
                            <div class="safa-ov-tttl">What’s Included & Not Included</div>
                            <div class="whtinclud-wrap">
                                <div class="whtinclud-title">What’s Included</div>
                                <p v-if="safari?.safari_included" v-html="safari?.safari_included"></p>
                                <p v-else>No data</p>
                            </div>
                            <div class="whtinclud-wrap">
                                <div class="whtinclud-title"> What’s Not Included</div>
                                <p v-if="safari?.safari_not_included" v-html="safari?.safari_not_included"></p>
                                <p v-else>No data</p>
                            </div>

                        </div>

                        <div class="safa-dtl-gal inn-cmn-gap">
                            <div class="safa-ov-tttl">Gallery</div>
                            <div class="safa-gal-wrap">
                                <ul>
                                    <li v-if="safari?.gallery.length > 0" v-for="(image, index) in safari?.gallery"
                                        :key="index">
                                        <div class="img-wrap">
                                            <figure>
                                                <!-- <a :href="image?.full_image_url" target="_blank" data-fancybox="sfgallery"> -->
                                                <img :src="image?.full_image_url" alt="safari details">
                                                <!-- </a> -->
                                            </figure>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="yr-trs-safa inn-cmn-gap pb-0" id="operator">
                            <div class="safa-ov-tttl">Meet Your Safari Operator</div>
                            <div class="yr-trs-outrer trs_outr_mdfed">
                                <div class="yrs-trt-top">
                                     <div class="meet-safari-logo">
                                        <img :src="operatorDetail?.operator_logo_full_url ?? '/frontend_assets/images/avatar.png'"
                                        alt="operator logo">
                                     </div>
                                      
                                    <h3>{{ operatorDetail?.business_name ?? 'NA' }}</h3>
                                </div>
                                <div class="verify-right-wrap">
                                     <ul class="all_tag_with_icon_lstng">
                                        <li>
                                            <div class="tick-icon">
                                                <img :src="'/frontend_assets/images/nw-tick.svg'" alt="tick">
                                            </div>
                                            <p>Verified by Tuskari</p>
                                        </li>
                                        <li>
                                            <div class="tick-icon">
                                                <img :src="'/frontend_assets/images/location-nw.svg'" alt="base icon">
                                            </div>
                                            <p>Based in {{ operatorDetail?.address ?? '' }}</p>
                                        </li>
                                        <li>
                                            <div class="tick-icon">
                                                <img :src="'/frontend_assets/images/nw-calndr.svg'"
                                                    alt="experience icon">
                                            </div>
                                            <p>{{ operatorDetail.no_of_employees ?? '' }} Experienced Staff</p>
                                        </li>
                                    </ul>
                                </div>
                                <!-- <p class="abt">
                               
                            </p> -->
                                <div class="abt abt_dscrption">
                                    <p> {{ operatorDetail?.about_operation ?? 'NA' }}</p>
                                    <p><strong>Why choose us:</strong></p>
                                    <p v-if="operatorDetail?.why_choose_us" v-html="operatorDetail?.why_choose_us"></p>
                                    <p v-else>No data</p>
                                </div>
                            </div>
                        </div>

                        <div class="wht-clnt-say inn-cmn-gap pb-0">
                            <div class="top-sec">
                                <div class="safa-ov-tttl">What Other Travellers Say</div>
                                <div class="rg-btn">
                                </div>
                            </div>

                            <div class="tst_list">
                                <ul v-if="safari?.safari_reviews.length > 0">
                                    <li v-for="(review, index) in safari?.safari_reviews" :key="index">
                                        <div class="safa-testi-box">
                                            <div class="sfa-testi-head">
                                                <div class="left">
                                                    <div class="usr-img">
                                                        <img :src="review?.user?.profile_photo_url || review?.user_full_image_url"
                                                            alt="user image">
                                                    </div>
                                                    <div class="usr-data">
                                                        <div class="usr-name">{{ review?.user?.full_name ||
                                                            review?.username }}</div>
                                                        <div class="pst-dte">
                                                            {{ ListHelper.dateFormat(review?.created_at, "MMM DD,YYYY")}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="rgt">
                                                    <div class="rtng-val">
                                                        <div class="str-icn">
                                                            <img :src="'/frontend_assets/images/star-green.svg'"
                                                                alt="star icon">
                                                        </div>
                                                        <div class="rtn-num">{{ review?.rating }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="sfa-user-smnt">
                                                <div class="ttl">{{ review?.heading }}</div>
                                                <p>
                                                    {{ review?.details }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                                <ul v-else>
                                    <h4 class="text-center">No review found</h4>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 right-col block">
                        <div class="av-form-wrap">
                            <div class="things-to-know">
                                <div class="safa-ov-tttl small_text">Things to know before you go</div>
                                <div class="faq">
                                    <div class="faq-section-cont faqoutwrpr" v-if="props.faqs.length > 0">
                                        <div class="faq-container" v-for="(faq, index) in props.faqs" :key="index">
                                            <div class="qution">
                                                <h3>{{ faq?.heading }}</h3>
                                                <div class="rightIcon">
                                                    <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                                </div>
                                            </div>
                                            <div class="anwers" style="display: block"
                                                v-html="faq?.description ?? 'NA'">
                                            </div>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <h4 class="text-center">No Faq found</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- safari overview sec end  -->
    </div>

    <Dialog v-model:visible="showDialog" modal header="Photo Gallery"
        :style="{ width: '80vw', height: '95vh', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '80vw', '575px': '95vw' }" :draggable="false" :resizable="false"
        class="photo-gallery-dialog">
        <div class="mdl-glry-outr-row">
            <div v-for="(image, index) in props.safari.gallery" :key="index" class="gallery-item">
                <div class="glry-item-inr"> <a :href="image.full_image_url" target="_blank" rel="noopener"
                        class="galryiitem-lnk"> <img :src="image.full_image_url" alt="Safari Image"
                            style="cursor: pointer;" /> </a> </div>
            </div>
        </div>
    </Dialog>

</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { homeJs } from "@/custom.js";
import { router, useForm } from "@inertiajs/vue3";
import MapBoxView from '@/components/Frontend/MapBoxView.vue';
import ListHelper from "@/helpers/ListHelper";
import SocialShare from '@/components/SocialShare.vue';

const location = ref({ name: 'South Africa', lat: -24.7828986, lng: 26.1870993, description: 'This is a beautiful safari location.', contact: '+27 123 4567' })
const showDialog = ref(false);
const props = defineProps({
    safari: Object,
    operatorDetail: Object,
    faqs: Array
});
const share = ref({
    safariLink: route('frontend.safari-details', { id: props?.safari?.id }),
    safariTitle: props?.safari?.title,
});


const deleteRecode = (id) => {
    sw.confirm(
        "deleteConfirm",
        id,
        "Are you sure?",
        "Safari related all data will be deleted.",
        "Yes, Change it!"
    );
};

const deleteConfirm = (id) => {
    router.delete(route("frontend.safari-delete-listing", props?.safari?.id));
};

const showAllWildlife = ref([]) // store toggle states

const toggleWildlife = (journeyIndex) => {
    showAllWildlife.value[journeyIndex] = !showAllWildlife.value[journeyIndex]
}

onMounted(() => {
    homeJs();
    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });

    location.value = {
        name: props?.safari?.location,
        lat: props?.safari?.lat,
        lng: props?.safari?.long,
        description: props?.safari?.location,
    }
    emit.emit("pageName", "Your Safari Details");
    emit.emit("pageSubTitle", "Manage and update your trips");
});

onUnmounted(() => {
    emit.off("deleteConfirm");
});

const form = useForm({
    safari_id: ''
});

const duplicateRecode = (id) => {
    form.safari_id = id;
    form.post(route('frontend.duplicate-safari'));
}


</script>