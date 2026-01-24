<template>
    <div class="panel_rght_cntnt_area serv-booksec">
        <div class="trips_wrppr mt-0">
            <div class="trips_hder trips_header">
                <div class="hdr_lft_prt">
                    <p v-if="totalBookingCount > 0">{{ totalBookingCount < 10 ? '0' + totalBookingCount :
                        totalBookingCount }} {{form.status.toLowerCase().replace(/\b[a-z]/g, char =>
                                char.toUpperCase())}} bookings showing...</p>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex-book">
                <ul class="listingfiltermenu-list">
                    <li :class="{ active: form.status === 'all' }">
                        <button class="cmn-butn" @click="filterBookings('all')">All</button>
                    </li>
                    <li :class="{ active: form.status === 'upcoming' }">
                        <button class="cmn-butn" @click="filterBookings('upcoming')">Upcoming</button>
                    </li>
                    <li :class="{ active: form.status === 'ongoing' }">
                        <button class="cmn-butn" @click="filterBookings('ongoing')">Ongoing</button>
                    </li>
                    <li :class="{ active: form.status === 'completed' }">
                        <button class="cmn-butn" @click="filterBookings('completed')">Completed</button>
                    </li>
                </ul>
                <div class="hder_srch_box">
                    <input type="search" placeholder="Search" v-model="form.search">
                </div>

            </div>
            <div class="trips_table_hldr bookd_trps_table_hlder">
                <div class="table_scroll">
                    <table>
                        <thead>
                            <tr>
                                <th class="bkkng_id_cell">Booking ID</th>
                                <th class="wd_sfri_nme_cell">Safari Name</th>
                                <th class="lctn_cell">Client Name</th>
                                <th class="wd_dte_cell">Booking Date</th>
                                <th class="wd_prce_cell">Price</th>
                                <th class="wd_prce_cell">Payment Type</th>
                                <th class="wd_nghts_cell">Duration</th>
                                <th class="wd_status_cell">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="bookings.data.length === 0">
                                <td colspan="7" class="text-center">
                                    <h5>No bookings found</h5>
                                </td>
                            </tr>
                            <tr v-for="booking in bookings.data" :key="booking.id">
                                <td class="cell_id_label">#{{ booking.booking_id }}</td>
                                <td class="trips_nme_label">{{ booking?.safari?.title }}</td>
                                <td>{{ booking?.traveler?.full_name }}</td>
                                <td>{{ ListHelper.dateFormat(booking?.check_in_date, "MMMM DD, YYYY") }}</td>
                                <td class="prce">${{ Math.round(booking?.total_price) }}</td>
                                <td>{{ booking?.is_full_paid == 0 ? 'Deposit Paid' : 'Paid in Full' }}</td>
                                <td>{{ booking?.duration }}</td>
                                <td>
                                    <div class="stus_cell_hlder">
                                        <div class="stus_bttn_lke">{{booking?.status.toLowerCase().replace(/\b[a-z]/g,
                                            char => char.toUpperCase())}}</div>
                                        <div class="option-btn-dropdwn">
                                            <a href="javascript:void(0)" class="option_bttn dropdown-toggle"
                                                data-bs-toggle="dropdown"><img
                                                    :src="'/frontend_assets/images/optn_icon.svg'" alt="Active"></a>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        @click.prevent="handleClickBookingDetails(booking.id)"
                                                        href="javascript:void(0)">View details</a></li>
                                                <!-- <li><a class="dropdown-item" href="javascript:void(0)">Delete</a></li> -->
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="common-pagination save-pagination mb-5">
                <Pagination :pagination="bookings" route-name="frontend.operator-bookings"
                    :extraParams="pickBy(form)" />
            </div>
        </div>
    </div>
    <loading v-model:active="isLoading" :can-cancel="true" :is-full-page="true" />
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
                                {{ bookingDetails?.is_full_paid === 1 ? 'Paid in Full' : 'Deposit Paid' }}
                            </div>

                        </div>
                    </div>

                </div>
                <div class="trp-price-dtls">
                    <div class="left">
                        <span class="h3-title">{{ bookingDetails?.safari?.title }}</span>
                    </div>
                    <div class="right"><span class="price-pop">${{ Math.round(bookingDetails?.total_price) }}</span>
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
                                    Math.round(bookingDetails?.deposit_amount) }}</span></div>
                            </div>
                            <div class="trp-price-dtls-rgt-inn">
                                <div class="left">
                                    <span>Balanced amount</span>
                                </div>
                                <div class="right"><span class="price-pop">${{Math.round(bookingDetails?.next_deposit_amount)}}</span></div>
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
                    <div class="dialog-footer-box">
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
                    </div>
                </div>
            </div>
        </div>
    </Dialog>
</template>
<script setup>
import { onMounted, watch, ref, onUnmounted, reactive } from 'vue'
import { homeJs } from "@/custom.js";
import Pagination from '@/components/customPaginate.vue';
import ListHelper from "@/helpers/ListHelper";
import { router } from '@inertiajs/vue3';
import _ from 'lodash';
const { debounce, pickBy } = _;
import Loading from 'vue-loading-overlay';
import Multiselect from '@vueform/multiselect';
const params = () => new URLSearchParams(typeof window !== 'undefined' ? window.location.search : '');

const props = defineProps({
    bookings: Object,
    totalBookingCount: Number,
    countries: Object,
    safariTypes: Object,
});

const isLoading = ref(false);
const visible = ref(false);
const bookingDetails = ref({});

const form = reactive({
    search: params().get('search') || '',
    status: params().get('status') || 'all',
    bookingStatus: '',
    day_duration: params().get("day_duration") || '',
    location: params().get("location") || '',
    safari_type: params().get("safari_type") || '',
});

watch(form, debounce((value) => {
    const data = pickBy(form);
    router.visit(route('frontend.operator-bookings'), {
        method: 'get',
        data,
        preserveState: true,
        preserveScroll: true,
    });
}, 500));

const filterBookings = (status) => {
    isLoading.value = true;
    form.status = status;
    router.visit(route('frontend.operator-bookings'), {
        method: 'get',
        data: pickBy(form),
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            isLoading.value = false;
        }
    });
}

onMounted(() => {
    homeJs();
    emit.on("changeStatusConfirm", function (arg1) {
        changeStatusConfirm(arg1);
    });
    emit.emit("pageName", "Your Bookings & Guests");
    emit.emit("pageSubTitle", "View upcoming and past guests");

});

const handleClickBookingDetails = (bookingId) => {
    axios.get(route('frontend.operator-booking-details', { safariBookingId: bookingId })).then(res => {
        bookingDetails.value = res.data;
        form.bookingStatus = res.data?.status
    });
    visible.value = true;
}

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
const resetSearch = () => {
    router.visit(route("frontend.operator-bookings"), {
        method: "get",
    });
};

</script>
<style src="@vueform/multiselect/themes/default.css"></style>

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
</style>
