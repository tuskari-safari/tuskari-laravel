<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr serv-dashboardsec">
        <div class="row cntnt_row gx-0">
            <div class="col-lg-9 pnl_rghtCntnt_lftPart">
                <div class="booking_lft_part equal_height">
                    <div class="bookng_top_part">
                        <div class="row bookng_row">
                            <div class="col-lg-4 col-sm-6 bookng_col">
                                <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'/frontend_assets/images/nw-listing-icon.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['listingCount'] }}</strong>
                                        <p>Current Listings</p>
                                    </div>
                                </div>
                            </div>
                             <div class="col-lg-4 col-sm-6 bookng_col">
                               <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'/frontend_assets/images/nw-calender-icon.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['upcomingBookingCount'] }}</strong>
                                        <p>Upcoming bookings</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6  bookng_col">
                                <div class="bookng_box">
                                    <div class="bkng_lftIcon">
                                        <img :src="'/frontend_assets/images/nw-bk-completed.svg'" alt="">
                                    </div>
                                    <div class="bookng_rghtCntnt">
                                        <strong>{{ count['completedBookingCount'] }}</strong>
                                        <p>Completed bookings</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="trips_wrppr">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>My bookings</h2>
                            </div>
                            <div class="hdr_rght_prt">
                                <a :href="route('frontend.operator-bookings')" class="vew_all_lnk">View all
                                    <span><img :src="'/frontend_assets/images/duble_arrw.svg'" alt=""></span></a>
                            </div>
                        </div>
                        <div class="trips_table_hldr">
                            <div class="table_scroll">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="bkkng_cell">Booking Id</th>
                                            <th class="sfri_nme_cell">Safari name</th>
                                            <th class="dte_cell">Trip Date</th>
                                            <th class="prce_cell">Price</th>
                                            <th class="wd_prce_cell">Payment Type</th>
                                            <th class="nghts_cell">Duration</th>
                                            <th class="nghts_cell">Booking Status</th>
                                            <th class="status_cell">Payment Status</th>
                                            <th class="action_cell">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="booking in bookings" :key="booking.id">
                                            <td class="fnt-c-m">#{{ booking?.booking_id }}</td>
                                            <td class="fnt-c-m">{{ booking?.safari?.title }}</td>
                                            <td>{{ ListHelper.dateFormat(booking.check_in_date, "MMM DD, YYYY") }}</td>
                                            <td class="prce">${{ Math.round(booking?.total_price) }}
                                            </td>
                                            <td>  {{ booking?.is_full_paid === 1 ? 'Full Paid' : 'Deposit Paid' }}</td>
                                            <td>{{ booking?.duration }}</td>
                                            <td>
                                                <div class="stus_bttn_lke">{{booking?.status.toLowerCase().replace(/\b[a-z]/g,
                                            char => char.toUpperCase())}}</div>
                                            </td>
                                            <td>
                                                <div class="stus_bttn_lke">{{ booking?.payment_status == 'confirmed' ? 'Paid' : booking?.payment_status === 'cancelled' ? 'Refunded' : 'Unpaid' }}</div>
                                            </td>
                                            <td><a href="javascript:void(0)"
                                                    @click.prevent="handleClickBookingDetails(booking.id)"
                                                    class="stus_bttn">View details</a>
                                            </td>
                                        </tr>
                                        <tr v-if="bookings.length == 0">
                                            <td colspan="7" class="text-center">
                                                <h5>No bookings found</h5>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <DashboardMessages />
        </div>
        <div class="sved_box_wrppr">
            <div class="trips_wrppr">
                <div class="trips_hder">
                    <div class="hdr_lft_prt">
                        <h2>Current Listings</h2>
                    </div>
                    <div class="hdr_rght_prt">
                        <!-- <a :href="route('frontend.safari-create-listing')" class="cmn-butn">Create new listing</a> -->
                        <a :href="route('frontend.safari-manage-listing')" class="vew_all_lnk">View all <span><img
                                    :src="'frontend_assets/images/duble_arrw.svg'" alt=""></span></a>
                    </div>
                </div>
                <div class="custmsliderwrpr">
                    <!-- <div class="feat-exp-slidr-arwwrpr">
                        <div class="featrprgr">
                            <div class="featrprgrsafter">
                                <div class="featrprgrsfill"></div>
                            </div>
                        </div>
                        <div class="feat-slidrbtn-wrapr">
                            <button class="ftslickarw ftslickarw-prev"><img
                                    :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="Left Arrow"></button>
                            <button class="ftslickarw ftslickarw-next"><img
                                    :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="Right Arrow"></button>
                        </div>
                    </div> -->
                    <div class="ft-saf-kr-outrwrpr oprtr-saf-slider-wrpr">
                        <div class="row ft-saf-kr" v-if="mySafaris.length">
                            <div class="col-xl-3 col-lg-4 col-sm-6 ft-exp-slide" v-for="safari in mySafaris"
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
        :style="{ width: '50rem', maxWidth: '100%', height: '100%', zIndex: 1000 }">
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
                                {{ bookingDetails?.is_full_paid === 1 ? 'Full Paid' : 'Deposit Paid' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="trp-price-dtls">
                    <div class="left">
                        <span class="h3-title">{{ bookingDetails?.safari?.title }}</span>
                    </div>
                    <div class="right"><span class="price-pop">${{
                        Math.round(bookingDetails?.total_price)}}</span></div>
                </div>

                <div class="trp-cancel-dtls" v-if="bookingDetails?.status === 'CANCELLED'">
                    <div class="cancel-header">
                        <span class="cancel-badge">Cancelled</span>
                    </div>
                    <div class="cancel-details-wrap">
                        <div class="cancel-detail-item" v-if="bookingDetails?.cancel_reason">
                            <div class="left"><span>Reason:</span></div>
                            <div class="right">{{ bookingDetails?.cancel_reason }}</div>
                        </div>
                        <div class="cancel-detail-item" v-if="bookingDetails?.refund_amount">
                            <div class="left"><span>Refund amount:</span></div>
                            <div class="right"><span class="price-pop">${{
                                Math.round(bookingDetails?.refund_amount) }}</span></div>
                        </div>
                        <div class="cancel-detail-item" v-if="bookingDetails?.cancelled_at">
                            <div class="left"><span>Cancelled on:</span></div>
                            <div class="right">{{ ListHelper.dateFormat(bookingDetails?.cancelled_at, "MMM DD, YYYY") }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="trp-price-dtls-wrap" v-if="bookingDetails?.next_deposit_date !== null">
                    <div class="trp-price-dtls-left">
                        <span class="h3-title">Payment breakdown</span>
                    </div>
                    <div class="trp-price-dtls-rgt">
                        <div class="trp-price-dtls-rgt-wrp">
                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Deposit paid</span>
                                </div>
                                <div class="right"><span class="price-pop">${{
                                    Math.round(bookingDetails?.deposit_amount)
                                        }}</span></div>
                            </div>
                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Balanced amount</span>
                                </div>
                                <div class="right"><span class="price-pop">${{
                                    Math.round(bookingDetails?.next_deposit_amount)}}</span></div>
                            </div>

                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Auto pay date </span>
                                </div>
                                <div class="right"><span class="price-pop">
                                        {{ ListHelper.dateFormat(bookingDetails?.next_deposit_date, "MMM DD, YYYY") ??
                                            'N/A' }}
                                    </span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="bookingDetails?.is_full_paid === 0 && !isDepositDateValid" class="trp-price-dtls-wrap">
                    <div >
                        <span class="h3-title">Payment has not been successfully done.</span>
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
                                  <div class="ttl">Traveller name:</div>
                                    <div class="abt">
                                    {{ bookingDetails?.traveler?.full_name }}
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
                    <!-- <div class="dialog-footer-box">
                        <div class="status-change-wrap">
                            <label class="form-label">Change Status:</label>
                            <Dropdown v-model="form.bookingStatus" optionLabel="label" optionValue="value"
                                placeholder="Select a Status"
                                :options="[{ label: 'Active', value: 'ACTIVE' }, { label: 'Completed', value: 'COMPLETED' }]"
                                class="status-dropdown" :disabled="bookingDetails?.status === 'COMPLETED'" />
                        </div>

                        <div class="completion-date-wrap" v-if="bookingDetails?.completion_date">
                            <label class="form-label">Booking Completed Date:</label>
                            <div class="completed-date-text">
                                {{ ListHelper.dateFormat(bookingDetails?.completion_date, "MMMM DD, YYYY") }}
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </Dialog>
</template>
<script setup>
import { onMounted, onUnmounted, watch , computed, ref, reactive} from 'vue'
import { homeJs } from "@/custom.js";
import SafariCard from '@/components/Frontend/SafariCard.vue';
import DashboardMessages from './DashboardMessageComponent.vue';
import ListHelper from "@/helpers/ListHelper";
import { router, usePage } from '@inertiajs/vue3';


const props = defineProps({
    mySafaris: Object,
    count: Object,
    bookings: Object
});

const visible = ref(false);
const bookingDetails = ref({});
const page = usePage();

onMounted(() => {
    homeJs();
    emit.on("changeStatusConfirm", function (arg1) {
        changeStatusConfirm(arg1);
    });
     emit.emit("pageName", "Welcome Back, " + page.props.auth.user?.first_name);
     emit.emit("pageSubTitle", "Here’s today’s snapshot of your safaris");
});
const form = reactive({
    bookingStatus: '',
});

const handleClickBookingDetails = (bookingId) => {
    axios.get(route('frontend.operator-booking-details', { safariBookingId: bookingId })).then(res => {
        bookingDetails.value = res.data;
    });
    visible.value = true;
};

watch(() => form.bookingStatus, (newVal) => {
    if (newVal == 'COMPLETED' && bookingDetails.value.status == 'ACTIVE') {
        sw.confirm(
            "changeStatusConfirm",
            bookingDetails.value.id,
            "Are you sure?",
            "You want to change the status!",
            "Yes, Change it!"
        );
    }
});

const changeStatusConfirm = async (id) => {
    try {
        await router.post(route("frontend.operator-booking-status-change"), { id: id }, {
            onSuccess: () => {
                handleClickBookingDetails(id);
                isLoading.value = false;
            }
        });
    } catch (e) {
        console.error(e);
    }
};


onUnmounted(() => {
    emit.off("changeStatusConfirm");
    emit.off("deleteConfirm");
});

const isDepositDateValid = computed(() => {
  if (!bookingDetails.value?.next_deposit_date) return false;
  const today = new Date();
  today.setHours(0, 0, 0, 0); 
  const depositDate = new Date(bookingDetails.value.next_deposit_date);
  depositDate.setHours(0, 0, 0, 0); 
  return depositDate >= today; 
});

</script>

<style>
.swal2-container {
    z-index: 9999 !important;
}

.dialog-footer-box {
    margin-top: 2rem;
    padding: 1.5rem;
    background-color: #f9f9f9;
    border-top: 1px solid #ddd;
    border-radius: 0 0 12px 12px;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.status-change-wrap,
.completion-date-wrap {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.status-dropdown {
    min-width: 200px;
}

.completed-date-text {
    font-weight: 500;
    font-size: 15px;
    color: #333;
}

.form-label {
    margin: 0px !important;
}

.trpdtls-pdbutn {
    font-size: 14px;
    width: 106px;
    padding: 7px 10px;
}
.trp-cancel-dtls {
    background: #fff5f5;
    border: 1px solid #fed7d7;
    border-radius: 8px;
    padding: 16px;
    margin: 16px 0;
}

.cancel-header {
    margin-bottom: 12px;
}

.cancel-badge {
    background: #e53e3e;
    color: white;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.cancel-details-wrap {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.cancel-detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 4px 0;
}

.cancel-detail-item .left span {
    font-weight: 600;
    color: #4a5568;
}

.cancel-detail-item .right {
    color: #2d3748;
}

.trpdtls-paidbutnbx {
    display: flex;
    align-items: center;
    gap: 12px;
}

.cancel-trip-btn {
    background: #dc3545 !important;
    border: none !important;
    color: white !important;
    padding: 8px 16px !important;
    border-radius: 20px !important;
    font-weight: 600 !important;
    font-size: 14px !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    white-space: nowrap;
    letter-spacing: 0.5px;
}

.cancel-trip-btn:hover {
    background: #c82333 !important;
    border-color: #bd2130 !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(220, 53, 69, 0.2);
}

.cancel-trip-btn:active {
    transform: translateY(0);
    box-shadow: 0 1px 2px rgba(220, 53, 69, 0.2);
}

.trpdtls-pdbutn {
    font-size: 14px;
    width: 106px;
    padding: 7px 10px;
}

</style>