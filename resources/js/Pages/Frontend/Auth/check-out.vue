<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="trips_hder trips_header">
            <div class="hdr_lft_prt hdr-lft-backarw">
                <a :href="route('frontend.traveller-safari-details', { slug: safari.slug })" class="backarow"><i><img
                            :src="'/frontend_assets/images/back-blck-arrow.svg'" alt="Back Arrow"></i> Back</a>
            </div>
        </div>
        <div class="chk_out_main_wrapper">
            <div class="check_out_row">
                <div class="chk_out_left_col fixed_width_col">
                    <div class="lft_pnl">
                        <div class="my_trps_pnl">
                            <h2>Checkout </h2>
                            <div class="mlt_card_pnl" v-if="!cardsLoading">
                                <div v-if="!savedCards.length">
                                    <h2>No cards saved</h2>
                                </div>
                                <div v-for="card in savedCards" :key="card.id" class="slt_card_col mb-3">
                                    <div class="custom-radio">
                                        <input type="radio" class="cus_radio_btn" name="cardSelect" :value="card.id"
                                            v-model="selectedCardId">
                                        <div class="chekslect-lbel">
                                            <div class="slct_card_outer chkout-chscrd-outr">
                                                <div class="slt_lft_col">
                                                    <div class="chkout-crd-txtcont">
                                                        <div class="nm_on_card">
                                                            {{ card.billing_details.name || 'Unnamed Card' }}</div>
                                                        <div class="card_no"> XXXX XXXX XXXX {{
                                                            card.card.last4 }}</div>

                                                        <div class="chkout-card-btmprt">
                                                            <div class="crd-exp-date">{{ card.card.exp_month }}/{{
                                                                card.card.exp_year.toString().slice(-2) }}</div>
                                                            <button @click="deleteCard(card.id)" class="carddlte-butn">
                                                                <img :src="'/frontend_assets/images/trash-2.svg'"
                                                                    alt="Delete">
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <i class="chck-crd-icon">
                                                        <img :src="getCardLogo(card.card.brand).toLowerCase()" alt="">
                                                    </i>

                                                </div>
                                                <div class="slt_rgt_col">
                                                    <div class="rdio_box">
                                                        <span class="tick-mark"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-else>
                                <SpinnerOverlay v-if="cardsLoading" />
                            </div>
                        </div>
                        <div class="cards_pnl">
                            <h2>Credit / debit card</h2>
                            <div class="form_box">
                                <form @submit.prevent="handleSaveCard">
                                    <div id="card-element"></div>
                                    <div id="card-errors" class="text-red-500 mt-2 mb-3 text-danger"></div>
                                    <FrontendSubmitButton :isLoading="isLoading" :value="'Add card'" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Flow -->
                <div class="chk_out_right_col fixed_width_col">
                    <div class="booking_summry_outer">
                        <div class="booking_summry_inr">
                            <div class="booking_summry_cntnt">
                                <h2>Booking summary</h2>
                                <p>Pay and complete your booking</p>
                                <div class="booking_summry_bdy form_box">
                                    <form @submit.prevent="handleClickConfirmCheckout">
                                        <div class="booking_dtl_wrap">
                                            <div v-if="bookingData?.is_enquiry_booking" class="enquiry-booking-badge mb-2">
                                                <span class="badge">Enquiry Booking</span>
                                                <small>Price agreed with operator</small>
                                            </div>
                                            <div class="bkwrap_head">
                                                {{ safari?.title ?? 'NA' }} - {{ safari?.location ?? 'NA' }}
                                            </div>
                                            <div class="chk_list_outer">
                                                <ul>
                                                    <li>
                                                        <div class="chk_dtl_bx">
                                                            <div class="lft_lgo">
                                                                <img :src="'/frontend_assets/images/calendar.svg'"
                                                                    alt="calender">
                                                            </div>
                                                            <div class="rgt_cont">
                                                                <div class="hed">Check In</div>
                                                                <div class="dtl"> {{
                                                                    ListHelper.dateFormat(bookingData?.check_in_date,
                                                                        "MMM DD, YYYY") }}</div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="chk_dtl_bx">
                                                            <div class="lft_lgo">
                                                                <img :src="'/frontend_assets/images/calendar.svg'"
                                                                    alt="calender">
                                                            </div>
                                                            <div class="rgt_cont">
                                                                <div class="hed">Check Out</div>
                                                                <div class="dtl">{{
                                                                    ListHelper.dateFormat(bookingData?.check_out_date,
                                                                        "MMM DD, YYYY") }}</div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="chk_dtl_bx">
                                                            <div class="lft_lgo">
                                                                <img :src="'/frontend_assets/images/usr-green.svg'"
                                                                    alt="calender">
                                                            </div>
                                                            <div class="rgt_cont">
                                                                <div class="hed">Adult</div>
                                                                <div class="dtl">{{ bookingData?.number_of_adults }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="chk_dtl_bx">
                                                            <div class="lft_lgo">
                                                                <img :src="'/frontend_assets/images/usr-green.svg'"
                                                                    alt="calender">
                                                            </div>
                                                            <div class="rgt_cont">
                                                                <div class="hed">Child</div>
                                                                <div class="dtl">{{ bookingData?.number_of_children }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="booking_btm_wrap">
                                            <div class="bkprc-dtlshd">
                                                Price details
                                            </div>
                                            <div class="guest_dtl">
                                                <div class="user_type_bx">
                                                    <div class="usr-ttl-wrp">
                                                        <div class="usr_ttl">Adult ({{ bookingData?.number_of_adults
                                                        }}x)</div>
                                                    </div>
                                                    <div class="usr-ttl-prc">{{ formatLocalPrice(bookingData?.total_adult_price) }}</div>
                                                </div>
                                                <div class="user_type_bx">
                                                    <div class="usr-ttl-wrp">
                                                        <div class="usr_ttl">Child ({{ bookingData?.number_of_children
                                                            }}x)</div>
                                                    </div>
                                                    <div class="usr-ttl-prc">{{ formatLocalPrice(bookingData?.total_child_price) }}</div>
                                                </div>
                                            </div>
                                            <span class="cmn-butn mt-2" style="padding: 4px 12px;" v-if="!bookingData?.is_enquiry_booking && (bookingData.hasDiscountAdultPrice || bookingData.hasDiscountChildPrice)">Group Discount Applied</span>

                                        </div>
                                        <div class="payment_wrap_chkin">
                                            <div class="bkprc-dtl-total-wrp" v-if="paymentType === 'deposit'">
                                                <div class="bkprc-dtl-total" style="margin-bottom: 20px;">
                                                    <div class="bkprc-dtl-ttllft">
                                                        <div class="bkprc-dtl-ttl-txt">Deposit
                                                            Due Now ({{ Number(props.setting?.first_deposit_percentage)
                                                            }}%)
                                                        </div>
                                                    </div>
                                                    <div class="bkprc-dtl-ttlrgt">
                                                        <div class="bkprc-dtl-ttl-prc">{{ formatLocalPrice((totalPayable * props.setting?.first_deposit_percentage) / 100) }}</div>
                                                    </div>
                                                </div>
                                                <div class="bkprc-dtl-total" style="margin-bottom: 10px;">
                                                    <div class="bkprc-dtl-ttllft">
                                                        <div class="bkprc-dtl-ttl-txt" style="font-size: 14px;">
                                                            Remaining Balance
                                                        </div>
                                                    </div>
                                                    <div class="bkprc-dtl-ttlrgt">
                                                        <div class="bkprc-dtl-ttl-prc bkprc-dtl-ttl-prc-rg"
                                                            style="font-size: 14px;">
                                                            {{ formatLocalPrice(totalPayable - (totalPayable *
                                                                props.setting?.first_deposit_percentage /
                                                            100)) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bkprc-dtl-total" style="margin-bottom: 10px;">
                                                    <div class="bkprc-dtl-ttllft">
                                                        <div class="bkprc-dtl-ttl-txt" style="font-size: 14px;">
                                                            Auto-charge Date
                                                        </div>
                                                    </div>
                                                    <div class="bkprc-dtl-ttlrgt">
                                                        <div class="bkprc-dtl-ttl-prc bkprc-dtl-ttl-prc-rg"
                                                            style="font-size: 14px;">{{ new Date(
                                                                new Date(bookingData?.check_in_date).getTime() - 30 * 24 *
                                                                60 * 60 * 1000
                                                            ).toLocaleDateString('en-US', {
                                                                year: 'numeric',
                                                                month: 'short',
                                                                day: 'numeric',
                                                            })
                                                            }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bkprc-dtl-total" v-if="paymentType !== 'deposit'">
                                                <div class="bkprc-dtl-ttllft">
                                                    <div class="bkprc-dtl-ttl-txt">Total Due Now</div>
                                                </div>
                                                <div class="bkprc-dtl-ttlrgt">
                                                    <div class="bkprc-dtl-ttl-prc">{{formatLocalPrice(totalPayable) }}</div>
                                                </div>
                                            </div>
                                            <div class="row form_row">
                                                <div class="col-6 form_col">
                                                    <div class="payment-option"
                                                        :class="{ 'active': paymentType === 'pay_in_full' }"
                                                        @click="paymentType = 'pay_in_full'">
                                                        <input type="radio" id="pay_in_full" value="pay_in_full"
                                                            name="payment_type" v-model="paymentType"
                                                            class="payment-radio">
                                                        <div class="payment-content">
                                                            <div class="payment-title">Pay in Full</div>
                                                        </div>
                                                        <div class="payment-check">✓</div>
                                                    </div>
                                                </div>
                                                <div class="col-6 form_col">
                                                    <div class="payment-option"
                                                        :class="{ 'active': paymentType === 'deposit', 'disabled': !isDepositAllowed }"
                                                        @click="isDepositAllowed && (paymentType = 'deposit')">
                                                        <input type="radio" id="deposit" value="deposit"
                                                            name="payment_type" v-model="paymentType"
                                                            :disabled="!isDepositAllowed" class="payment-radio">
                                                        <div class="payment-content">
                                                            <div class="payment-title">Pay a Deposit</div>
                                                        </div>
                                                        <div class="payment-check">✓</div>
                                                    </div>
                                                </div>
                                                <div class="col-12 form_col">
                                                    <FrontendSubmitButton :isLoading="paymentLoading" :value="'Pay ' + (
                                                        paymentType === 'pay_in_full'
                                                            ? formatLocalPrice(totalPayable)
                                                            : formatLocalPrice(totalPayable * props.setting?.first_deposit_percentage / 100)
                                                    )" />
                                                </div>
                                                <div class="col-12 form_col">
                                                    <p class="para-payment text-center" style="font-size: 12px; color: #666; margin-top: 10px;">
                                                        All payments are processed in USD. Local currency amounts are approximate and based on daily exchange rates.
                                                    </p>
                                                </div>
                                                <div class="col-12 form_col" v-if="paymentType === 'pay_in_full'">
                                                    <p class="para-payment">
                                                        This safari will be <strong>fully paid today</strong>. No
                                                        further payments will be required.
                                                    </p>
                                                </div>
                                                <div class="col-12 form_col" v-if="paymentType === 'deposit'">
                                                    <p class="para-payment">
                                                        Your remaining balance will be <strong>auto-charged {{
                                                            props.setting?.auto_balance_duration_days || 30 }} days
                                                            before departure</strong>, or you can pay earlier from your
                                                        dashboard.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <Dialog v-model:visible="paymentSuccessModalVisible" modal :style="{ width: '28vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '50vw', '575px': '80vw' }" :draggable="false" :resizable="false"
        class="pyment-suces-popup">
        <div class="paymnt-suces-title">Success</div>
        <p>Your {{ safari?.title ?? 'NA' }} has been successfully booked!
            <br> Please check your email for the confirmation
        </p>
        <div class="pymnt-sucesiconbx">
            <Icon icon="material-symbols:check-rounded" width="24" height="24" />
        </div>

        <a class="cmn-butn pyment-sucesbutn" href="javascript:void(0)" @click="goToMyTrips">Ok</a>
    </Dialog>

    <Dialog v-model:visible="paymentErrorModalVisible" modal :style="{ width: '28vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '50vw', '767px': '80vw' }" :draggable="false" :resizable="false"
        class="pyment-error-popup">
        <!-- <img src="/frontend_assets/images/planet.png" alt=""> -->
        <div class="pymnt-eroriconbx">
            <Icon icon="bx:error" width="24" height="24" />
        </div>
        <div class="paymnt-error-title">Something went wrong</div>
        <p>Something went wrong</p>
        <button class="cmn-butn pyment-erorbutn" @click="paymentErrorModalVisible = false">Close</button>
    </Dialog>
    <loading v-model:active="paymentLoading" :can-cancel="true" :is-full-page="true" />

</template>

<script setup>
import SpinnerOverlay from '@/components/Frontend/Loader.vue';
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { homeJs } from "@/custom.js";
import ListHelper from "@/helpers/ListHelper";
import Toaster from "@/helpers/Toaster";
import axios from 'axios';
import { router, usePage } from '@inertiajs/vue3';
import { loadStripe } from "@stripe/stripe-js";
import Loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/css/index.css';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const toaster = Toaster;

let stripe;
let elements;
let paymentElement;
const isLoading = ref(false);
const cardsLoading = ref(false);
const paymentLoading = ref(false);
const savedCards = ref([]);
const selectedCardId = ref(null);
const paymentSuccessModalVisible = ref(false);
const paymentErrorModalVisible = ref(false);
const paymentType = ref('pay_in_full');

const props = defineProps({
    bookingData: Object,
    safari: Object,
    setting: Object
});
if (!props.bookingData) {
    router.visit(route('frontend.index'))
};
const page = usePage();

const fetchCardFromStripe = async () => {
    try {
        cardsLoading.value = true;
        paymentLoading.value = true;
        const response = await axios.get(route('frontend.user-cards'));

        savedCards.value = response.data.cards;
        const defaultId = response.data.default_payment_method;

        if (defaultId) {
            selectedCardId.value = defaultId;
        } else if (savedCards.value.length) {
            selectedCardId.value = savedCards.value[0].id;
        }

        cardsLoading.value = false;
        paymentLoading.value = false;
    } catch (error) {
        cardsLoading.value = false;
        paymentLoading.value = false;
        console.error('Failed to fetch cards:', error);
    }
}

const mountPaymentElement = async () => {
    stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
    const { data } = await axios.post(route('frontend.create-setup-intent'));
    const clientSecret = data.clientSecret;
    elements = stripe.elements({ clientSecret });
    paymentElement = elements.create("payment");
    paymentElement.mount("#card-element");
};

onMounted(async () => {
    homeJs();
    await initializeCurrency();
    fetchCardFromStripe();
    document.body.classList.remove("no-scroll");
    stripe = await loadStripe(import.meta.env.VITE_STRIPE_KEY);

    await mountPaymentElement();

    emit.on("deleteCardConfirm", function (arg1) {
        deleteCardConfirm(arg1);
    });
});


const handleSaveCard = async () => {
    isLoading.value = true;
    cardsLoading.value = true;
    document.getElementById('card-errors').textContent = '';

    try {
        const { setupIntent, error } = await stripe.confirmSetup({
            elements,
            confirmParams: {
                payment_method_data: {
                    billing_details: {
                        name: page.props.auth.user.full_name,
                        email: page.props.auth.user.email,
                    },
                },
            },
            redirect: 'if_required',
        });

        if (error) {
            document.getElementById('card-errors').textContent = error.message;
        } else {
            await axios.post(route('frontend.store-payment-method'), {
                payment_method: setupIntent.payment_method,
            });

            toaster.success('Payment method saved successfully.');
            await fetchCardFromStripe();

            paymentElement.unmount();
            await mountPaymentElement();
        }

    } catch (error) {
        const msg = error?.response?.data?.error || "Something went wrong";
        document.getElementById('card-errors').textContent = msg;
    } finally {
        isLoading.value = false;
        cardsLoading.value = false;
    }
};

const totalPayable = (props.bookingData?.total_adult_price + props.bookingData?.total_child_price);

const handleClickConfirmCheckout = async () => {
    if (savedCards.value.length == 0) {
        toaster.error("Please add a card to checkout.");
        return;
    }
    if (!paymentType.value) {
        toaster.error("Please select a payment type.");
        return;
    }

    try {
        paymentLoading.value = true;
        const data = {
            ...props.bookingData,
            safari_id: props.safari.id,
            card_id: selectedCardId.value,
            total_payable: totalPayable,
            payment_type: paymentType.value
        }
        
        const response = await axios.post(route('frontend.safari-payment'), data);
        const clientSecret = response.data.client_secret;
        const result = await stripe.confirmCardPayment(clientSecret, {
            payment_method: selectedCardId.value,
        });
        if (result.paymentIntent && result.paymentIntent.status === 'succeeded') {
            // Payment succeeded
            const response = await axios.post(route('frontend.payment-success'), {
                payment_intent_id: result.paymentIntent.id,
            });
            if (response.status == 200) {
                paymentLoading.value = false;
                paymentSuccessModalVisible.value = true;
                localStorage.removeItem('safariBookingData');
            } else {
                paymentErrorModalVisible.value = true;
                paymentLoading.value = false;
            }

        }
    } catch (error) {
        paymentLoading.value = false;
        const errorMessage = error?.response?.data?.error || 'Failed to process payment';
        toaster.error(errorMessage);
    }
};

const deleteCard = async (cardId) => {
    sw.confirm(
        "deleteCardConfirm",
        cardId,
        "Are you sure?",
        "This card will be deleted and you won't be able to use it again.",
        "Yes, Delete it!"
    );
};

const deleteCardConfirm = async (cardId) => {
    try {
        cardsLoading.value = true;
        await axios.delete(route('frontend.delete-card', { card_id: cardId }));
        fetchCardFromStripe();
        isLoading.value = false;
        toaster.success("Card deleted successfully.");
    } catch (error) {
        cardsLoading.value = false;
        toaster.error("Failed to delete card.");
        console.error(error);
    }
}

onUnmounted(() => {
    emit.off("deleteCardConfirm");
});

const goToMyTrips = () => {
    router.visit(route('frontend.my-trips'), {
        replace: true,
    })
}
const getCardLogo = (brand) => {
    return 'https://raw.githubusercontent.com/aaronfagan/svg-credit-card-payment-icons/main/flat-rounded/' + brand + '.svg';
}

const isDepositAllowed = computed(() => {
    if (!props.bookingData?.check_in_date) return false;

    const today = new Date();
    const checkIn = new Date(props.bookingData.check_in_date);

    // calculate difference in days
    const diffTime = checkIn.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

    return diffDays > 30;
});
</script>

<style scoped>
.disabled-label {
    color: #aaa;
    /* gray color */
    cursor: not-allowed;
}

.payment-option {
    border: 2px solid var(--stroke-color);
    border-radius: 15px;
    padding: 12px 20px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
    background: var(--white-color);
}

.payment-option:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(72, 86, 72, 0.15);
}

.payment-option.active {
    border-color: var(--primary-color);
    background: var(--light-green-color-one);
    box-shadow: 0 4px 12px rgba(72, 86, 72, 0.15);
}

.payment-option.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    background: var(--gray-color-one);
}

.payment-option.disabled:hover {
    border-color: var(--stroke-color);
    box-shadow: none;
}

.payment-radio {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.payment-content {
    text-align: center;
}

.payment-title {
    font-weight: 600;
    font-size: 16px;
    color: var(--black-color-one);
    margin-bottom: 4px;
}

.payment-subtitle {
    font-size: 12px;
    color: var(--body-color);
}

.payment-check {
    position: absolute;
    top: 10px;
    right: 10px;
    width: 20px;
    height: 20px;
    border-radius: 50%;
    background: var(--primary-color);
    color: var(--white-color);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    font-weight: bold;
    opacity: 0;
    transform: scale(0.8);
    transition: all 0.3s ease;
}

.payment-option.active .payment-check {
    opacity: 1;
    transform: scale(1);
}

.payment-option.active .payment-title {
    color: var(--primary-color);
}

@media (max-width: 1199px) {
    .payment-check {
        width: 18px;
        height: 18px;
        top: 9px;
        right: 9px;
        font-size: 11px;
    }
}

@media (max-width: 767px) {
    .payment-check {
        width: 16px;
        height: 16px;
        top: 8px;
        right: 8px;
        font-size: 10px;
    }
}

@media (max-width: 575px) {
    .payment-check {
        width: 14px;
        height: 14px;
        top: 6px;
        right: 6px;
        font-size: 9px;
    }
}

@media (max-width: 480px) {
    .payment-check {
        width: 12px;
        height: 12px;
        top: 5px;
        right: 5px;
        font-size: 8px;
    }
}

.enquiry-booking-badge {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 1px solid #90caf9;
    border-radius: 8px;
    padding: 10px 14px;
    text-align: center;
}

.enquiry-booking-badge .badge {
    display: inline-block;
    background: #1976d2;
    color: white;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    margin-bottom: 4px;
}

.enquiry-booking-badge small {
    display: block;
    color: #1565c0;
    font-size: 12px;
}
</style>
