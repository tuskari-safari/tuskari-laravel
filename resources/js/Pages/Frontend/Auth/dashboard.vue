<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="row cntnt_row gx-0">
            <div class="col-lg-9 pnl_rghtCntnt_lftPart">
                <div class="booking_lft_part equal_height">
                    <div class="bookng_top_part">
                        <div class="row bookng_row">
                            <div class="col-lg-4 col-sm-6 bookng_col">
                                <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'frontend_assets/images/booking_icon1.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['active'] }}</strong>
                                        <p>Active bookings</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 bookng_col">
                                <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'frontend_assets/images/booking_icon2.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['cancelled'] }}</strong>
                                        <p>Cancelled bookings</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6  bookng_col">
                                <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'frontend_assets/images/booking_icon3.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['completed'] }}</strong>
                                        <p>Completed bookings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="trips_wrppr">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>My trips</h2>
                            </div>
                            <div class="hdr_rght_prt">
                                <a :href="route('frontend.my-trips')" class="vew_all_lnk">View all
                                    <span><img :src="'frontend_assets/images/duble_arrw.svg'" alt=""></span></a>
                            </div>
                        </div>
                        <div class="trips_table_hldr">
                            <div class="table_scroll">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="bkkng_id_cell">Booking ID</th>
                                            <th class="wd_sfri_nme_cell">Safari Name</th>
                                            <th class="lctn_cell">Location</th>
                                            <th class="wd_dte_cell">Date</th>
                                            <th class="wd_prce_cell">Price</th>
                                            <th class="wd_nghts_cell">Duration</th>
                                            <th class="wd_status_cell">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-if="latestBookings.length == 0">
                                            <td colspan="7" class="text-center">
                                                <h5>No bookings found</h5>
                                            </td>
                                        </tr>
                                        <tr v-for="booking in latestBookings" :key="booking.id">
                                            <td class="cell_id_label">{{ booking.booking_id ? '#' + booking.booking_id :
                                                'NA' }}
                                            </td>
                                            <td class="trips_nme_label">{{ booking?.safari?.title }}</td>
                                            <td>{{ booking?.safari?.location }}, {{ booking?.safari?.country?.name }}
                                            </td>
                                            <td>{{ ListHelper.dateFormat(booking?.created_at, "MMM DD, YYYY") }}</td>
                                            <td class="prce">{{ formatLocalPrice(booking?.total_price) }}
                                            </td>
                                            <td>{{ booking?.duration }}</td>
                                            <td>
                                                <div class="stus_cell_hlder">
                                                    <div class="stus_bttn_lke">{{
                                                        booking?.payment_status == 'confirmed' ? 'Paid' : 'Unpaid' }}
                                                    </div>
                                                    <a @click.prevent="handleClickBookingDetails(booking.id)"
                                                        href="javascript:void(0)"><img
                                                            :src="'/frontend_assets/images/optn_icon.svg'"
                                                            alt="Paid"></a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <DashboardMessageComponent />
        </div>
        <div class="sved_box_wrppr" v-if="savedSafaris.length">
            <div class="trips_wrppr">
                <div class="trips_hder">
                    <div class="hdr_lft_prt">
                        <h2>Saved safaris</h2>
                    </div>
                    <div class="hdr_rght_prt">
                        <a :href="route('frontend.saved-safaris')" class="vew_all_lnk">View all <span><img
                                    :src="'frontend_assets/images/duble_arrw.svg'" alt=""></span></a>
                    </div>
                </div>

                <div class="custmsliderwrpr mb-custmslider">
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

                    <div class="ft-saf-kr-outrwrpr">
                        <div class="row ft-saf-kr mb-tuskarislidr">
                            <div class="col-xl-3 col-lg-4 col-md-6 ft-exp-slide" v-for="safari in savedSafaris"
                                :key="safari.id">
                                <SafariCard :safari="safari" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="visible" :modal="true" :closable="true" :draggable="false" :position="'right'"
        :style="{ width: '50rem', maxWidth: '100%', height: '100%' }">
        <template #header>
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Trip details</h2>
                <p>Everything you need to know about your upcoming adventure all in one place.</p>
            </div>
        </template>

        <div class="fltr-ppupfrm">
            <div class="trpdtlspop-wrpr">
                <figure class="trpdtls-imgbx">
                    <img :src="bookingDetails?.safari?.full_thumbnail_url" alt="image"
                        style="width: 500px; display: block; margin: 0 auto;">
                </figure>

                <div class="trpdtls-tpvrfyinfo">
                    <div class="trpdtls-tpvrfy-lft">
                        <div class="trpdtls-rate">
                            <i class="ftrate-img"><img :src="'/frontend_assets/images/grnstar.svg'" alt="Rate Star"></i>
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
                            <div class="trpdtls-pdbutn">
                                {{ bookingDetails?.payment_status === 'confirmed' ? 'Paid' : 'Pending' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="trp-price-dtls">
                    <div class="left">
                        <span class="h3-title">{{ bookingDetails?.safari?.title }}</span>
                    </div>
                    <div class="right"><span class="price-pop">{{ formatLocalPrice(bookingDetails?.total_price) }}</span>
                    </div>
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
                            <li v-if="bookingDetails?.operator">
                                <div class="lft-icn">
                                    <img :src="'/frontend_assets/images/hat-icn.svg'" alt="hat">
                                </div>
                                <div class="right-cont">
                                    <div class="ttl">Operator name:</div>
                                    <div class="abt">
                                        <div class="abt_inn_img">
                                            <img :src="bookingDetails?.operator?.operator_logo_full_url" alt="user">
                                        </div>
                                        {{ bookingDetails?.operator?.business_name }}
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="pop-trip-dtlwrap">
                    <div class="bkdtls-title">Trip Details</div>
                    <p>{{ bookingDetails?.safari?.description }}</p>
                    <div class="pop-dtl-ullist">
                        <ul>
                            <li v-for="value in bookingDetails?.safari?.activity" :key="value.id">
                                <div class="left-btn">
                                    <img :src="value?.full_icon_url" alt="tick">
                                </div>
                                <p>{{ value?.title }}</p>
                            </li>
                        </ul>
                    </div>
                    <!-- <a href="javascript:void(0)" class="cmn-butn">View trip details</a> -->
                </div>
            </div>
        </div>
    </Dialog>
</template>
<script setup>
import { onMounted, ref } from 'vue'
import { homeJs } from "@/custom.js";
import SafariCard from '@/components/Frontend/SafariCard.vue'
import DashboardMessageComponent from './DashboardMessageComponent.vue';
import ListHelper from "@/helpers/ListHelper";
import { usePage } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const visible = ref(false);
const bookingDetails = ref({});
const page = usePage();

const props = defineProps({
    savedSafaris: Object,
    latestBookings: Object,
    count: Object
});

const handleClickBookingDetails = (bookingId) => {
    axios.get(route('frontend.traveller-booking-details', { safariBookingId: bookingId })).then(res => {
        bookingDetails.value = res.data;
    });
    visible.value = true;
}
onMounted(async () => {
    await initializeCurrency();
    emit.emit("pageName", "Welcome Back, " + page.props.auth.user?.first_name);
    emit.emit("pageSubTitle", "Here’s what’s next on your safari journey");

    homeJs();
});
</script>