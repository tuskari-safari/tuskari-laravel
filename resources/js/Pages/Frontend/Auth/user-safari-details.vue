<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="trips_hder trips_header">
            <div class="hdr_lft_prt hdr-lft-backarw">
                <a :href="route('frontend.saved-safaris')" class="backarow"><i><img
                            :src="'/frontend_assets/images/back-blck-arrow.svg'" alt="Back Arrow"></i>
                    Back</a>
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
                                                <p>{{ formatLocalPrice(totalPriceWithFees) }}
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
                                <p class="top_desc" v-html="safari?.short_description ?? 'NA'"></p>
                            </div>
                        </div>

                        <div class="jrny-dy-sec inn-cmn-gap">
                            <div class="safa-ov-tttl">Your Journey, Day by Day</div>
                            <p class="top_desc">
                                {{ safari?.short_description ?? 'NA' }}
                            </p>
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
                                                    <div class="ft-ttl acc-ttl">Wildlife in
                                                        {{ journey?.location?.name ?? 'NA' }}</div>
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
                                                <img :src="image?.full_image_url" alt="safari details">
                                            </figure>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

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
                                        <div class="anwers" style="display: block" v-html="faq?.description ?? 'NA'">
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <h4 class="text-center">No Faq found</h4>
                                </div>
                            </div>
                        </div>

                        <div class="yr-trs-safa inn-cmn-gap">
                        <div class="safa-ov-tttl">Meet Your Safari Operator</div>
                            <div class="yr-trs-outrer trs_outr_mdfed">
                            <div class="yrs-trt-top">
                                <div class="meet-safari-logo">
                                    <img :src="operatorDetail?.operator_logo_full_url ?? '/frontend_assets/images/avatar.png'"
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

                                <div class="abt abt_dscrption">
                                    <p> {{ operatorDetail?.about_operation ?? 'NA' }}</p>
                                    <p><strong>Why choose us:</strong></p>
                                    <p v-if="operatorDetail?.why_choose_us" v-html="operatorDetail?.why_choose_us"></p>
                                    <p v-else>No data</p>
                                </div>
                            </div>
                        </div>

                        <div class="wht-clnt-say inn-cmn-gap pb-0">
                            <div class="top-sec" v-if="userBooking">
                                <div class="safa-ov-tttl">What Other Travellers Say</div>
                                <div class="">
                                    <a href="javascript:void(0);" class="cmn-butn"
                                        @click.prevent="ratingVisible = true">
                                        {{ travellerReview ? 'Update' : 'Write' }} your review
                                    </a>
                                </div>
                            </div>

                            <div class="tst_list">
                                <ul v-if="safari?.safari_reviews.length > 0" id="review-list">
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
                                                        <div class="pst-dte">{{
                                                            ListHelper.dateFormat(review?.created_at, "MMM DD,YYYY") }}
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
                    <div class="col-md-4 right-col chkout-bokk-rgtcol">
                        <div class="av-form-wrap">

                            <div class="form-outer" v-if="props.canBook">
                                <div class="form-title-bx">
                                    <div class="form-head">Book Your Safari</div>
                                    <p>Check live availability and pricing in seconds.</p>
                                </div>
                                <div class="form_box">
                                    <form @submit.prevent="handleBookSafari">
                                        <div class="row form_row">
                                            <div class="col-6 form_col">
                                                <label>Travel dates</label>
                                                <div class="input_hldr input_hldrt book-date-fild bnrfrm-inputwrp date datefrmat">
                                                    <DatePicker :minDate="minAvailableDate" :maxDate="maxAvailableDate"
                                                        :startDate="minAvailableDate" :placeholder="'Check in'"
                                                        v-model="checkInDate" :disabledDates="disabledDates" />
                                                    <span class="input-group-append dateicon"></span>
                                                </div>
                                                <span class="text-danger" v-if="errors?.check_in_date">{{
                                                    errors?.check_in_date }}</span>

                                            </div>
                                            <div class="col-6 form_col">
                                                <div class="input_hldr input_hldrt book-date-fild bnrfrm-inputwrp date datefrmat"
                                                    style="margin-top: 35px;">
                                                    <DatePicker
                                                    :minDate="checkInDate ? new Date(new Date(checkInDate).getTime() + (safari?.day_duration * 24 * 60 * 60 * 1000)) : (minAvailableDate > new Date() ? minAvailableDate : new Date())"
                                                    :maxDate="checkInDate ? new Date(new Date(checkInDate).getTime() + (safari?.day_duration * 24 * 60 * 60 * 1000)) : new Date(safari?.date_range[0]?.available_end_date)"
                                                    :startDate="checkInDate ? new Date(new Date(checkInDate).getTime() + (safari?.day_duration * 24 * 60 * 60 * 1000)) : minAvailableDate"
                                                    :placeholder="'Check out'" v-model="checkOutDate"
                                                    :disabledDates="checkInDate ? [] : getDateArray(fullyBookedDates)" />
                                                    <span class="input-group-append dateicon"></span>
                                                </div>
                                                <span class="text-danger" v-if="errors?.check_out_date">{{
                                                    errors?.check_out_date }}</span>
                                            </div>

                                            <div class="col-12 form_col">
                                                <label>Enter guest</label>
                                                <div>
                                                    <select name="" id="" v-model="numberOfAdults">
                                                        <option value="">Number of Adults</option>
                                                        <option v-for="n in safari.no_of_adult" :key="n" :value="n">{{ n
                                                        }}</option>
                                                    </select>
                                                </div>

                                                <div class="mt-3">
                                                    <select name="" id="" v-model="numberOfChildren">
                                                        <option value="">Number of Children</option>
                                                        <option value="0">0</option>
                                                        <option v-for="n in safari.no_of_child" :key="n" :value="n">{{ n
                                                        }}</option>
                                                    </select>
                                                </div>
                                                <span class="text-danger" v-if="errors?.number_of_adults">{{
                                                    errors?.number_of_adults }}</span>
                                                <span class="text-danger" v-if="errors?.number_of_children">{{
                                                    errors?.number_of_children }}</span>
                                            </div>

                                            <div class="col-12 form_col">
                                                <label>Duration</label>
                                                <div class="input_hldr">
                                                    <input type="text" :value="durationText ? `${durationText}` : '---'"
                                                        readonly />
                                                </div>
                                                <span class="text-danger" v-if="errors?.duration">{{ errors?.duration
                                                    }}</span>
                                            </div>
                                            <div class="col-12" v-if="numberOfAdults || numberOfChildren">
                                                <div class="total-price-box">
                                                    <div class="left">Booking Summary</div>
                                                </div>
                                                <div>
                                                    <span v-if="numberOfAdults">{{ numberOfAdults }} {{ numberOfAdults > 1 ? 'Adults' : 'Adult'  }} x {{ formatLocalPrice(pricePerAdult) }} per person</span>
                                                    <p v-if="numberOfChildren>0">{{ numberOfChildren }} {{ numberOfChildren > 1 ? 'Children' : 'Child'  }} x {{ formatLocalPrice(pricePerChild)  }} per person</p>
                                                    <span class="cmn-butn" style="padding: 6px 13px;" v-if="hasDiscountAdultPrice || hasDiscountChildPrice">Group Discount Applied</span>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="total-price-box">
                                                    <div class="left">Total price</div>
                                                    <div class="right">
                                                        <div class="price">{{ formatLocalPrice(totalPrice) }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 form_col">
                                                <input type="submit" value="Book now">
                                            </div>
                                            <div class="col-12 form_col cht-ope">
                                                <button class="cmn-butn" type="button"
                                                    @click.prevent="chatWithOperator(safari?.chat_room_id, safari?.user?.isGroup, [{ 'user': safari?.user }], safari?.user?.full_name)">Still
                                                    got question? Message the operator.</button>
                                            </div>

                                            <div class="col-12 form_col cht-stus"
                                                v-if="$page.props.isLogin && $page?.props?.auth?.user?.role == 'TRAVELLER'">
                                                <UserStatus :userId="safari?.user_id" v-if="safari?.fastest_reply_time"
                                                    :fastestReplyTime="safari.fastest_reply_time" />
                                                <UserStatus :userId="safari?.user_id" v-else />
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div v-else>
                                <div class="form-title-bx">
                                    <p> You cannot book this safari yet as your previous safari booking is still
                                        ongoing.</p>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- safari overview sec end  -->
        <div class="bttm_bttn_hlder mt-3">
            <div class="allbttn_hlder">
                <div class="indvdl_bttn_hlder">
                    <a class="cmn-butn" href="javascript:void(0)" @click="isVisible">Report a Problem</a>
                </div>
            </div>
        </div>
        <ReportIssueDialog v-model:visible="reportProblemVisible" :key="reportProblemVisible" />
    </div>
    <Dialog v-model:visible="ratingVisible" modal header="Write your review"
        :style="{ width: '40vw', maxHeight: '90vh' }" :breakpoints="{ '1199px': '80vw', '575px': '95vw' }"
        :draggable="false" :resizable="false" class="review-dialog">
        <div class="popup_form_outer">
            <div class="form_box add_card_popup">
                <form class="write-rvw-popup" @submit.prevent="handleClickListingRating">
                    <div class="row form_row">
                        <div class="col-12 form_col">
                            <div class="rating-outer-pop">
                                <div class="left">Rate your trip</div>
                                <div class="right_strs">
                                    <Rating v-model="form.rating">
                                        <template #onicon>
                                            <Icon icon="tabler:star-filled" width="25" height="25" />
                                        </template>
                                        <template #officon>
                                            <Icon icon="tabler:star" width="25" height="25" />
                                        </template>
                                    </Rating>
                                    <span class="text-danger" v-if="form.errors.rating">{{ form.errors.rating }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <label>Write your review</label>
                            <div class="input_hldr">
                                <InputText type="text" v-model="form.heading" placeholder="Write review heading" />
                                <span class="text-danger" v-if="form.errors.heading">{{ form.errors.heading }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <div class="input_hldr">
                                <Textarea v-model="form.details" placeholder="Write your thoughts" rows="4"
                                    autoResize />
                                <span class="text-danger" v-if="form.errors.details">{{ form.errors.details }}</span>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <FrontendSubmitButton :isLoading="form.processing" :value="'Submit'" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Dialog>

    <Dialog v-model:visible="showDialog" modal header="Photo Gallery"
        :style="{ width: '80vw', height: '95vh', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '80vw', '575px': '95vw' }" :draggable="false" :resizable="false"
        class="photo-gallery-dialog">
        <div class="mdl-glry-outr-row">
            <div v-for="(image, index) in props.safari.gallery" :key="index" class="gallery-item">
                <div class="glry-item-inr"> <a :href="image.full_image_url" target="_blank" rel="noopener"
                        class="galryiitem-lnk"> <img :src="image.full_image_url" alt="Safari Image"
                            style="cursor: pointer;" /> </a>
                </div>
            </div>
        </div>
    </Dialog>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { router, useForm } from "@inertiajs/vue3";
import MapBoxView from '@/components/Frontend/MapBoxView.vue';
import ListHelper from "@/helpers/ListHelper";
import SocialShare from '@/components/SocialShare.vue';
import UserStatus from '@/components/Frontend/UserStatus.vue';
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import ReportIssueDialog from "@/components/Frontend/ReportProblemDialog.vue";
import DatePicker from "@/components/Datepicker.vue";
import { computed } from 'vue';

const location = ref({ name: 'South Africa', lat: -24.7828986, lng: 26.1870993, description: 'This is a beautiful safari location.', contact: '+27 123 4567' })
const storeData = localStorage.getItem('safariBookingData');
const parsed = storeData ? JSON.parse(storeData) : {};
const showDialog = ref(false);
const ratingVisible = ref(false);
const reportProblemVisible = ref(false);
const checkInDate = ref(parsed?.check_in_date ?? null);
const checkOutDate = ref(parsed?.check_out_date ?? null);
const numberOfAdults = ref(parsed?.number_of_adults ?? '');
const numberOfChildren = ref(parsed?.number_of_children ?? '');
const errors = ref([]);
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const props = defineProps({
    safari: Object,
    operatorDetail: Object,
    faqs: Array,
    travellerReview: Object,
    fullyBookedDates: Array,
    userBooking: Object,
    bookingData: Object,
    version: Number,
    canBook: Boolean,
    setting: Object
});

const share = ref({
    safariLink: route('frontend.safari-details', { id: props?.safari?.id }),
    safariTitle: props?.safari?.title,
});

const form = useForm({
    safari_id: props?.safari?.id,
    rating: props?.travellerReview?.rating ?? '',
    heading: props?.travellerReview?.heading ?? '',
    details: props?.travellerReview?.details ?? '',
});

const handleClickListingRating = () => {
    const ratingId = { ratingId: props.travellerReview?.id };
    const routeName = 'frontend.safari-listing-review-store';
    const routeParams = ratingId ? route(routeName, ratingId) : route(routeName);
    form.post(routeParams, {
        onSuccess: () => {
            ratingVisible.value = false;
            setTimeout(() => {
                const reviewSection = document.getElementById("review-list")?.scrollIntoView({ behavior: "smooth" });
                if (reviewSection) {
                    reviewSection.scrollIntoView({
                        behavior: "smooth",
                        block: "start",
                    });
                }
            }, 500);
        },
    });
};

onMounted(async() => {
    homeJs();
    await initializeCurrency();
     if (numberOfAdults.value && seasonPrices.value?.adultPrice) {
        checkPriceSpecialDiscount();
    }
    location.value = {
        name: props?.safari?.location,
        lat: props?.safari?.lat,
        lng: props?.safari?.long,
        description: props?.safari?.location,
    }

    emit.emit("pageName", "Your Saved Safaris");
    emit.emit("pageSubTitle", "Safaris you’ve bookmarked for later");
});

const chatWithOperator = (id = 0, isGroup = '', chatMembers, chatName) => {
    localStorage.removeItem('chatDetailsData');
    const chatDetails = {
        chatRoom: id,
        chatRoomUser: chatMembers[0].user,
        chatName: chatName,
        autoGenerateMessage: props?.safari?.autoGenerateMessage
    };
    localStorage.setItem('chatDetailsData', JSON.stringify(chatDetails));
    router.get(route('frontend.messages'));
};
const isVisible = () => {
    reportProblemVisible.value = true
}

const dayDuration = Number(props.safari.day_duration);

const nextDayOfCheckIn = computed(() => {
    if (!checkInDate.value) return null;
    const date = new Date(checkInDate.value);
    date.setDate(date.getDate() + 1);
    return date;
});

const checkOutMaxDate = computed(() => {
    if (!checkInDate.value || !props.safari?.date_range?.length) return null;

    const max = new Date(checkInDate.value);
    max.setDate(max.getDate() + dayDuration);

    // Get the latest available_end_date from the date range
    const latestEnd = props.safari?.date_range
        .map(r => new Date(r.available_end_date))
        .sort((a, b) => b - a)[0]; // latest end date

    return max > latestEnd ? latestEnd : max;
});


function isDateInRange(date, startString, endString) {
    const d = new Date(date)
    const start = new Date(startString)
    const end = new Date(endString)

    if (isNaN(d) || isNaN(start) || isNaN(end)) return false

    return d >= start && d <= end
}
const selectedSeason = ref(null);
const seasonPrices = computed(() => {
    const safari = props.safari;
    const checkIn = new Date(checkInDate.value);
    const checkOut = new Date(checkOutDate.value);

    if (!safari || !safari.seasonal_pricings?.length) {
        selectedSeason.value = null;
        return { adultPrice: 0, childPrice: 0 };
    }

    const seasonalPricing = safari.seasonal_pricings;

    // Helper — check if date is in range
    const isDateInRange = (date, start, end) => {
        const d = new Date(date);
        return d >= new Date(start) && d <= new Date(end);
    };

    // Find season matching check-in or check-out
    let activeSeason =
        seasonalPricing.find(s =>
            isDateInRange(checkIn, s.available_start_date, s.available_end_date)
        ) ||
        seasonalPricing.find(s =>
            isDateInRange(checkOut, s.available_start_date, s.available_end_date)
        );

    // Find the "HIGH" season for fallback
    const highSeason = seasonalPricing.find(s => s.season?.toUpperCase() === 'HIGH');

    // If no matching season → fallback to HIGH (or first available)
    if (!activeSeason) {
        activeSeason = highSeason || seasonalPricing[0];
    }

    selectedSeason.value = activeSeason.season || '';

    // Determine final prices
    const adultPrice =
        activeSeason.adult_price ||
        highSeason?.adult_price ||
        0;

    const childPrice =
        activeSeason.child_price ||
        highSeason?.child_price ||
        0;


    return {
        adultPrice,
        childPrice,
    };
});

// Watch for changes in numberOfAdults to trigger group pricing check
watch([numberOfAdults, numberOfChildren], ([newAdults, newChildren], [oldAdults, oldChildren]) => {
    if (newAdults !== oldAdults || newChildren !== oldChildren) {
        checkPriceSpecialDiscount();
    }
});

watch(seasonPrices, () => {
    if (numberOfAdults.value && seasonPrices.value?.adultPrice) {
        checkPriceSpecialDiscount();
    }
}, { deep: true });

const netDiscountedAdultPrice = ref(0);
const netDiscountedChildPrice = ref(0);
const hasDiscountAdultPrice = ref(false);
const hasDiscountChildPrice = ref(false);

const checkPriceSpecialDiscount = () => {
    if (!numberOfAdults.value || !seasonPrices.value?.adultPrice) {
        return;
    }
    let data = {
        season: selectedSeason.value,
        safari_id: props.safari?.id,
        noOfAdults: numberOfAdults.value,
        noOfChildren: numberOfChildren.value
    }

    axios.get(route('frontend.check-price-special-discount'), { params: data })
        .then(res => {
            const response = res.data;
            netDiscountedAdultPrice.value = response.net_adult_price;
            hasDiscountAdultPrice.value = response.has_adult_discount;
            netDiscountedChildPrice.value = response.net_child_price;
            hasDiscountChildPrice.value = response.has_child_discount;
        })
        .catch(err => {
            console.error('Error fetching group discount:', err);
        });
};

const pricePerAdult = ref(0);
const pricePerChild = ref(0);

const totalAdultPrice = computed(() => {
    const platformFeeRate = (Number(props.setting?.platform_fee) || 0) / 100;
    const stripeFeeRate = (Number(props.setting?.stripe_processing_fee) || 0) / 100;

    const adults = Number(numberOfAdults.value) || 0;
    if (!adults) return 0;
    const baseAdultPrice = hasDiscountAdultPrice.value ? netDiscountedAdultPrice.value : Number(seasonPrices.value?.adultPrice);
    pricePerAdult.value = baseAdultPrice + (baseAdultPrice * platformFeeRate) + (baseAdultPrice * stripeFeeRate);
    const totalAdltPrice = pricePerAdult.value * adults;
    
    return totalAdltPrice;
});


const totalChildPrice = computed(() => {
    pricePerChild.value = hasDiscountChildPrice.value ? netDiscountedChildPrice.value : seasonPrices.value?.childPrice;
    return  pricePerChild.value * numberOfChildren.value;
});

const totalPrice = computed(() => {
    return totalAdultPrice.value + totalChildPrice.value;
});

const durationText = computed(() => {
    if (!checkInDate.value || !checkOutDate.value) return '';

    const inDate = new Date(checkInDate.value);
    const outDate = new Date(checkOutDate.value);

    const diffInMs = outDate.getTime() - inDate.getTime();
    const diffInDays = Math.round(diffInMs / (1000 * 60 * 60 * 24));

    if (diffInDays <= 0) return '';
    return `${diffInDays} ${diffInDays === 1 ? 'day' : 'days'}`;
});

const handleBookSafari = () => {
    const bookingData = {
        check_in_date: checkInDate.value,
        check_out_date: checkOutDate.value,
        number_of_adults: numberOfAdults.value,
        number_of_children: numberOfChildren.value
    }
    localStorage.setItem('safariBookingData', JSON.stringify(bookingData));
    router.post('/safari-booking', {
        safari_id: props.safari.id,
        check_in_date: checkInDate.value,
        check_out_date: checkOutDate.value,
        number_of_adults: numberOfAdults.value,
        number_of_children: numberOfChildren.value,
        duration: durationText.value,
        total_price: totalPrice.value,
        total_adult_price: totalAdultPrice.value,
        total_child_price: totalChildPrice.value,
        operator_adult_price: hasDiscountAdultPrice.value? netDiscountedAdultPrice.value : seasonPrices.value.adultPrice,
        operator_child_price: hasDiscountChildPrice.value? netDiscountedChildPrice.value : seasonPrices.value.childPrice,
        hasDiscountAdultPrice: hasDiscountAdultPrice.value,
        hasDiscountChildPrice: hasDiscountChildPrice.value
    }, {
        onSuccess: () => {
            console.log('Booking saved successfully.');
        },
        onError: (err) => {
            errors.value = err;
            if (!isOpenBookingModal.value) {
                isOpenBookingModal.value = true;
            }
        }
    });
};
const getDateArray = (dates) => {
    if (!Array.isArray(dates)) return [];

    return dates.map(dateStr => {
        const date = new Date(dateStr);
        date.setHours(0, 0, 0, 0); // normalize time
        return date;
    });
};
const showAllWildlife = ref([])

const toggleWildlife = (journeyIndex) => {
    showAllWildlife.value[journeyIndex] = !showAllWildlife.value[journeyIndex]
}

// const hasFutureAvailability = computed(() => {
//     return props.safari.date_range?.some(
//         range => new Date(range.available_end_date) >= new Date()
//     );
// });

const minAvailableDate = computed(() => {
    if (!props.safari?.date_range?.length) {
        return new Date();
    }

    const today = new Date();

    // only keep ranges that end today or later
    const futureRanges = props.safari.date_range.filter(r => {
        return new Date(r.available_end_date) >= today;
    });

    if (!futureRanges.length) {
        return today; // fallback
    }

    // get the earliest available_start_date among future ranges
    const earliest = futureRanges
        .map(r => new Date(r.available_start_date))
        .sort((a, b) => a - b)[0];

    return earliest > today ? earliest : today;
});

const maxAvailableDate = computed(() => {
    if (!props.safari?.date_range?.length) {
        return new Date();
    }

    const today = new Date();

    // only keep ranges that end today or later
    const futureRanges = props.safari.date_range.filter(r => {
        return new Date(r.available_end_date) >= today;
    });

    if (!futureRanges.length) {
        return today; // fallback
    }

    // get the latest available_end_date among future ranges
    return futureRanges
        .map(r => new Date(r.available_end_date))
        .sort((a, b) => b - a)[0];
});

const getDatesBetween = (start, end) => {
    const dates = [];
    let current = new Date(start);
    const last = new Date(end);

    while (current <= last) {
        dates.push(new Date(current));
        current.setDate(current.getDate() + 1);
    }
    return dates;
}

const availableDates = computed(() => {
    if (!props.safari?.date_range?.length) return [];

    let allDates = [];

    props.safari.date_range.forEach(range => {
        if (range.available_start_date && range.available_end_date) {
            allDates = [
                ...allDates,
                ...getDatesBetween(range.available_start_date, range.available_end_date)
            ];
        }
    });

    return allDates;
});
const minCheckOutDate = computed(() => {
    if (!checkInDate.value) return minAvailableDate.value > new Date() ? minAvailableDate.value : new Date();
    const date = new Date(checkInDate.value);
    date.setDate(date.getDate() + dayDuration);
    return date;
});

const disabledDates = computed(() => {
    const availableSet = new Set(availableDates.value.map(d => d.toDateString()));

    // 1. Blocked ranges from backend
    let blocked = [];
    props.safari.date_range.forEach(range => {
        if (range.blocked_start_date && range.blocked_end_date) {
            blocked.push(...getDatesBetween(range.blocked_start_date, range.blocked_end_date));
        }
    });

    // 2. Dates outside available ranges
    const min = minAvailableDate.value;
    const max = maxAvailableDate.value;
    let allBetween = getDatesBetween(min, max);
    let notAvailable = allBetween.filter(d => !availableSet.has(d.toDateString()));

    // 3. Fully booked dates (already array of strings like "2025-08-25")
    let fullyBooked = props.fullyBookedDates?.map(d => new Date(d)) || [];

    return [...blocked, ...notAvailable, ...fullyBooked];
});

const totalPriceWithFees = computed(() => {
    const basePrice = Number(props.safari?.total_price) || 0;
    const platformFeePercent = Number(props.setting?.platform_fee) || 0;
    const stripeFeePercent = Number(props.setting?.stripe_processing_fee) || 0;
    const platformFee = (basePrice * platformFeePercent) / 100;
    const stripeFee = (basePrice * stripeFeePercent) / 100;
    return Math.round(basePrice + platformFee + stripeFee);
});

</script>
