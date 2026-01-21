<template>
    <!-- banner start  -->
    <div class="bnrsec inerbnrsec safa-dtl-bnr">
        <img :src="safari?.full_thumbnail_url ?? '/frontend_assets/images/safari-dtl-banner.jpg'" alt="bannerimg"
            class="bnrimg">
        <div class="container ful-container">
            <div class="safa-dtl-bnrinr">
                <div class="safa-bnr-wrapper">
                    <div class="brd-nd-share">
                        <SocialShare :share="share" />
                    </div>
                    <div class="bnr-title-box">
                        <div class="rating-box">
                            <div class="rtng-val">
                                <div class="str-icn">
                                    <img :src="'/frontend_assets/images/star-green.svg'" alt="star icon">
                                </div>
                                <div class="rtn-num">{{ Number(safari?.safari_reviews_avg_rating).toFixed(1) }}
                                </div>
                            </div>
                        </div>
                        <div class="bnrcontent">
                            <h1 class="inerbnrhead">{{ safari?.title }}</h1>
                            <div class="bnr-price-box">
                                <ul>
                                    <li>
                                        <div class="bnr-price">
                                            <p>
                                                {{ formatLocalPrice(totalPriceWithFees) }}
                                                <span>PP</span>
                                            </p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="sty-dtl">
                                            <p>{{ safari?.day_duration }} days {{ safari?.night_duration }}
                                                nights</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="location-dtl">
                                            <div class="lcn-icn">
                                                <img :src="'/frontend_assets/images/location.svg'" alt="location icon">
                                            </div>
                                            <p>{{ safari?.country?.name }}</p>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="location-dtl">
                                            <p>Operated By: {{ safari?.user?.business_name }}</p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- banner end  -->
    <!-- list menu start  -->
    <div class="listingfiltermenu safa-dtl-list-menu">
        <div class="container">
            <ul class="listingfiltermenu-list">
                <li :class="{ active: activeSection === 'trip-overview' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('trip-overview')">Safari
                        overview</a>
                </li>
                <li :class="{ active: activeSection === 'trip-itinerary' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('trip-itinerary')">Your Safari
                        Itinerary</a>
                </li>
                <li :class="{ active: activeSection === 'included-notincluded' }">
                    <a class="cmn-butn" href="javascript:void(0)"
                        @click="scrollToSection('included-notincluded')">What's Included/Not Included</a>
                </li>
                <li :class="{ active: activeSection === 'faqs' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('faqs')">FAQ’s</a>
                </li>
                <li :class="{ active: activeSection === 'operator' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('operator')">Meet Your Safari
                        Operator</a>
                </li>
                <li :class="{ active: activeSection === 'gallery' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('gallery')">Gallery</a>
                </li>
                <li :class="{ active: activeSection === 'others-review' }">
                    <a class="cmn-butn" href="javascript:void(0)" @click="scrollToSection('others-review')">Reviews</a>
                </li>
            </ul>
        </div>
    </div>
    <!-- list menu end  -->

    <!-- safari overview sec start  -->
    <div class="safari-ov-sec inn-cmn-gap">
        <div class="container">
            <div class="safa-ov-row">
                <div class="col-md-8 left-col" id="trip-overview">
                    <div class="safa-ov-wrap inn-cmn-gap pt-0">
                        <div class="safa-ov-tttl">Safari Overview</div>
                        <p class="top_desc">
                            {{ safari?.description }}
                        </p>
                        <div class="safa-ov-tttl" style="font-size: 24px">What makes this trip special:</div>
                        <p class="top_desc" v-html="safari?.short_description ?? 'NA'">
                        </p>
                        <div class="dtl-map-outer" v-if="safari?.location && safari?.lat && safari?.long">
                            <div>
                                <MapBoxView :location="location" />
                            </div>
                        </div>
                    </div>
                    <div class="jrny-dy-sec inn-cmn-gap" id="trip-itinerary">
                        <div class="safa-ov-tttl pb-2">Your Safari Itinerary</div>
                        <div class="faq faqoutwrpr tour-journey" v-if="safari?.journeys && safari?.journeys.length > 0">
                            <div class="sfdtls-row" :class="index == 0 ? 'row-open' : ''"
                                v-for="(journey, index) in safari?.journeys" :key="journey.id">
                                <div class="sfdtls-left">
                                    <div class="sfdtls-lftpnl">
                                        <div class="sfdtls-numberhd">
                                            Day {{ index + 1 }}
                                        </div>
                                        <div class="sfdtls-dotlne"></div>
                                    </div>
                                </div>
                                <div class="sfdtls-rgt">
                                    <div class="faq-container" :class="index == 0 ? 'is-open' : ''">

                                        <div class="qution ">
                                            <h3>{{ journey?.heading ?? 'Transfer to Madikwe Game Reserve' }}</h3>
                                            <div class="rightIcon">
                                                <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                            </div>
                                        </div>
                                        <div class="anwers" style="display: block">
                                            <p>
                                                {{ journey?.subtext }}
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
                                            <template v-if="journey?.no_accommodation_included === 0">
                                                <p class="ft-ttl acc-ttl">Where you’ll stay - {{ journey?.accommodation
                                                    ?? 'NA' }}</p>
                                                <div class="acc-gal-outer"
                                                    v-if="journey?.journey_images && journey.journey_images.length > 0">
                                                    <ul>
                                                        <li v-for="image in journey?.journey_images" :key="image.id">
                                                            <div class="img-wrap">
                                                                <figure>
                                                                    <img :src="image.full_photo_url ?? '/frontend_assets/images/acc-img-1.jpg'"
                                                                        alt="safari details">
                                                                </figure>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </template>
                                            <template v-else>
                                                <span class="fw-bold fst-italic">No accommodation included</span>
                                            </template>
                                            <div class="daywldlife-wrpr">
                                                <div class="ft-ttl acc-ttl pt-3">Wildlife in {{ journey?.location?.name
                                                    ??
                                                    'NA' }}</div>

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

                    <div class="things-to-know" id="faqs">
                        <div class="safa-ov-tttl">Things to Know Before You Go</div>
                        <div class="faq">
                            <div class="faq-section-cont faqoutwrpr"
                                v-if="safari.things_to_know && safari.things_to_know.length > 0">
                                <div class="faq-container " v-for="(item, index) in safari.things_to_know" :key="index">
                                    <div class="qution">
                                        <h3>{{ item?.heading ?? 'Things to know' }}</h3>
                                        <div class="rightIcon">
                                            <img :src="'/frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                        </div>
                                    </div>
                                    <div class="anwers" style="display: block" v-html="item?.description ?? 'NA'">
                                    </div>
                                </div>
                            </div>
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
                                            <img :src="'/frontend_assets/images/nw-calndr.svg'" alt="experience icon">
                                        </div>
                                        <p>{{ operatorDetail.business_years ?? '' }} Years of Experience </p>
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

                    <div class="safa-dtl-gal inn-cmn-gap" id="gallery">
                        <div class="safa-ov-wrpr">
                            <div class="safa-ov-tttl">Gallery</div>
                            <a href="javascript:void(0)" @click.prevent="showDialog = true" class="more-photos-outer">
                                <span>View photos</span>
                            </a>
                        </div>

                        <div class="safa-gal-wrap">
                            <ul v-if="safari?.gallery && safari?.gallery.length > 0">
                                <li v-for="(image, index) in safari.gallery" :key="index">
                                    <div class="img-wrap">
                                        <figure>
                                            <img :src="image.full_image_url ?? '/frontend_assets/images/gal-1.jpg'"
                                                alt="safari details">
                                        </figure>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="wht-clnt-say inn-cmn-gap pb-0" id="others-review">
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
                                                        review?.username
                                                    }}</div>
                                                    <div class="pst-dte">
                                                        {{ ListHelper.dateFormat(review?.created_at, "MMM DD,YYYY")
                                                        }}
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
                <div class="col-md-4 right-col book-now-show-res"
                    v-if="$page?.props?.auth?.user?.role != 'SAFARI_OPERATOR' && safari.user_id != $page?.props?.auth?.user?.id && safari?.total_price > 0">
                    <div class="av-form-wrap">
                        <div class="form-outer" v-if="canBook">
                            <div class="form-title-bx">
                                <div class="form-head">{{ isEnquiryMode ? 'Plan with Operator' : 'Book Your Safari' }}</div>
                                <p v-if="isEnquiryMode">Tell us about your trip and the operator will get back to you with a custom quote.</p>
                                <p v-else>Check live availability and pricing in seconds.</p>
                            </div>
                            <!-- <div v-if="hasFutureAvailability" class="form_box"> -->
                            <div class="form_box">
                                <form @submit.prevent="handleFormSubmit">
                                    <div class="row form_row">
                                        <div class="col-6 form_col">
                                            <label>Travel dates</label>
                                            <!-- <div class="input_hldr input_hldrt book-date-fild cmn-caldr-adj"> -->
                                            <div
                                                class="input_hldr input_hldrt book-date-fild bnrfrm-inputwrp date datefrmat">
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
                                            <label>Guests</label>
                                            <div>
                                                <select name="" id="" v-model="numberOfAdults">
                                                    <option value="">Number of Adults</option>
                                                    <option v-for="n in safari.no_of_adult" :key="n" :value="n">{{ n
                                                    }}</option>
                                                </select>
                                            </div>
                                            <span class="text-danger" v-if="errors?.number_of_adults">{{
                                                errors?.number_of_adults }}</span>

                                            <div class="mt-3">
                                                <select name="" id="" v-model="numberOfChildren">
                                                    <option value="">Number of Children</option>
                                                    <option value="0">0</option>
                                                    <option v-for="n in safari.no_of_child" :key="n" :value="n">{{ n
                                                    }}</option>
                                                </select>
                                            </div>

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
                                        <div class="col-12 form_col" v-if="isEnquiryMode">
                                            <label>Message to Operator (optional)</label>
                                            <div class="input_hldr">
                                                <textarea v-model="travelerNotes" rows="3"
                                                    placeholder="Tell us about your trip preferences, special requests, or any questions you have..."></textarea>
                                            </div>
                                            <span class="text-danger" v-if="errors?.traveler_notes">{{ errors?.traveler_notes }}</span>
                                        </div>
                                        <div class="cmn-sub-inpt-txt">
                                            This trip is offered and operated by {{ safari?.user?.business_name }}.
                                            <br>
                                            All payments are securely handled by Tuskari.
                                        </div>
                                        <div class="col-12" v-if="!isEnquiryMode && (numberOfAdults || numberOfChildren)">
                                            <div class="total-price-box">
                                                <div class="left">Booking Summary</div>
                                            </div>
                                            <div>
                                                <span v-if="numberOfAdults">{{ numberOfAdults }} {{ numberOfAdults > 1 ? 'Adults' : 'Adult'  }} x {{ formatLocalPrice(pricePerAdult) }} per person</span>
                                                <br>
                                                <span v-if="numberOfChildren>0">{{ numberOfChildren }} {{ numberOfChildren > 1 ? 'Children' : 'Child'  }} x {{ formatLocalPrice(pricePerChild)  }} per person</span>
                                                <br>
                                                <span class="cmn-butn mt-2" style="padding: 4px 12px;" v-if="hasDiscountAdultPrice || hasDiscountChildPrice">Group Discount Applied</span>
                                            </div>
                                        </div>
                                        <div class="col-12" v-if="!isEnquiryMode">
                                            <div class="total-price-box">
                                                <div class="left">Total price</div>
                                                <div class="right">
                                                    <div class="price">
                                                        {{ formatLocalPrice(totalPrice) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 form_col" v-if="!isEnquiryMode">
                                            <input type="submit" value="Book now">
                                        </div>
                                        <div class="col-12 form_col" v-else>
                                            <!-- Login prompt for non-authenticated users -->
                                            <div v-if="!$page.props.isLogin" class="enquiry-login-prompt">
                                                <p>Please <a :href="route('frontend.login')">log in</a> or <a :href="route('frontend.register')">create an account</a> to submit your enquiry.</p>
                                            </div>
                                            <input type="submit" :value="isSubmittingEnquiry ? 'Submitting...' : 'Plan with Operator'" :disabled="isSubmittingEnquiry || !$page.props.isLogin">
                                        </div>
                                        <div class="col-12 form_col cht-ope" v-if="!isEnquiryMode">
                                            <button class="cmn-butn" type="button"
                                                @click.prevent="chatWithOperator(safari?.chat_room_id, safari?.user?.isGroup, [{ 'user': safari?.user }], safari?.user?.full_name)">Still
                                                got questions? Message the operator. </button>
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
                <div class="safa-ov-tttl" style="font-size: 20px" v-else>
                    Safari is not Available for booking
                </div>
            </div>
        </div>
    </div>
    <!-- safari overview sec end  -->
    <div class="feat-exp-sec npbtm inn-cmn-gap pt-0" v-if="similarSafaris.length">
        <div class="jour-you-like">
            <div class="container">
                <div class="cmn-heading ">
                    <h2 class="safa-ov-tttl">Similar Journeys You May Like</h2>
                </div>
                <div class="custmsliderwrpr">
                    <div class="feat-exp-slidr-arwwrpr">
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

                    <div class="ft-saf-kr-outrwrpr">
                        <div class="row ft-saf-kr mb-tuskarislidr">
                            <div class="col-lg-3 col-md-4 ft-exp-slide" v-for="(safari, index) in similarSafaris"
                                :key="index">
                                <SafariCard :safari="safari" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-rgtavalbty-form" :class="isOpenBookingModal ? 'open' : ''"
        :style="{ zIndex: isOpenBookingModal ? '9999' : '8' }">
        <div class="mb-rgtavalbty-form-innr">
            <a href="javascript:void(0)" class="avalblty-pop-arw" @click.prevent="isOpenBookingModalonClick">
                <Icon icon="fa7-solid:angle-up" v-if="!isOpenBookingModal" />
                <Icon icon="pepicons-pop:angle-down" v-if="isOpenBookingModal" />
            </a>
            <form @submit.prevent="handleFormSubmit">
                <div class="mb-rgtavalbty-wrpr">
                    <div v-if="!totalPrice && !durationText && !checkInDate && !checkOutDate && !isOpenBookingModal"
                        class="mb-rgtavalbty-lftpnl slect-avlblty-hdr">
                        {{ formatLocalPrice(totalPriceWithFees) }}
                        <span>PP</span>
                    </div>
                    <template v-if="!isOpenBookingModal">
                        <div class="mb-rgtavalbty-lftpnl"
                            v-if="totalPrice || durationText || checkInDate || checkOutDate">
                            <div class="right">
                                <div class="price" v-if="totalPrice">{{ formatLocalPrice(totalPrice) }}</div>
                                <div class="days" v-if="durationText">{{ durationText }}</div>
                            </div>
                            <div class="rgtavalbty-date" v-if="checkInDate && checkOutDate">{{
                                ListHelper.dateFormat(checkInDate,
                                    "MMM DD") }}-{{ ListHelper.dateFormat(checkOutDate,
                                    "MMM DD") }}</div>
                        </div>
                    </template>
                    <button type="button'" class="cmn-butn" v-if="!isOpenBookingModal">{{ isEnquiryMode ? 'Plan with Operator' : 'Book Now' }}</button>
                </div>
                <div v-if="isOpenBookingModal" class="form_box">
                    <div class="form-title-bx">
                        <div class="form-head">{{ isEnquiryMode ? 'Plan with Operator' : 'Book Your Safari' }}</div>
                        <p v-if="isEnquiryMode" style="font-size: 14px;">Tell us about your trip and the operator will get back to you with a custom quote.</p>
                        <p v-else style="font-size: 14px;">Check live availability and pricing in seconds.</p>
                    </div>
                    <div class="row form_row">
                        <div class="col-12 form_col">
                            <label>Travel dates</label>
                            <div class="input_hldr input_hldrt book-date-fild bnrfrm-inputwrp date datefrmat">
                                <DatePicker :minDate="minAvailableDate" :maxDate="maxAvailableDate"
                                    :startDate="minAvailableDate" :placeholder="'Check in'" v-model="checkInDate"
                                    :disabledDates="disabledDates" />
                                <span class="input-group-append dateicon"></span>
                            </div>
                            <span class="text-danger" v-if="errors?.check_in_date">{{
                                errors?.check_in_date }}</span>
                        </div>
                        <div class="col-12 form_col">
                            <div class="input_hldr input_hldrt book-date-fild bnrfrm-inputwrp date datefrmat">
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
                            <label>Guests</label>
                            <div>
                                <select name="" id="" v-model="numberOfAdults">
                                    <option value="">Number of Adults</option>
                                    <option v-for="n in safari.no_of_adult" :key="n" :value="n">{{ n
                                    }}</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors?.number_of_adults">{{
                                errors?.number_of_adults }}</span>
                            <div class="mt-3">
                                <select name="" id="" v-model="numberOfChildren">
                                    <option value="">Number of Children</option>
                                    <option value="0">0</option>
                                    <option v-for="n in safari.no_of_child" :key="n" :value="n">{{ n
                                    }}</option>
                                </select>
                            </div>
                            <span class="text-danger" v-if="errors?.number_of_children">{{
                                errors?.number_of_children }}</span>
                        </div>

                        <div class="col-12 form_col">
                            <label>Duration</label>
                            <div class="input_hldr">
                                <input type="text" :value="durationText ? `${durationText}` : '---'" readonly />
                            </div>
                            <span class="text-danger" v-if="errors?.duration">{{ errors?.duration
                            }}</span>
                        </div>
                        <div class="col-12 form_col" v-if="isEnquiryMode">
                            <label>Message to Operator (optional)</label>
                            <div class="input_hldr">
                                <textarea v-model="travelerNotes" rows="3"
                                    placeholder="Tell us about your trip preferences, special requests, or any questions you have..."></textarea>
                            </div>
                            <span class="text-danger" v-if="errors?.traveler_notes">{{ errors?.traveler_notes }}</span>
                        </div>

                        <p>This trip is offered and operated by {{ safari?.user?.business_name }}.
                            <br>
                            All payments are securely handled by Tuskari.
                        </p>
                        <div class="col-12" v-if="!isEnquiryMode && (numberOfAdults || numberOfChildren)">
                            <div class="total-price-box">
                                <div class="left">Booking Summary</div>
                            </div>
                            <div>
                                <span v-if="numberOfAdults">{{ numberOfAdults }} {{ numberOfAdults > 1 ? 'Adults' : 'Adult'  }} x {{ formatLocalPrice(pricePerAdult) }} per person</span>
                                <br>
                                <span v-if="numberOfChildren>0">{{ numberOfChildren }} {{ numberOfChildren > 1 ? 'Children' : 'Child'  }} x {{ formatLocalPrice(pricePerChild)  }} per person</span>
                                <br>
                                <span class="cmn-butn mt-2" style="padding: 4px 12px;" v-if="hasDiscountAdultPrice || hasDiscountChildPrice">Group Discount Applied</span>
                            </div>
                        </div>
                        <div class="total-price-box" style="padding-top: 0;" v-if="!isEnquiryMode">
                            <div class="left">Total price</div>
                            <div class="right">
                                <div class="price">{{ formatLocalPrice(totalPrice) }}</div>
                            </div>
                        </div>
                        <div class="mb-rgtavalbty-rgtpnl">
                            <button type="submit" class="cmn-butn" v-if="!isEnquiryMode">Book Now</button>
                            <template v-else>
                                <!-- Login prompt for non-authenticated users (mobile) -->
                                <div v-if="!$page.props.isLogin" class="enquiry-login-prompt">
                                    <p>Please <a :href="route('frontend.login')">log in</a> or <a :href="route('frontend.register')">create an account</a> to submit your enquiry.</p>
                                </div>
                                <button type="submit" class="cmn-butn" :disabled="isSubmittingEnquiry || !$page.props.isLogin">{{ isSubmittingEnquiry ? 'Submitting...' : 'Plan with Operator' }}</button>
                            </template>
                            <div class=" form_col cht-ope" v-if="!isEnquiryMode">
                                <button class="cmn-butn" type="button"
                                    @click.prevent="chatWithOperator(safari?.chat_room_id, safari?.user?.isGroup, [{ 'user': safari?.user }], safari?.user?.full_name)">Still
                                    got questions? Message the operator. </button>
                            </div>
                            <div class="col-12 form_col cht-stus"
                                v-if="$page.props.isLogin && $page?.props?.auth?.user?.role == 'TRAVELLER'">
                                <UserStatus :userId="safari?.user_id" v-if="safari?.fastest_reply_time"
                                    :fastestReplyTime="safari.fastest_reply_time" />
                                <UserStatus :userId="safari?.user_id" v-else />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
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
import { onMounted, ref, computed, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { router } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import SafariCard from '@/components/Frontend/SafariCard.vue';
import MapBoxView from '@/components/Frontend/MapBoxView.vue';
import ListHelper from "@/helpers/ListHelper";
import SocialShare from '@/components/SocialShare.vue';
import UserStatus from '@/components/Frontend/UserStatus.vue';
import DatePicker from "@/components/Datepicker.vue";
import axios from 'axios';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const props = defineProps({
    safari: Object,
    similarSafaris: Array,
    operatorDetail: Object,
    fullyBookedDates: Array,
    userBooking: Object,
    canBook: Boolean,
    setting: Object
});
const storeData = typeof window !== 'undefined' ? localStorage.getItem('safariBookingData') : null;
const parsed = storeData ? JSON.parse(storeData) : {};
const checkInDate = ref(parsed?.check_in_date ?? null);
const checkOutDate = ref(parsed?.check_out_date ?? null);
const numberOfAdults = ref(parsed?.number_of_adults ?? '');
const numberOfChildren = ref(parsed?.number_of_children ?? '');
const errors = ref([]);
const isOpenBookingModal = ref(false);
const travelerNotes = ref('');
const isSubmittingEnquiry = ref(false);
const showDialog = ref(false);
const activeSection = ref('trip-overview');
const dayDuration = Number(props.safari.day_duration);
const showAllWildlife = ref([]);

const location = ref({
    name: props.safari?.location || 'Unknown Location',
    lat: props.safari?.lat || 0,
    lng: props.safari?.long || 0,
    description: props.safari?.location || 'No description available.'
});

onMounted( async() => {
    homeJs();
    await initializeCurrency();
    if (numberOfAdults.value && seasonPrices.value?.adultPrice) {
        checkPriceSpecialDiscount();
    }
});

const share = ref({
    safariLink: typeof window !== 'undefined' ? route('frontend.safari-details', { id: props?.safari?.id }) : '',
    safariTitle: props?.safari?.title,
});

const scrollToSection = (id) => {
    activeSection.value = id;
    const element = document.getElementById(id);
    if (element) {
        const yOffset = -100;
        const y = element.getBoundingClientRect().top + window.pageYOffset + yOffset;

        window.scrollTo({ top: y, behavior: 'smooth' });
    }
}
const chatWithOperator = (id = 0, isGroup = '', chatMembers, chatName) => {
    if (typeof window !== 'undefined') {
        localStorage.removeItem('chatDetailsData');
        const chatDetails = {
            chatRoom: id,
            chatRoomUser: chatMembers[0].user,
            chatName: chatName,
            autoGenerateMessage: props?.safari?.autoGenerateMessage
        };
        localStorage.setItem('chatDetailsData', JSON.stringify(chatDetails));
    }

    if (typeof window !== 'undefined') {
        router.get(route('frontend.messages'));
    }
};

const nextDayOfCheckIn = computed(() => {
    if (!checkInDate.value) return null;
    const date = new Date(checkInDate.value);
    date.setDate(date.getDate() + 1);
    return date;
});

const minCheckOutDate = computed(() => {
    if (!checkInDate.value) return minAvailableDate.value > new Date() ? minAvailableDate.value : new Date();
    const date = new Date(checkInDate.value);
    date.setDate(date.getDate() + dayDuration);
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


// Watch for changes in seasonPrices to trigger group pricing check
watch(seasonPrices, (newVal, oldVal) => {
    if (JSON.stringify(newVal) !== JSON.stringify(oldVal)) {
        checkPriceSpecialDiscount();
    }
}, { deep: true });

const netDiscountedAdultPrice = ref(0);
const netDiscountedChildPrice = ref(0);
const hasDiscountAdultPrice = ref(false);
const hasDiscountChildPrice = ref(false);

const checkPriceSpecialDiscount = () => {
    if (!numberOfAdults.value || !seasonPrices.value?.adultPrice || typeof window === 'undefined') {
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
    if (typeof window !== 'undefined') {
        localStorage.setItem('safariBookingData', JSON.stringify(bookingData));
    }
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
        operator_adult_price: hasDiscountAdultPrice.value ? netDiscountedAdultPrice.value : seasonPrices.value.adultPrice,
        operator_child_price: hasDiscountChildPrice.value ? netDiscountedChildPrice.value : seasonPrices.value.childPrice,
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
        date.setHours(0, 0, 0, 0);
        return date;
    });
};

const toggleWildlife = (journeyIndex) => {
    showAllWildlife.value[journeyIndex] = !showAllWildlife.value[journeyIndex]
}

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

    props.safari?.date_range?.forEach(range => {
        if (range.available_start_date && range.available_end_date) {
            allDates = [
                ...allDates,
                ...getDatesBetween(range.available_start_date, range.available_end_date)
            ];
        }
    });

    return allDates;
});

const disabledDates = computed(() => {
    const availableSet = new Set(availableDates.value.map(d => d.toDateString()));

    // 1. Blocked ranges from backend
    let blocked = [];

    props.safari?.date_range?.forEach(range => {
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
const isOpenBookingModalonClick = () => {
    isOpenBookingModal.value = !isOpenBookingModal.value;
    document.body.classList.toggle("no-scroll", isOpenBookingModal.value);
}

const isEnquiryMode = computed(() => {
    return props.safari?.booking_mode === 'enquiry';
});

const handleEnquiry = () => {
    if (isSubmittingEnquiry.value) return;
    isSubmittingEnquiry.value = true;

    axios.post(route('frontend.create-enquiry'), {
        safari_id: props.safari.id,
        check_in_date: checkInDate.value,
        check_out_date: checkOutDate.value,
        number_of_adults: numberOfAdults.value,
        number_of_children: numberOfChildren.value || 0,
        duration: durationText.value,
        traveler_notes: travelerNotes.value,
    })
    .then(response => {
        if (response.data.redirect_url) {
            window.toaster.success('Enquiry submitted! Redirecting to messages...');

            // Store chat details so the messages page auto-opens the conversation
            if (typeof window !== 'undefined' && response.data.chat_room_id) {
                localStorage.removeItem('chatDetailsData');
                const chatDetails = {
                    chatRoom: response.data.chat_room_id,
                    chatRoomUser: props.safari?.user,
                    chatName: props.safari?.user?.full_name,
                };
                localStorage.setItem('chatDetailsData', JSON.stringify(chatDetails));
            }

            router.visit(response.data.redirect_url);
        }
    })
    .catch(err => {
        if (err.response?.data?.errors) {
            errors.value = err.response.data.errors;
        } else if (err.response?.data?.error) {
            window.toaster.error(err.response.data.error);
        } else {
            window.toaster.error('Failed to submit enquiry. Please try again.');
        }
        if (!isOpenBookingModal.value) {
            isOpenBookingModal.value = true;
        }
    })
    .finally(() => {
        isSubmittingEnquiry.value = false;
    });
};

const handleFormSubmit = () => {
    if (isEnquiryMode.value) {
        handleEnquiry();
    } else {
        handleBookSafari();
    }
};
</script>

<style scoped>
.enquiry-login-prompt {
    background: #fff3e0;
    border: 1px solid #ffcc80;
    border-radius: 6px;
    padding: 12px;
    margin-bottom: 12px;
    text-align: center;
}

.enquiry-login-prompt p {
    margin: 0;
    font-size: 14px;
    color: #e65100;
}

.enquiry-login-prompt a {
    color: #1976d2;
    font-weight: 600;
    text-decoration: underline;
}

.enquiry-login-prompt a:hover {
    color: #0d47a1;
}
</style>