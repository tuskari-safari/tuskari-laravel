<template>
    <div class="panel_rght_cntnt_area">
        <div class="prfpref-row">
            <div class="prfpref-lftcol">
                <div class="prfpref-lftcard">
                    <div class="prfpref-infotoppnl">
                        <figure class="prfpref-img">
                            <img :src="profile?.profile_photo_url" alt="image">
                        </figure>
                        <h2 class="prfpref-name">{{ profile?.full_name }}</h2>
                        <p class="prfpref-email">{{ profile?.email }}</p>
                    </div>
                    <div class="prfpref-infocontnt">
                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Full name
                            </div>
                            <div class="prfpref-info">
                                {{ profile?.full_name }}
                            </div>
                        </div>

                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Email
                            </div>
                            <div class="prfpref-info">
                                {{ profile?.email }}
                            </div>
                        </div>
                    </div>
                    <div class="prfpref-butnbx">
                        <button type="button" class="cmn-butn sf-filterbutn" data-target="edit">Edit profile</button>
                        <button type="button" class="cmn-butn wht-butn ppcncelbutn chngpswrdbutn sf-filterbutn"
                            data-target="updtpassword">Change password</button>
                    </div>
                </div>
            </div>
            <div class="prfpref-rgtcol">
                <div class="prfpref-card">
                    <div class="prfpref-topcard">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>Saved cards</h2>
                            </div>
                        </div>
                        <div class="row prfpref-cardrow">
                            <div class="col-md-4 prfpref-card-col" v-for="card in savedCards" :key="card.id"
                                v-if="savedCards.length">
                                <div class="prfpref-cardbx">
                                    <div class="prf-cardicontxt">
                                        <div class="prf-cardicon">
                                            <img :src="getCardLogo(card.card.brand).toLowerCase()" alt="Image">
                                        </div>
                                        <div class="prf-cart-valuetxt">
                                            {{ selectedCardId == card.id ? 'Default' : '' }}
                                        </div>
                                    </div>
                                    <div class="prf-cardnumbr">
                                        XXXX XXXX XXXX {{ card.card.last4 }}
                                    </div>
                                    <div class="prf-card-phlpcont">
                                        <div class="prf-card-phlp-wrpr">
                                            <div class="prf-card-phlp-hd">{{ card.billing_details.name }}</div>
                                            <div class="prf-card-phlp-date">{{ card.card.exp_month }}/{{
                                                card.card.exp_year.toString().slice(-2) }}</div>
                                        </div>
                                        <div class="prf-card-phlpbutnwrpr">
                                            <ul>
                                                <li><a href="javascript:void(0)" @click.prevent="deleteCard(card.id)"><img
                                                            :src="'/frontend_assets/images/graydlticon.svg'"
                                                            alt="edit"></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 prfpref-card-col" v-if="!cardsLoading">
                                <div class="add-prfpref-cardbx">
                                    <a href="javascript:void(0)" @click.prevent="mountCard">
                                        <i class="add-prf-icon"><img :src="'/frontend_assets/images/addcardicon.svg'"
                                                alt="Add"></i>
                                        <div class="addprfpref-cardtxt">Add Card</div>
                                    </a>
                                </div>
                            </div>
                            <SpinnerOverlay v-if="cardsLoading" />
                        </div>
                    </div>

                    <div class="prfpref-tblepymntinfo">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>Transaction history</h2>
                            </div>
                            <div class="hdr_rght_prt">
                                <a :href="route('frontend.payments')" class="vew_all_lnk">View all
                                    <span><img :src="'/frontend_assets/images/duble_arrw.svg'" alt="Arrow"></span></a>
                            </div>
                        </div>
                        <div class="trips_wrppr mt-0">
                            <div class="trips_table_hldr bookd_trps_table_hlder pymnts_table_hlder">
                                <div class="table_scroll">
                                    <table>
                                        <thead>
                                            <tr>
                                                <th class="bkkng_id_cell">Booking ID</th>
                                                <th class="wd_sfri_nme_cell">Safari Name</th>
                                                <th class="wd_dte_cell">Booking Date</th>
                                                <th class="wd_prce_cell">Price</th>
                                                <th class="bkng_enddte_cell">Transaction Date</th>
                                                <th class="wd_status_cell">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-if="payments.length == 0">
                                                <td colspan="6" class="text-center">
                                                    <h6>No payments found</h6>
                                                </td>
                                            </tr>
                                            <tr v-for="payment in payments" :key="payment.id">
                                                <td class="cell_id_label">#{{ payment?.safari_booking?.booking_id }}
                                                </td>
                                                <td class="trips_nme_label">{{ payment?.safari_booking?.safari?.title }}
                                                </td>
                                                <td>
                                                    {{ payment?.traveler?.full_name }}
                                                </td>

                                                <td class="prce">{{formatLocalPrice(payment?.amount)}}</td>
                                                <td>{{ ListHelper.dateFormat(payment?.safari_booking?.created_at,
                                                    "MMMM DD, YYYY") }}</td>
                                                <td>
                                                    <div class="stus_cell_hlder">
                                                        <div class="stus_bttn_lke">{{ payment?.payment_status ==
                                                            'completed' ? 'Paid' : 'Pending' }}
                                                        </div>
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
            </div>
        </div>
    </div>
    <!--edit filter popup-->
    <UpdateTravellerProfile :profile="profile" />
    <!--Passoword filter popup-->
    <ChangeTravellerPassword />
    <Dialog v-model:visible="showAddCardModal" modal header="Add Card" :style="{ width: '30vw', maxHeight: '90vh' }"
        :breakpoints="{ '1199px': '80vw', '575px': '95vw' }" :draggable="false" :resizable="false"
        class="add-card-modal">
        <div class="add-card-content">
            <h4 class="title">Credit / Debit Card</h4>
            <p class="subtitle">Securely save your card for faster checkout.</p>

            <form @submit.prevent="handleSaveCard" class="card-form">
                <div id="card-element" class="stripe-card-element"></div>
                <div id="card-errors" class="card-error"></div>
                <FrontendSubmitButton :isLoading="cardSaveLoading" :value="'Add card'" />
            </form>
        </div>
    </Dialog>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { homeJs } from "@/custom.js";
import UpdateTravellerProfile from '@/components/Frontend/UpdateTravellerProfile.vue';
import ChangeTravellerPassword from '@/components/Frontend/ChangeTravellerPassword.vue';
import { ref } from 'vue';
import SpinnerOverlay from '@/components/Frontend/Loader.vue';
import ListHelper from "@/helpers/ListHelper";
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import { usePage } from '@inertiajs/vue3';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const savedCards = ref([]);
const cardsLoading = ref(false);
const selectedCardId = ref(null);
const showAddCardModal = ref(false);
const cardSaveLoading = ref(false);
let stripe;
let elements;
let paymentElement;
const page = usePage();

const props = defineProps({
    profile: Object,
    payments: Object
});

onMounted(async() => {
    homeJs();
    await initializeCurrency();
    fetchCardFromStripe();
    emit.on("deleteCardConfirm", function (arg1) {
        deleteCardConfirm(arg1);
    });
     emit.emit("pageName", "Your Travel Profile");
    emit.emit("pageSubTitle", "Personal details, travel style, and preferences");


});

const fetchCardFromStripe = async () => {
    try {
        cardsLoading.value = true;
        const response = await axios.get(route('frontend.user-cards'));
        savedCards.value = response.data.cards;
        cardsLoading.value = false;
        const defaultId = response.data.default_payment_method;

        if (defaultId) {
            selectedCardId.value = defaultId;
        } else if (savedCards.value.length) {
            selectedCardId.value = savedCards.value[0].id;
        }
    } catch (error) {
        cardsLoading.value = false;
        console.error('Failed to fetch cards:', error);
    }
}


const mountCard = async () => {
    showAddCardModal.value = true;
    stripe = Stripe(import.meta.env.VITE_STRIPE_KEY);
    const { data } = await axios.post(route('frontend.create-setup-intent'));
    const clientSecret = data.clientSecret;
    elements = stripe.elements({ clientSecret });
    paymentElement = elements.create("payment");
    paymentElement.mount("#card-element");
}

const handleSaveCard = async () => {
    cardSaveLoading.value = true;
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
            return;
        }

        await axios.post(route('frontend.store-payment-method'), {
            payment_method: setupIntent.payment_method,
        });

        showAddCardModal.value = false;
        toaster.success('Payment method saved successfully.');

        await fetchCardFromStripe();
    } catch (err) {
        const msg = err?.response?.data?.error || "Something went wrong. Please try again.";
        document.getElementById('card-errors').textContent = msg;
    } finally {
        cardSaveLoading.value = false;
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

const getCardLogo = (brand) => {
    return 'https://raw.githubusercontent.com/aaronfagan/svg-credit-card-payment-icons/main/flat-rounded/' + brand + '.svg';
}
</script>

<style>
.add-card-modal .p-dialog-content {
    /* light neutral background */
    border-radius: 12px;
    padding: 1.5rem;
}

.add-card-content {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.title {
    font-size: 1.25rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: #1f2937;
}

.subtitle {
    font-size: 0.9rem;
    color: #6b7280;
    margin-bottom: 1rem;
}

.card-form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.stripe-card-element {
    padding: 0.75rem;
    border: 1px solid #d1d5db;
    border-radius: 8px;
    background: white;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
    transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card-error {
    font-size: 0.85rem;
    color: #dc2626;
    min-height: 1.2rem;
}
</style>