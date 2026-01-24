<template>
    <div class="panel_rght_cntnt_area">
        <div class="trips_wrppr mt-0">
            <div class="trips_table_hldr bookd_trps_table_hlder pymnts_table_hlder">
                <div class="table_scroll">
                    <table>
                        <thead>
                            <tr>
                                <th class="bkkng_id_cell">Booking ID</th>
                                <th class="wd_sfri_nme_cell">Safari Name</th>
                                <th class="wd_dte_cell">Traveller</th>
                                <th class="wd_prce_cell">Price</th>
                                <th class="bkng_enddte_cell">Booking Date</th>
                                <th class="wd_status_cell">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="payments.data.length == 0">
                                <td colspan="6" class="text-center"><h6>No payments found</h6></td>
                            </tr>
                            <tr v-for="payment in payments.data" :key="payment.id">
                                <td class="cell_id_label">#{{ payment?.safari_booking?.booking_id }}</td>
                                <td class="trips_nme_label">{{ payment?.safari_booking?.safari?.title }}</td>
                                <td>
                                    {{ payment?.traveler?.full_name }}
                                </td>

                                <td class="prce">{{ formatLocalPrice(payment?.amount)}}</td>
                                <td>{{ ListHelper.dateFormat(payment?.safari_booking?.created_at, "MMMM DD, YYYY") }}
                                </td>
                                <td>
                                    <div class="stus_cell_hlder">
                                        <div class="stus_bttn_lke">{{ payment?.payment_status ? payment.payment_status.charAt(0).toUpperCase() + payment.payment_status.slice(1).toLowerCase() : '' }}</div>
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
        <Pagination :pagination="payments" route-name="frontend.payments" />
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import ListHelper from "@/helpers/ListHelper";
import Pagination from '@/components/customPaginate.vue';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();


onMounted(async() => {
    homeJs();
    await initializeCurrency();
    emit.emit("pageName", "Your Bookings & Payments");
    emit.emit("pageSubTitle", "View payment history and manage transactions");


});

const props = defineProps({
    payments: Object
});
</script>