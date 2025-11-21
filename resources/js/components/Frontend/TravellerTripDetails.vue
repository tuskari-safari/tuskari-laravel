<template>
    <div class="filtr-backdrop" data-popup="tripdetails"></div>
    <div class="filtr-rgtsdepnl" data-popup="tripdetails">
        <button class="fltrpop-closebutn" type="button" @click="closeModal">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Trip details</h2>
                <p>
                    Everything you need to know about your upcoming adventure all in one
                    place.
                </p>
            </div>
            <div class="fltr-ppupfrm">
                <div class="trpdtlspop-wrpr">
                    <figure class="trpdtls-imgbx">
                        <img :src="bookingDetails?.safari?.full_thumbnail_url" alt="image">
                    </figure>
                    <div class="trpdtls-tpvrfyinfo">
                        <div class="trpdtls-tpvrfy-lft">
                            <div class="trpdtls-rate">
                                <i class="ftrate-img"><img :src="'/frontend_assets/images/grnstar.svg'"
                                        alt="Rate Star"></i>
                                <span>{{ Number(bookingDetails?.safari?.safari_reviews_avg_rating).toFixed(1) }}</span>
                            </div>
                            <div class="trpdtls-vrfynme">Verified by Tuskari</div>
                            <div class="trp-bk-idbx" v-if="bookingDetails?.booking_id">
                                <span class="trp-bk-hd">Booking ID</span>
                                <span class="trp-bk-no">#{{ bookingDetails?.booking_id }}</span>
                            </div>
                        </div>

                        <div class="trpdtls-tpvrfy-rgt">
                            <div class="trpdtls-paidbutnbx">
                                <div class="trpdtls-pdbutn">{{ bookingDetails?.payment_status=='confirmed' ? 'Paid' : 'Pending' }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="trp-price-dtls">
                        <div class="left">
                            <span class="h3-title">{{ bookingDetails?.safari?.title }}</span>
                        </div>
                        <div class="right"><span class="price-pop">${{ bookingDetails?.total_price }}</span></div>
                    </div>
                    <div class="pop-bking-dtl-box">
                        <div class="bkdtls-title">Booking Details</div>
                        <div class="bkdtls-list">
                            <ul>
                                <li>
                                    <div class="lft-icn">
                                        <img :src="'/frontend_assets/images/mp-icn.svg'" alt="map">
                                    </div>
                                    <div class="right-cont">
                                        <div class="ttl">Location:</div>
                                        <div class="abt">{{ bookingDetails?.safari?.location }}</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="lft-icn">
                                        <img :src="'/frontend_assets/images/cal-icn.svg'" alt="calendar">
                                    </div>
                                    <div class="right-cont">
                                        <div class="ttl">Duration:</div>
                                        <div class="abt">{{ bookingDetails?.duration }}</div>
                                    </div>
                                </li>
                                <li>
                                    <div class="lft-icn">
                                        <img :src="'/frontend_assets/images/hat-icn.svg'" alt="hat">
                                    </div>
                                    <div class="right-cont">
                                        <div class="ttl">Operator name:</div>
                                        <div class="abt" v-if="bookingDetails?.operator">
                                            <div class="abt_inn_img">
                                                <img :src="bookingDetails?.operator?.operator_logo_full_url" alt="user">
                                            </div>
                                            {{ bookingDetails?.operator?.full_name }}
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="pop-trip-dtlwrap">
                        <div class="bkdtls-title">Trip Details</div>
                        <p>A three-day escape into the heart of Madikwe — South Africa’s best-kept safari secret. Stay
                            at a boutique
                            lodge, track Big Five wildlife, and unwind...</p>
                        <div class="pop-dtl-ullist">
                            <ul>
                                <li>
                                    <div class="left-btn">
                                        <img :src="'/frontend_assets/images/dti-tick.png'" alt="tick">
                                    </div>
                                    <p>Twice-daily game drives led by expert local guides</p>
                                </li>
                                <li>
                                    <div class="left-btn">
                                        <img :src="'/frontend_assets/images/dti-tick.png'" alt="tick">
                                    </div>
                                    <p>Luxury lodge stay with all-inclusive dining and sundowners</p>
                                </li>
                            </ul>
                        </div>
                        <a href="javascript:void(0)" class="cmn-butn">View trip details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import axios from 'axios';
import { ref } from 'vue';
import { onMounted } from 'vue';

const props = defineProps({
    safariBookingId: Number,
});

const bookingDetails = ref({});

const getSafariBookingDetails = () => {
    axios.get(route('frontend.traveller-booking-details', { safariBookingId: props.safariBookingId })).then(res => {
        bookingDetails.value = res.data;
    });
}

onMounted(() => {
    getSafariBookingDetails();
    
});

const closeModal = () => {
    document.body.classList.remove("no-scroll");
    document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
        panel.classList.remove("popopen");
    });
    document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
        backdrop.classList.remove("open");
    });
}

</script>