<template>
    <Meta :cms="props.meta" />
    <div class="bnrsec homebnrsec new_modify_bnnr ">
        <img :src="props.homeBanner?.full_image ?? 'frontend_assets/images/bnrimg.jpg'" alt="bannerimg" class="bnrimg">
        <div class="container">
            <div class="bnrcontent">
                <!-- <div v-html="props.homeBanner?.title"></div> -->
                <div v-html="props.homeBanner?.title"></div>
                <!-- <h1 class="bnrhead">Explore Africa <br> <span class="bnrdsgn"> Your Way </span></h1> -->
                <p>{{ props.homeBanner?.short_description ?? 'NA' }}</p>
                <div class="bnr-form-wrpr new_frm_bnnr">
                    <form @submit.prevent="handleStartExploring" ref="countryModalRef">
                        <div class="bnrform">
                            <div class="bnrfrmleft-prt">
                                <div class="hm-bnrfrmleft-inr">
                                    <div class="bnrfrm-fld bnrfrm-fld-1">
                                        <div class="bnrfrm-inputwrp bnr-inputadrs bnr-loacteinput updted_lctn_input">
                                            <i class="bnrfrmicon"><img :src="'frontend_assets/images/locationicon.svg'"
                                                    alt="location"></i>
                                            <AutoComplete v-model="selectedDestinations" optionLabel="label"
                                                placeholder="Where do you want to go safari?"
                                                :suggestions="filteredDestinations" @complete="searchDestinations"
                                                @item-select="storeSelection" class="multiselect-dropdwn" />
                                        </div>
                                    </div>
                                    <div class="bnrfrm-fld bnrfrm-fld-2">
                                        <div class="bnrfrm-inputwrp date datefrmat">
                                            <DateRange v-model="form.dateRange" :minDate="new Date()" :format="formatDate" />
                                        </div>
                                    </div>
                                    <div class="bnrfrm-fld bnrfrm-fld-3">
                                        <div class="bnrfrm-inputwrp">
                                            <div class="bnrad-trveler-butn" @click="visible = !visible">
                                                <i class="bnrfrmicon"><img :src="'frontend_assets/images/usericon.svg'"
                                                        alt="location"></i>
                                                <p v-if="form.numberOfAdults || form.numberOfChildren"> {{
                                                    form.numberOfAdults }} {{ form.numberOfAdults > 1 ? 'Adults' :
                                                        'Adult' }} {{ form.numberOfChildren }} {{ form.numberOfChildren > 1
                                                        ? 'Children' : 'Child' }}</p>
                                                <p v-else>How many travellers?</p>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="hmbnr-quantity-pop" v-if="visible">
                                    <div class="ftrppup-chckbxwrpr hmbnr-quantity">

                                        <div class="fltr-trv-qunttybx">
                                            <div class="fltr-trv-quntcont">
                                                <div class="fltr-trv-qunthd">Adult</div>
                                            </div>
                                            <div class="fltr-qunt-new">
                                                <InputNumber v-model="form.numberOfAdults" showButtons
                                                    buttonLayout="horizontal" style="width: 20%" :min="0">
                                                    <template #incrementbuttonicon>
                                                        <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                                    </template>
                                                    <template #decrementbuttonicon>
                                                        <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                                    </template>
                                                </InputNumber>
                                            </div>

                                        </div>
                                        <div class="fltr-trv-qunttybx">
                                            <div class="fltr-trv-quntcont">
                                                <div class="fltr-trv-qunthd">Child</div>
                                            </div>
                                            <div class="fltr-qunt-new">
                                                <InputNumber v-model="form.numberOfChildren" showButtons
                                                    buttonLayout="horizontal" style="width: 20%" :min="0">
                                                    <template #incrementbuttonicon>
                                                        <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                                    </template>
                                                    <template #decrementbuttonicon>
                                                        <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                                    </template>
                                                </InputNumber>
                                            </div>
                                        </div>
                                         <div class="fltr-trv-qunttybx">
                                            <button type="button" class="cmn-butn" @click="visible = !visible">Done</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="cmn-butn bnrfrmbutn tskrifilter-butn">Start Exploring
                                Africa</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="whttuskari-sec hm-whttuskari-sec">
        <div class="container">
            <div class="row whttuskari-row">
                <div class="col-md-6 whttuskari-lftcol">
                    <figure class="whttuskari-imgbx">
                        <img :src="props.cms?.['what-is-tuskari']?.thumbnail_full_url ?? 'frontend_assets/images/tuskari-abtimg.jpg'"
                            alt="image">
                    </figure>
                </div>
                <div class="col-md-6 whttuskari-rgtcol">
                    <div class="whttuskari-contentbx">
                        <div class="cmn-heading">
                            <span class="cmn-subhd cmn-subhd-mb">{{ props.cms?.['what-is-tuskari']?.tag ?? ''
                                }}</span>
                            <h2 v-html="props.cms?.['what-is-tuskari']?.title ?? 'What is Tuskari'"></h2>
                            <!-- <h2>Your Gateway To Africa's Greatest Adventures</h2> -->
                        </div>
                        <p>{{ props.cms?.['what-is-tuskari']?.subtitle ?? '' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--populr-safari-->
    <div class="populr-safarisec cmn-gap">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <span class="cmn-subhd">Popular Destinations</span>
                        <h2>Most Popular Safari Destinations</h2>
                    </div>
                    <div class="feat-exp-slidr-arwwrpr">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>

                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev"><img
                                    :src="'frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow"></button>
                            <button class="ftslickarw ftslickarw-next"><img
                                    :src="'frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow"></button>
                        </div>
                    </div>
                    <a :href="route('frontend.destinations')" class="cmn-butn dsktpsafari-vwallbutn"
                        v-if="props.destinations.length > 0">View all destinations</a>
                </div>

                <div class="mb-tuskarislid-outrwrpr">
                    <div class="row populr-safr-row mb-tuskarislidr" v-if="props.destinations.length > 0">
                        <div class="col-md-3 populr-safr-col" v-for="(destination, index) in props.destinations"
                            :key="index">
                            <div class="populr-safr-item">
                                <div class="ppulr-safr-itm-top">
                                    <figure class="ppulr-safrimgbx">
                                        <img :src="destination?.full_thumbnail_url ?? 'frontend_assets/images/safari-d-img1.jpg'"
                                            alt="Safari Image">
                                    </figure>
                                    <span class="ppulr-safr-catenm">{{ destination?.country?.name ?? 'NA' }}</span>
                                    <Link :href="route('frontend.national-park-details', destination?.name)"
                                        class="sf-viewbutn">View location details <span class="viewbtnarw"><img
                                            :src="'frontend_assets/images/butnrgtarw.svg'" alt="Arrow"></span></Link>
                                </div>
                                <div class="ppulr-safr-contbx">
                                    <h3 class="ppulr-safrhd"><a
                                            :href="route('frontend.national-park-details', destination?.name)">{{
                                                destination?.name ?? 'Name' }}, {{ destination?.country?.name ?? 'NA' }}</a>
                                    </h3>
                                    <div class="saflocate-txt">{{ destination?.country?.region?.name ?? 'Africa' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row populr-safr-row mb-tuskarislidr" v-else>
                        No destinations found
                    </div>
                </div>
                <div class="mbsafari-vwallbutnbx" v-if="props.destinations.length > 0">
                    <a :href="route('frontend.destinations')" class="cmn-butn">View all destinations</a>
                </div>
            </div>
        </div>
    </div>

    <!--how it work-->
    <div class="hw-it-wrk-sec">
        <div class="container">
            <div class="hw-it-wrk-wrpr cmn-gap"
                :style="{ backgroundImage: `url(${props.cms?.['how-it-works']?.thumbnail_full_url ?? '/frontend_assets/images/howitworkimg.jpg'})` }">
                <div class="hw-it-wrk-contnt">

                    <div class="cmn-heading">
                        <span class="cmn-subhd cmn-subhd-mb cmn-subhd-wht">{{ props.cms?.['how-it-works']?.tag ?? ''
                        }}</span>
                        <h2 class="whth2" v-html="props.cms?.['how-it-works']?.title ?? ''"></h2>
                        <p> {{ props.cms?.['how-it-works']?.subtitle ?? '' }}</p>
                    </div>


                    <div class="hw-it-wrklstbx">
                        <div class="hw-it-wrk-lstitm"
                            v-for="(feature, index) in JSON.parse(props.cms?.['how-it-works']?.features || '[]')"
                            :key="index">
                            <div class="hw-it-wrklin"><img :src="'/frontend_assets/images/dshoutlineimg.svg'"
                                    alt="line">
                            </div>
                            <div class="hw-it-wrk-imgbx">
                                <img :src="feature.logo ?? '/frontend_assets/images/srchicon.svg'"
                                    alt="How it work icon">

                            </div>
                            <div class="hw-it-wrk-itmcont">
                                <h3 class="whth2">
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

    <!--feature experience-->
    <div class="feat-exp-sec cmn-gap">
        <div class="container">
            <div class="custmsliderwrpr fetursldrwrp">
                <div class="cmn-headflex-outr">
                    <div class="cmn-heading cmn-head-flx">
                        <span class="cmn-subhd">Featured Trips</span>
                        <h2>Featured Safaris</h2>
                    </div>
                    <div class="feat-exp-slidr-arwwrpr">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>

                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev"><img
                                    :src="'frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow"></button>
                            <button class="ftslickarw ftslickarw-next"><img
                                    :src="'frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow"></button>
                        </div>
                    </div>
                </div>
                <div class="feat-exp-slidr-outr feat-exp-slidr-new">
                    <div class="feat-exp-slidr-nw">
                        <div class="ft-exp-slide" v-for="(feature, index) in props.featuredSafaris" :key="index">
                            <SafariCard :safari="feature" />
                        </div>
                        <div class="" v-if="featuredSafaris.length === 0">
                                <p>No featured safaris found.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--why-tuskarisec-->

    <div class="why-tuskarisec">
        <div class="container">
            <div class="why-tuskari-wrpr cmn-gap">
                <img :src="props.cms?.['why-book-with-tuskari']?.thumbnail_full_url ?? 'frontend_assets/images/whytuskariimg.jpg'"
                    alt="Why Tuskari" class="whytuskariimg">
                <div class="why-tuskri-contwrpr">
                    <div class="cmn-heading text-center">
                        <span class="cmn-subhd cmn-subhd-mb cmn-subhd-wht">{{ props.cms?.['why-book-with-tuskari']?.tag
                            ??
                            'Why tuskari' }}</span>
                        <h2 class="whth2"
                            v-html="props.cms?.['why-book-with-tuskari']?.title ?? 'why book with Tuskari'">
                        </h2>
                    </div>
                    <div class="why-tuskri-lstwpr">
                        <div class="row why-tuskri-row">
                            <div class="col-md-3 why-tuskri-col"
                                v-for="(feature, index) in JSON.parse(props.cms?.['why-book-with-tuskari']?.features || '[]')"
                                :key="index">
                                <div class="why-tuskri-item why-tuskri-item-fst">
                                    <div class="whytuskariimgbx">
                                        <img :src="feature.logo ?? 'frontend_assets/images/handpickimg.svg'" alt="icon">
                                    </div>
                                    <div class="whytuskari-textbx">
                                        <h3 class="whytuskari-hd">{{ feature.title ?? 'Title' }}</h3>
                                        <p>{{ feature.subtitle ?? 'Every trip vetted for quality & ethics.' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--explore region-->
    <div class="exprg-sec cmn-gap">
        <div class="container">
            <div class="custmsliderwrpr">
                <div class="row exprg-row">
                    <div class="col-md-3 exprg-lftcol">
                        <div class="cmn-heading">
                            <span class="cmn-subhd cmn-subhd-mb">{{ props.cms?.['trip-regions']?.tag ?? 'Trip region'
                                }}</span>
                            <h2 v-html="props.cms?.['trip-regions']?.title ?? 'Explore by region'"></h2>
                            <p>{{ props.cms?.['trip-regions']?.subtitle ?? '' }}</p>
                        </div>


                    </div>
                    <div class="col-md-9 exprg-lftcol">

                        <div class="custmsliderwrpr">
                            <div class="feat-exp-slidr-arwwrpr">
                                <div class="featrprgr">
                                    <div class="featrprgrsafter">
                                        <div class="featrprgrsfill"></div>
                                    </div>
                                </div>

                                <div class="feat-slidrbtn-wrapr">
                                    <button class="ftslickarw ftslickarw-prev">
                                        <img :src="'frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow" />
                                    </button>
                                    <button class="ftslickarw ftslickarw-next">
                                        <img :src="'frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow" />
                                    </button>
                                </div>
                            </div>
                            <div class="prvt-reserve">
                                <div class="ft-exp-slide" v-for="(feature, index) in props.regions" :key="index">
                                    <a
                                        :href="route('frontend.safari-listings', { 'location[name]': feature?.name, 'location[type]': 'region' })">
                                        <div class="privt-resrt-card">
                                            <img :src="feature.image_url ? feature.image_url : 'frontend_assets/images/exploreimg1.jpg'"
                                                alt="private slider" />
                                            <div class="bottom-cont">
                                                <div class="h3-title cmnhd-design">{{ feature.name}}
                                                </div>
                                                <p>
                                                    {{feature.description}}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="testimorial-sec cmn-gap pt-0">
        <div class="container">
            <div class="cmn-heading text-center">
                <span class="cmn-subhd">Testimonials</span>
                <h2>Real Travellers. <br>Real Stories.</h2>
            </div>
        </div>
        <div class="wrap-test-al">
            <div class="wrap-slider-cir" v-if="props.testimonial.length > 3 || screenWidth < 1700">
                <div class="slider-testimorial" v-if="props.testimonial.length > 0">
                    <div class="slider-testimorial-item" v-for="(feature, index) in props.testimonial" :key="index">
                        <div class="test-wrap">
                            <div class="up-testimorial">
                                <div class="left-test">
                                    <div class="img-wraptest">
                                        <img :src="feature?.testimonial_user_image_path ?? 'frontend_assets/images/test-1.jpg'"
                                            alt="img">
                                    </div>
                                    <h3 class="whth2">{{ feature?.full_name ?? 'NA' }}</h3>
                                </div>
                                <div class="rgt-test">
                                    <div class="star-part">
                                        <img :src="'frontend_assets/images/star.png'" alt="img">
                                        {{ feature?.rating ?? '0' }}
                                    </div>
                                </div>
                            </div>
                            <div class="down-testimoril">
                                <h4>{{ feature?.title ?? 'Perfectly Tailored Experience' }}</h4>
                                <p>
                                    {{ feature?.description && feature.description.length > 150
                                        ? feature.description.slice(0, 150) + '...'
                                        : feature?.description }}
                                </p>
                            </div>
                        </div>
                    </div>

                </div>
                <div v-else>
                    <div class="test-wrap" style="color: #ffffff; text-align: center">
                        No testimonial found
                    </div>
                </div>
                <div v-if="props.testimonial.length > 0" class="wrap-test">
                    <a href="javascript:void(0)" class="slick-btn prev"><img
                            :src="'frontend_assets/images/ftslick-arwlft.svg'" alt="img"></a>
                    <a href="javascript:void(0)" class="slick-btn next"><img
                            :src="'frontend_assets/images/ftslick-arwrgt.svg'" alt="img"></a>
                </div>
            </div>
            <div class="slider-testimorial-mb" v-else>
                <div class="slider-testimorial-mb-rw">
                    <div class="slider-testimorial-item" v-for="(feature, index) in props.testimonial" :key="index">
                        <div class="test-wrap">
                            <div class="up-testimorial">
                                <div class="left-test">
                                    <div class="img-wraptest">
                                        <img :src="feature?.testimonial_user_image_path ?? 'frontend_assets/images/test-1.jpg'"
                                            alt="img">
                                    </div>
                                    <h3 class="whth2">{{ feature?.full_name ?? 'NA' }}</h3>
                                </div>
                                <div class="rgt-test">
                                    <div class="star-part">
                                        <img :src="'frontend_assets/images/star.png'" alt="img">
                                        {{ feature?.rating ?? '0' }}
                                    </div>
                                </div>
                            </div>
                            <div class="down-testimoril">
                                <h4>{{ feature?.title ?? 'Perfectly Tailored Experience' }}</h4>
                                <p>
                                    {{ feature?.description && feature.description.length > 150
                                        ? feature.description.slice(0, 150) + '...'
                                        : feature?.description }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <div class="meet-sec">
        <div class="wrap-meet">
            <div class="row row-meet">
                <div class="col-md-5 col-meet">
                    <div class="inr-meet">
                        <div class="cmn-subhd">{{ props.cms?.['operator-highlights']?.tag ?? 'Operator' }}</div>
                        <h2 v-html="props.cms?.['operator-highlights']?.title ?? 'Meet the People'"></h2>
                        <p> {{ props.cms?.['operator-highlights']?.subtitle ?? '' }}
                        </p>
                        <a href="javascript:void(0)" class="cmn-butn">Partner with us</a>
                    </div>
                </div>
                <div class="col-md-7 col-meet">
                    <div class="man-img-wrap">
                        <img :src="props.cms?.['operator-highlights']?.thumbnail_full_url ?? 'frontend_assets/images/main-operatorimg.jpeg'"
                            alt="img">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="the-wild-sec">
        <div class="wild-wrap">
            <img :src="props.bottomBanner?.full_image ?? 'frontend_assets/images/wild-img.jpg'" alt="img"
                class="wild-img wild-img-dsktp">
            <img :src="props.bottomBanner?.full_image ?? 'frontend_assets/images/mb-trvelbetterimg1.jpg'" alt="img" class="wild-img wild-img-mb">
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">{{ props.bottomBanner?.title ?? 'The Wild' }}</h2>
                    <p>{{ props.bottomBanner?.subtitle ?? '' }}</p>
                    <div class="form-wild-wrap">
                        <form>
                            <div class="wrap-form-inr">
                                <input type="email" placeholder="Enter your email">
                                <input type="submit" value="Join the herd">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref, computed, onUnmounted } from 'vue';
import { homeJs } from "@/custom.js";
import { router, Head } from "@inertiajs/vue3";
import _ from "lodash";
const { pickBy } = _;
import SafariCard from '@/components/Frontend/SafariCard.vue';
import DateRange from "@/components/DateRange.vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import Meta from '../../components/Frontend/Meta.vue';


const visible = ref(false);
const countryModalRef = ref(null);
const screenWidth = ref(typeof window !== 'undefined' ? window.innerWidth : 1920);

const form = reactive({
    location: '',
    countries: [],
    numberOfAdults: 0,
    numberOfChildren: 0,
    dateRange: null
});

const handleStartExploring = () => {
    const location = form.location || null;
    const adults = String(form.numberOfAdults || '').trim();
    const children = String(form.numberOfChildren || '').trim();
    const dateRange = form.dateRange || null;

    const hasLocation = location !== null;
    const hasTravelers = adults.length > 0 || children.length > 0;
    const hasDate = dateRange !== null;
    if (!hasLocation && !hasTravelers && !hasDate) {
        toaster.error('Please select location, travelers, or date');
        return;
    }

    router.visit(route('frontend.safari-listings'), {
        method: 'get',
        data: pickBy({
            location,
            numberOfAdults: adults,
            numberOfChildren: children,
            dateRange
        }),
    });
};

const props = defineProps({
    homeBanner: Object,
    cms: Object,
    testimonial: Object,
    bottomBanner: Object,
    featuredSafaris: Array,
    destinations: Array,
    countryGuides: Array,
    regions: Array,
    meta: Object
});

onMounted(() => {
    homeJs();
});

onUnmounted(() => {
    homeJs();
});

function formatDate(dates) {
    if (!dates) return '';
    if (Array.isArray(dates)) {
        const validDates = dates.filter(d => d && !isNaN(new Date(d)));
        if (validDates.length === 0) return '';
        if (validDates.length === 1) return singleFormat(validDates[0]);
        return `${singleFormat(validDates[0])} - ${singleFormat(validDates[1])}`;
    }
    return singleFormat(dates);
}

function singleFormat(date) {
    if (!date) return '';
    date = new Date(date); // make sure it's a Date object

    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'short' }); // 'Oct', 'Nov'
    const year = date.getFullYear();

    let suffix = 'th';
    if (day % 10 === 1 && day !== 11) suffix = 'st';
    else if (day % 10 === 2 && day !== 12) suffix = 'nd';
    else if (day % 10 === 3 && day !== 13) suffix = 'rd';

    return `${day}${suffix} ${month} ${year}`;
}

const searchQuery = ref([]);

const filteredCountries = computed(() => {
    if (!searchQuery.value) {
        return props.countryGuides;
    }
    return props.countryGuides.filter(item =>
        item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    );
});

const selectedDestinations = ref();
const filteredDestinations = ref([]);

const searchDestinations = (event) => {
    const query = event.query.toLowerCase()
    let results = []

    props?.countryGuides.forEach(country => {
        const countryName = country.name.toLowerCase()

        if (countryName.includes(query)) {
            results.push({
                id: country.id,
                name: country.name,
                label: country.name,
                type: "country"
            })

            country.parks.forEach(park => {
                results.push({
                    id: park.id,
                    name: park.name,
                    label: `${park.name} (${country.name})`,
                    type: "park"
                })
            })
        } else {
            country.parks.forEach(park => {
                if (park.name.toLowerCase().includes(query)) {
                    results.push({
                        id: park.id,
                        name: park.name,
                        label: `${park.name} (${country.name})`,
                        type: "park"
                    })
                }
            })
        }
    })

    filteredDestinations.value = results
}

const storeSelection = (event) => {
    const item = event.value
    form.location = {
        name: item.name,
        type: item.type
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>
