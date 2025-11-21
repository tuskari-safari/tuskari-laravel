<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr earnwrpr">
        <div class="row cntnt_row gx-0">
            <div class="col-lg-9 pnl_rghtCntnt_lftPart">
                <div class="booking_lft_part equal_height">
                    <div class="earntopsec">
                        <div class="earnttl-lftpnl">
                            <div class="earntop-row">
                                <div class="earntopcol">
                                    <div class="earntotalbx">
                                        <div class="ttlearn-hd">Available Balance</div>
                                        <div class="ttlearn-prc">
                                            ${{ Number(wallet?.available_amount ?? 0).toLocaleString('en-GB') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="earntopcol">
                                    <div class="earntotal-sub">
                                        <div class="ttlearn-subhd">Pending Balance</div>
                                        <div class="ttlearn-subprc">
                                            ${{ Number(wallet?.pending_amount ?? 0).toLocaleString('en-GB') }}
                                            <i class="tpearnarw">
                                                <img :src="'/frontend_assets/images/grntransactnicon.svg'" alt="arw">
                                            </i>
                                        </div>
                                    </div>
                                </div>
                                <div class="earntopcol">
                                    <div class="earntotal-sub">
                                        <div class="ttlearn-subhd">Total Earnings</div>
                                        <div class="ttlearn-subprc">
                                            ${{ Number(wallet?.total_earned ?? 0).toLocaleString('en-GB') }}
                                            <i class="tpearnarw">
                                                <img :src="'/frontend_assets/images/grntransactnicon.svg'" alt="arw">
                                            </i>
                                        </div>
                                    </div>
                                </div>
                                <div class="earntopcol">
                                    <div class="earntotal-sub">
                                        <div class="ttlearn-subhd">Withdrawals</div>
                                        <div class="ttlearn-subprc">
                                            ${{ Number(wallet?.total_withdrawn ?? 0).toLocaleString('en-GB') }}
                                            <i class="tpearnarw">
                                                <img :src="'/frontend_assets/images/red-arrow.svg'" alt="arrow">
                                            </i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="earn-tmbutnbx" v-if="wallet?.available_amount > 0">
                            <a href="javascript:void(0)" class="cmn-butn sf-filterbutn"
                                data-target="transfr-mny-pop">Withdrawal</a>
                        </div>
                    </div>

                    <!-- === Earnings History Table === -->
                    <div class="trips_wrppr">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>Earning history</h2>
                            </div>
                            <div class="hdr_rght_prt">
                                <div class="srtng_box">
                                    <button v-if="selectedFilter" @click="resetFilter" class="cmn-butn"
                                        style="margin-right: 15px; padding: 8px 16px; font-size: 12px;">Reset</button>
                                    <div class="srtng_txt_prt">
                                        <p>Sort by:</p>
                                    </div>
                                    <div class="sortng_input_hlder">
                                        <div class="sortng_input_col">

                                            <Multiselect placeholder="Select" v-model="selectedFilter"
                                                @change="applyFilter" class="multislect-dropdwn multislect-cmn-adj"
                                                :close-on-select="true" :searchable="true" :create-option="false"
                                                :options="[
                                                    { value: 'Last 7 days', label: 'Last 7 days' },
                                                    { value: 'Last 30 days', label: 'Last 30 days' },
                                                    { value: 'Last 2 Months', label: 'Last 2 Months' }
                                                ]" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="trips_table_hldr">
                            <div class="table_scroll">
                                <table>
                                    <thead>
                                        <tr>
                                            <th class="bkkng_cell">Booking Id</th>
                                            <th class="sfri_nme_cell">Paid by</th>
                                            <th class="dte_cell">Date & Time</th>
                                            <th class="dte_cell">Start Date</th>
                                            <th class="prce_cell">Price</th>
                                            <th class="status_cell">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="earning in earnings?.data" :key="earning.id">
                                            <td>#{{ earning.booking?.booking_id }}</td>
                                            <td>
                                                <div class="earnpaynmbx">
                                                    {{ earning.booking?.traveler?.full_name || 'N/A' }}
                                                </div>
                                            </td>
                                            <td>{{ formatDate(earning.created_at) }} {{ formatTime(earning.created_at) }}
                                            </td>
                                           <td>{{ earning?.booking && earning.booking.check_in_date ? formatDate(earning.booking.check_in_date) : 'N/A' }}</td>
                                            <td class="prce">${{ Number(earning.amount).toLocaleString('en-GB') }}</td>
                                            <td>
                                                <div :class="{
                                                    'stus_bttn_lke': earning.status === 'completed',
                                                    'stus_bttn_pending': earning.status === 'pending'
                                                }">
                                                    {{ earning.status === 'completed' ? 'Completed' : 'Pending' }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr v-if="!earnings?.total">
                                            <td colspan="5" class="text-center">No earnings found</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="common-pagination save-pagination mb-5">
                            <Pagination :pagination="earnings" route-name="frontend.operator-earnings"
                                :extraParams="{ filter: selectedFilter }" />
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 pnl_rghtCntnt_rghtPart">
                <div class="mssg_pnl target_height">
                    <div class="mssg_top_pnl stcky_part">
                        <h2>Latest Withdrawals</h2>
                    </div>
                    <div class="mssg_bttm_pnl">
                        <div class="mssg_bttm_pnl_inner scroll_area" @scroll="handleScroll">
                            <div class="trans-item" v-for="(withdrawal, i) in withdrawals" :key="i">
                                <a href="javascript:void(0)" class="trnsctnindlnk">
                                    <div class="savedact-bnkicon">
                                        <img :src="'/frontend_assets/images/bank.svg'" alt="bankicon">
                                    </div>
                                    <div class="ltsttrnsct-rgtwrp">
                                        <div class="ltsttrnsct-inn">
                                            <div class="trns-bnknm">
                                                {{ withdrawal.operator_bank?.bank_name || 'Bank' }}
                                            </div>
                                            <div class="trns-dttm">
                                                <ul>
                                                    <li><span class="trns-dt">{{ formatDate(withdrawal.requested_at)
                                                    }}</span></li>
                                                    <li><span class="trns-tm">{{ formatTime(withdrawal.requested_at)
                                                    }}</span></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="ltsttrnsct-prc">
                                            <div class="withdrawal-status">
                                                <span :class="{
                                                    'status-approved': withdrawal.status === 'approved',
                                                    'status-processed': withdrawal.status === 'processed',
                                                    'status-rejected': withdrawal.status === 'rejected'
                                                }" class="status-badge">
                                                    {{ withdrawal.status.charAt(0).toUpperCase() +
                                                        withdrawal.status.slice(1) }}
                                                </span>
                                            </div>
                                            <i class="ltsttrnsct-arw">
                                                <img :src="'/frontend_assets/images/red-arrow.svg'" alt="arrow" />
                                            </i>
                                            <span class="text-danger">
                                                -${{ Number(withdrawal.amount).toLocaleString('en-GB') }}
                                            </span>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <div v-if="loadingMore" class="text-center p-2">Loading...</div>
                            <div v-if="!withdrawals.length && !loadingMore" class="text-center p-3">
                                No recent withdrawals
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <OperatorTransferMoney :banks="props.operatorBanks" />
</template>

<script setup>
import OperatorTransferMoney from '@/components/Frontend/OperatorTransferMoney.vue';
import { onMounted, ref } from 'vue';
import { homeJs } from "@/custom.js";
import { router } from '@inertiajs/vue3';
import Pagination from '@/components/customPaginate.vue';
import Multiselect from '@vueform/multiselect';

const props = defineProps({
    operatorBanks: Object,
    wallet: Object,
    withdrawalRequests: Object,
    earnings: Object
});

const withdrawals = ref([...props.withdrawalRequests.data]);
const nextPageUrl = ref(props.withdrawalRequests.next_page_url);
const loadingMore = ref(false);

const handleScroll = (e) => {
    const el = e.target;
    if (el.scrollTop + el.clientHeight >= el.scrollHeight - 50 && nextPageUrl.value && !loadingMore.value) {
        loadMore();
    }
};

const loadMore = async () => {
    if (!nextPageUrl.value || loadingMore.value) return;

    loadingMore.value = true;
    try {
        const url = new URL(nextPageUrl.value);
        const page = url.searchParams.get('page');

        await router.post(route('frontend.operator-earnings'), {
            page: page,
        }, {
            preserveScroll: true,
            preserveState: true,
            only: ['withdrawalRequests'],
            onSuccess: (response) => {
                const newData = response.props.withdrawalRequests.data;
                withdrawals.value = [...withdrawals.value, ...newData];
                nextPageUrl.value = response.props.withdrawalRequests.next_page_url;
            }
        });
    } finally {
        loadingMore.value = false;
    }
};

const params = new URLSearchParams(window.location.search);
const selectedFilter = ref(params.get('filter') || '');

const applyFilter = () => {
    router.get(route('frontend.operator-earnings'), {
        filter: selectedFilter.value
    }, {
        preserveScroll: true,
        preserveState: true,
        only: ['earnings']
    });
};

const resetFilter = () => {
    router.visit(route("frontend.operator-earnings"), {
        method: "get"
    });
};

const formatDate = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleDateString("en-US", { year: "numeric", month: "short", day: "numeric" });
};

const formatTime = (dateStr) => {
    const d = new Date(dateStr);
    return d.toLocaleTimeString("en-US", { hour: "2-digit", minute: "2-digit" });
};

onMounted(() => {
    homeJs();
    emit.emit("pageName", "Your Earnings Overview");
    emit.emit("pageSubTitle", "Track payments and payouts");
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
<style scoped>
.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 500;
    text-transform: capitalize;
}

.status-approved {
    background-color: #3B82F6;
    color: white;
}

.status-processed {
    background-color: #10B981;
    color: white;
}

.status-rejected {
    background-color: #EF4444;
    color: white;
}
</style>
