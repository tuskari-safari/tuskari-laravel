<template>
    <div class="panel_rght_cntnt_area">
        <div class="trips_wrppr mt-0">
            <div class="trips_hder trips_header">
                <div class="hdr_lft_prt">
                    <p v-if="totalBookingCount > 0">{{ totalBookingCount < 10 ? '0' + totalBookingCount :
                        totalBookingCount }} active bookings showing...</p>
                </div>
                <div class="hdr_rght_prt">
                    <div class="srtng_box">
                        <button type="button" class="cmn-butn wht-butn dashboard-restbutn mr-2" @click="resetSearch"
                            style="border: 1px solid #e2e5eb;">
                            <Icon icon="iconamoon:close-bold" />
                        </button>
                        <div class="srtng_txt_prt">
                            <p>Filter by:</p>
                        </div>
                        <div class="sortng_input_hlder">
                            <div class="sortng_input_col">
                                <Multiselect placeholder="Select" v-model="form.day_duration"
                                    class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                    :searchable="true" :create-option="false" :options="[
                                        { value: 'Last 7 days', label: 'Last 7 days' },
                                        { value: 'Last 30 days', label: 'Last 30 days' },
                                        { value: 'Last 2 Months', label: 'Last 2 Months' }
                                    ]" />
                            </div>
                            <div class="sortng_input_col">
                                <Multiselect placeholder="Select" v-model="form.safari_type"
                                    class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                    :searchable="true" :create-option="false" :options="safariTypes" />
                            </div>
                            <div class="sortng_input_col">
                                <Multiselect placeholder="Booking Type" v-model="form.booking_type"
                                    class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                    :searchable="true" :create-option="false" :options="[
                                        { value: '', label: 'All' },
                                        { value: 'bookings', label: 'Confirmed Bookings' },
                                        { value: 'enquiries', label: 'Enquiries' }
                                    ]" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="trips_table_hldr bookd_trps_table_hlder">
                <div class="table_scroll">
                    <table>
                        <thead>
                            <tr>
                                <th class="bkkng_id_cell">Booking ID</th>
                                <th class="wd_sfri_nme_cell">Safari Name</th>
                                <th class="lctn_cell">Location</th>
                                <th class="wd_dte_cell">Date</th>
                                <th class="wd_prce_cell">Total</th>
                                <th class="wd_prce_cell">Deposit Paid</th>
                                <th class="wd_nghts_cell">Duration</th>
                                <th class="wd_status_cell">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-if="bookings.data.length == 0">
                                <td colspan="7" class="text-center">
                                    <h5>No bookings found</h5>
                                </td>
                            </tr>
                            <tr v-for="booking in bookings.data" :key="booking.id">
                                <td class="cell_id_label">{{ booking.booking_id ? '#' + booking.booking_id : 'NA' }}
                                </td>
                                <td class="trips_nme_label">{{ booking?.safari?.title }}</td>
                                <td>{{ booking?.safari?.location }}, {{ booking?.safari?.country?.name }}</td>
                                <td>{{ ListHelper.dateFormat(booking?.check_in_date, "MMMM DD, YYYY") }}</td>
                                <td class="prce">{{ formatLocalPrice(booking?.total_price) }}</td>
                                <td class="prce" v-if="booking?.deposit_amount > 0">${{
                                    Number(booking?.deposit_amount).toLocaleString('en-GB') }}</td>
                                <td class="prce" v-else>Paid in Full</td>
                                <td>{{ booking?.duration }}</td>
                                <td>
                                    <div class="stus_cell_hlder">
                                        <div v-if="booking.is_enquiry" :class="'enquiry-status-badge enquiry-' + booking.enquiry_status">
                                            {{ capitalizeFirst(booking.enquiry_status) }}
                                        </div>
                                        <div v-else class="stus_bttn_lke">{{
                                            booking?.status }}</div>
                                        <a v-if="booking.is_enquiry && booking.enquiry_status === 'pending'"
                                            @click.prevent="goToChat(booking.chat_room_id)"
                                            href="javascript:void(0)" title="View Chat">
                                            <Icon icon="mdi:chat" width="20" />
                                        </a>
                                        <a v-else-if="booking.is_enquiry && booking.enquiry_status === 'quoted'"
                                            @click.prevent="handleEnquiryAction(booking)"
                                            href="javascript:void(0)" title="View Quote">
                                            <Icon icon="mdi:currency-usd" width="20" />
                                        </a>
                                        <a v-else @click.prevent="handleClickBookingDetails(booking.id)"
                                            href="javascript:void(0)"><img
                                                :src="'/frontend_assets/images/optn_icon.svg'" alt="Paid"></a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="common-pagination save-pagination mb-5">
        <Pagination :pagination="bookings" route-name="frontend.my-trips" :extraParams="pickBy(form)" />
    </div>

    <!-- Booking details modal -->
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
                                {{ bookingDetails?.is_full_paid === 1 ? 'Paid in Full' : 'Deposit Paid' }}
                            </div>
                            <Button v-if="canCancelBooking && bookingDetails?.status !== 'CANCELLED'" type="button"
                                class="cancel-trip-btn" @click="cancelModalVisible = true">Cancel Trip</Button>
                        </div>
                    </div>
                </div>

                <div class="trp-price-dtls">
                    <div class="left">
                        <span class="h3-title">{{ bookingDetails?.safari?.title }}</span>
                    </div>
                    <div class="right"><span class="price-pop">{{ formatLocalPrice(bookingDetails?.total_price) }}</span></div>
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
                            <div class="right"><span class="price-pop">{{formatLocalPrice(bookingDetails?.refund_amount) }}</span></div>
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
                                    <span>Already Paid</span>
                                </div>
                                <div class="right"><span class="price-pop">{{formatLocalPrice(bookingDetails?.deposit_amount)}}</span></div>
                            </div>
                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Remaining Balance</span>
                                </div>
                                <div class="right"><span class="price-pop">{{formatLocalPrice(bookingDetails?.next_deposit_amount)}}</span></div>
                            </div>

                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Next Payment Date </span>
                                </div>
                                <div class="right"><span class="price-pop">
                                        {{ ListHelper.dateFormat(bookingDetails?.next_deposit_date, "MMM DD, YYYY") ??
                                            'N/A' }}
                                    </span></div>
                            </div>
                            <div class="top-sec price-top-sec" v-if="bookingDetails?.is_full_paid == 0 && bookingDetails?.status !== 'CANCELLED'">
                                <div class="" v-if="bookingDetails?.is_full_paid !== 1 && isDepositDateValid">
                                    <button type="button" @click="payManual" class="cmn-butn" v-if="!paymentLoading">Pay
                                        now</button>
                                    <SpinnerOverlay v-if="paymentLoading" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="bookingDetails?.is_full_paid === 0 && !isDepositDateValid" class="trp-price-dtls-wrap">
                    <div>
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
                    <!-- <template v-if="bookingDetails?.status == 'COMPLETED'"> -->
                    <div v-if="travelerReview" class="review-card">
                        <div class="bkdtls-title">Your Review</div>
                        <div class="review-header">
                            <div class="review-text">
                                <p class="review-heading">{{ travelerReview.heading }}</p>
                                <p class="review-details">{{ travelerReview.details }}</p>
                            </div>
                            <div class="review-rating">
                                ⭐ {{ travelerReview.rating }}
                            </div>
                        </div>
                    </div>
                    <div class="top-sec">
                        <div class="">
                            <a href="javascript:void(0);" class="cmn-butn" @click.prevent="ratingVisible = true">
                                {{ travelerReview !== null ? 'Update' : 'Write' }} your review
                            </a>
                        </div>
                    </div>
                    <!-- </template> -->
                </div>
            </div>
        </div>
    </Dialog>

    <!-- Rating modal -->
    <Dialog v-model:visible="ratingVisible" modal header="Write your review" :position="'right'"
        :style="{ width: '35vw', maxHeight: '90vh' }" :breakpoints="{ '1199px': '80vw', '575px': '95vw' }"
        :draggable="false" :resizable="false" class="review-dialog">
        <div class="popup_form_outer">
            <div class="form_box add_card_popup">
                <form class="write-rvw-popup" @submit.prevent="handleClickListingRating">
                    <div class="row form_row">
                        <div class="col-12 form_col">
                            <div class="rating-outer-pop">
                                <div class="left">Rate your trip</div>
                                <div class="right_strs">
                                    <Rating v-model="ratingForm.rating">
                                        <template #onicon>
                                            <Icon icon="tabler:star-filled" width="25" height="25" />
                                        </template>
                                        <template #officon>
                                            <Icon icon="tabler:star" width="25" height="25" />
                                        </template>
                                    </Rating>
                                    <span class="text-danger" v-if="ratingForm.errors.rating">{{
                                        ratingForm.errors.rating
                                        }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-12 form_col">
                            <label>Write your review</label>
                            <div class="input_hldr">
                                <InputText type="text" v-model="ratingForm.heading"
                                    placeholder="Write review heading" />
                                <span class="text-danger" v-if="ratingForm?.errors?.heading">{{
                                    ratingForm.errors.heading
                                    }}</span>
                            </div>
                        </div>
                        <div class="col-12 form_col">
                            <div class="input_hldr">
                                <Textarea v-model="ratingForm.details" placeholder="Write your thoughts" rows="4"
                                    autoResize />
                                <span class="text-danger" v-if="ratingForm.errors.details">{{ ratingForm.errors.details
                                    }}</span>
                            </div>
                        </div>
                        <div class="col-12 form_col">
                            <FrontendSubmitButton :isLoading="ratingForm.processing" :value="'Submit'" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </Dialog>

    <!-- Payment success modal -->
    <Dialog v-model:visible="paymentSuccessModalVisible" modal :style="{ width: '28vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '50vw', '575px': '80vw' }" :draggable="false" :resizable="false"
        class="pyment-suces-popup">
        <div class="paymnt-suces-title">Success</div>
        <p>Your full payment has been done successfully.
        </p>
        <div class="pymnt-sucesiconbx">
            <Icon icon="material-symbols:check-rounded" width="24" height="24" />
        </div>

        <a class="cmn-butn pyment-sucesbutn" href="javascript:void(0)" @click="goToMyTrips">Ok</a>
    </Dialog>

    <!-- Payment error modal -->
    <Dialog v-model:visible="paymentErrorModalVisible" modal :style="{ width: '28vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '50vw', '767px': '80vw' }" :draggable="false" :resizable="false"
        class="pyment-error-popup">
        <!-- <img src="/frontend_assets/images/planet.png" alt=""> -->
        <div class="pymnt-eroriconbx">
            <Icon icon="bx:error" width="24" height="24" />
        </div>
        <div class="paymnt-error-title">Something went wrong</div>
        <p>Failed to process payment.</p>

        <button class="cmn-butn pyment-erorbutn" @click="paymentErrorModalVisible = false">Close</button>
    </Dialog>

    <!-- Cancel modal -->
    <Dialog v-model:visible="cancelModalVisible" modal :style="{ width: '30vw', maxHeight: '100vh' }"
        :breakpoints="{ '1199px': '50vw', '767px': '80vw' }" :draggable="false" :resizable="false"
        class="pyment-error-popup">
        <div class="pymnt-eroriconbx" style="background-image: none; width: auto; height: auto;">
            <Icon icon="material-symbols:cancel-outline" width="24" height="24" />
        </div>
        <div class="paymnt-error-title">Cancel Booking</div>
        <p>Are you sure you want to cancel this booking?</p>
        <div class="cancel-amount-info" style="background: #f8f9fa; padding: 15px; border-radius: 8px; margin: 15px 0;">
            <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                <span>Total Amount:</span>
                <span>{{ formatLocalPrice(refundDetails.totalAmount) }}</span>
            </div>
            <div
                style="display: flex; justify-content: space-between; font-weight: 600; border-top: 1px solid #dee2e6; padding-top: 8px;">
                <span>Refunded Amount:</span>
                <span>{{ formatLocalPrice(refundDetails.refundAmount) }}</span>
            </div>
        </div>
        <div style="margin: 15px 0;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600;">Reason for cancellation:</label>
            <Textarea v-model="cancelReason" placeholder="Please provide a reason for cancelling this booking" rows="3"
                style="width: 100%;" />
            <span style="color: red; display: block; margin-top: 5px;" v-if="cancelReasonError">{{ cancelReasonError
            }}</span>
        </div>
        <div
            style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 12px; border-radius: 6px; margin: 15px 0; font-size: 14px; color: #856404;">
            <strong>Note:</strong> Refund will be processed to your original payment card and may take 5-10 business
            days to
            reflect in your account.
        </div>
        <div style="display: flex; gap: 10px; justify-content: center; margin-top: 20px;">
            <button class="cmn-butn pyment-erorbutn" :disabled="cancelLoading" @click="handleClickCancelBooking">
                <span v-if="cancelLoading">Cancelling...</span>
                <span v-else>Confirm Cancel</span>
            </button>
            <button class="cmn-butn pyment-erorbutn" style="background: #6c757d; color: white;"
                @click="cancelModalVisible = false">Close</button>
        </div>
    </Dialog>
</template>

<script setup>
import { onMounted, watch, ref, computed, reactive } from 'vue';
import { homeJs } from "@/custom.js";
import Pagination from '@/components/customPaginate.vue';
import ListHelper from "@/helpers/ListHelper";
import _ from 'lodash';
const { debounce, pickBy } = _;
import { router, useForm } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect';
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import SpinnerOverlay from '@/components/Frontend/Loader.vue';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const props = defineProps({
    bookings: Object,
    totalBookingCount: Number,
    countries: Object,
    safariTypes: Object,
});
const params = () => new URLSearchParams(typeof window !== 'undefined' ? window.location.search : '');

const visible = ref(false);
const bookingDetails = ref({});
const ratingVisible = ref(false);
const travelerReview = ref({});
const paymentSuccessModalVisible = ref(false);
const paymentLoading = ref(false);
const paymentErrorModalVisible = ref(false);
const cancelLoading = ref(false);
const cancelModalVisible = ref(false);
const cancelReason = ref('');
const cancelReasonError = ref('');

const form = reactive({
    day_duration: params().get("day_duration") || '',
    location: params().get("location") || '',
    safari_type: params().get("safari_type") || '',
    booking_type: params().get("booking_type") || '',
});

const resetSearch = () => {
    router.visit(route("frontend.my-trips"), {
        method: "get",
    });
};

const search = debounce(() => {
    router.visit(route('frontend.my-trips'), {
        method: 'get',
        data: pickBy(form),
        preserveScroll: true,
        replace: true,
    });
}, 100);

watch(form, (newValue) => {
    search();
});

const ratingForm = useForm({
    safari_id: '',
    rating: '',
    heading: '',
    details: ''
});

const handleClickBookingDetails = (bookingId) => {
    axios.get(route('frontend.traveller-booking-details', { safariBookingId: bookingId })).then(res => {
        bookingDetails.value = res.data;
        travelerReview.value = res.data?.safari?.safari_reviews[0] ?? null;
        ratingForm.safari_id = res.data?.safari?.id ?? '';
        if (travelerReview.value != null) {
            ratingForm.rating = travelerReview.value.rating;
            ratingForm.heading = travelerReview.value.heading;
            ratingForm.details = travelerReview.value.details;
        } else {
            ratingForm.rating = '';
            ratingForm.heading = '';
            ratingForm.details = '';
        }

    });
    visible.value = true;
};

const handleClickListingRating = () => {
    ratingForm.post(route('frontend.trip-booking-review'), {
        onSuccess: () => {
            ratingVisible.value = false;
            handleClickBookingDetails(bookingDetails.value?.id);
        }
    });
};

const payManual = async () => {
    try {
        paymentLoading.value = true;
        if (!bookingDetails.value?.id) {
            return;
        }
        const data = {
            booking_id: bookingDetails.value?.id
        }
        const response = await axios.post(route('frontend.manual-payment'), data);
        console.log(response.status);

        if (response.status == 200) {
            paymentSuccessModalVisible.value = true;
            paymentLoading.value = false;
        } else {
            paymentLoading.value = false;
            paymentErrorModalVisible.value = true;
        }
    } catch (error) {
        paymentErrorModalVisible.value = true;
        paymentLoading.value = false;
    }
}

onMounted( async () => {
    homeJs();
    await initializeCurrency();
    emit.emit("pageName", "Your Safari Plans");
    emit.emit("pageSubTitle", "Upcoming adventures and past memories");

});
const goToMyTrips = () => {
    router.visit(route('frontend.my-trips'), {
        replace: true,
    })
}

const canCancelBooking = computed(() => {
    if (!bookingDetails?.value?.check_in_date) return false;
    const today = new Date();
    const checkIn = new Date(bookingDetails?.value.check_in_date);
    const diffTime = checkIn - today;
    const diffDays = diffTime / (1000 * 60 * 60 * 24);
    return diffDays > 30 && bookingDetails?.value?.status != 'COMPLETED';
});

const handleClickCancelBooking = () => {
    cancelReasonError.value = "";

    if (!cancelReason.value || cancelReason.value.trim() === "") {
        cancelReasonError.value = "Please provide a reason for cancelling this booking";
        return;
    }

    if (cancelReason.value.length > 500) {
        cancelReasonError.value = "Cancellation reason cannot exceed 500 characters";
        return;
    }

    cancelLoading.value = true;

    router.post(route("frontend.cancel-booking"), {
        booking_id: bookingDetails.value?.id,
        cancel_reason: cancelReason.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            cancelModalVisible.value = false; // close cancel dialog
            visible.value = false;            // close main modal
            cancelLoading.value = false;
        },
        onError: (error) => {
            toaster.error(error.message || "Failed to cancel booking");
            cancelLoading.value = false;
        }
    });
};


const refundDetails = computed(() => {
    if (!bookingDetails.value) {
        return { cancelAmount: 0, cancellationFee: 0, refundAmount: 0 };
    }

    let refundAmount = 0;
    let totalAmount = bookingDetails.value.total_price;

    const payments = Array.isArray(bookingDetails.value.payment)
        ? bookingDetails.value.payment
        : []

    if (bookingDetails.value.is_full_paid && bookingDetails.value.payment_type === "pay_in_full") {
        refundAmount = payments.length > 0 ? payments[0].amount || 0 : 0;
    } else if (bookingDetails.value.is_full_paid && ["deposit_auto_payment", "deposit_manual_payment"].includes(bookingDetails.value.payment_type)
    ) {
        refundAmount = payments.map(payment => Number(payment.amount)).reduce((a, b) => a + b, 0).toFixed(2);
    } else {
        // Partial / other payment
        refundAmount = payments.length > 0 ? payments[0].amount || 0 : 0;
    }

    return {
        totalAmount,
        refundAmount,
    };
});

const isDepositDateValid = computed(() => {
    if (!bookingDetails.value?.next_deposit_date) return false;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const depositDate = new Date(bookingDetails.value.next_deposit_date);
    depositDate.setHours(0, 0, 0, 0);
    return depositDate >= today;
});

// Enquiry helpers
const capitalizeFirst = (str) => {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
};

const goToChat = (chatRoomId) => {
    router.visit(route('frontend.messages'));
};

const handleEnquiryAction = (booking) => {
    // Navigate to chat where the enquiry context panel will show
    router.visit(route('frontend.messages'));
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style>
.multiselect-search {
    cursor: pointer !important;
}

.review-card {
    margin-top: 1rem;
    padding: 1rem;
    background-color: #f9fafb;
    border-radius: 8px;
    border: 1px solid #e5e7eb;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.review-text {
    flex: 1;
    margin-right: 1rem;
}

.review-heading {
    font-weight: 600;
    color: #111827;
    margin: 0;
}

.review-details {
    color: #4b5563;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.review-rating {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.25rem 0.75rem;
    background-color: #fff;
    border: 1px solid #d1d5db;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
}
</style>

<style scoped>
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

.p-progressspinner.custom-spinner {
    height: 46px !important;
}

/* Enquiry status badges */
.enquiry-status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.enquiry-pending {
    background: #fff3cd;
    color: #856404;
}

.enquiry-quoted {
    background: #cce5ff;
    color: #004085;
}

.enquiry-confirmed {
    background: #d4edda;
    color: #155724;
}

.enquiry-declined {
    background: #f8d7da;
    color: #721c24;
}

.stus_cell_hlder a {
    display: inline-flex;
    align-items: center;
    margin-left: 8px;
    color: #666;
    cursor: pointer;
}

.stus_cell_hlder a:hover {
    color: #333;
}
</style>