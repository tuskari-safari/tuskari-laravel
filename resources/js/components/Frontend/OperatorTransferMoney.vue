<template>
    <div class="filtr-backdrop" data-popup="transfr-mny-pop"></div>
    <div class="filtr-rgtsdepnl" data-popup="transfr-mny-pop">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <div class="wrap-skip">
                    <h2 class="filtrpnlhdng">Transfer your wallet money</h2>
                </div>

                <p>Move your earnings from your Tuskari wallet straight to your bank.</p>
            </div>
            <div class="popup_form_outer">
                <div class="form_box add_card_popup paymentbox">
                    <form class="write-rvw-popup" @submit.prevent="handleTransferMoney">
                        <div class="savedact-wrp">
                            <div class="savedact-hd">Saved account</div>
                            <div class="savedact-subwrpr" v-for="Bank in banks" :key="Bank.id">
                                <input type="radio" class="savedact-radio" name="accuntname" v-model="form.bank_id"
                                    :value="Bank.id">
                                <div class="savedact-subwrprinr">
                                    <div class="savedact-lftcol">
                                        <div class="savedact-bnkicon">
                                            <img :src="'/frontend_assets/images/bank.svg'" alt="bankicon">
                                        </div>
                                        <div class="savedact-bnkid">xxxxxx{{ Bank.account_number.slice(-4) }}</div>
                                    </div>
                                    <div class="savedact-rgtcol">
                                        <div class="bnknamebx">{{ Bank.bank_name }}</div>
                                        <span class="bnktickmrk"></span>
                                    </div>
                                </div>

                            </div>
                            <div v-if="banks.length == 0" class="savedact-nodata">
                                <div class="text-center">No Account found</div>
                            </div>
                        </div>
                        <div class="acnt-pricelistbx">
                            <div class="acnt-ttlprice-slrct nw-withdraw-amnt">
                                <label>Withdraw Amount</label>
                                <input type="text" id="price" v-model="form.price" placeholder="Enter Price" @input="() => {
                                    form.price = form.price.replace(/[^0-9.]/g, '').replace(/^0+(?!\.)/, '').replace(/(\..*)\./g, '$1');
                                }" :class="form.errors.price ? 'border-danger' : ''">
                                <span class=" text-danger " v-if="form.errors.price">
                                {{ form.errors.price }}</span>
                            </div>
                        </div>
                        <div class="row form_row">
                            <div class="col-6 form_col full-width">
                                <label>Bank name</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter" class="" v-model="form.bank_name">
                                    <span class="text-danger" v-if="form.errors.bank_name">{{ form.errors.bank_name
                                    }}</span>
                                </div>
                            </div>
                            <div class="col-6 form_col full-width">
                                <label>Bank account holder name</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter" class="" v-model="form.account_holder_name">
                                    <span class="text-danger" v-if="form.errors.account_holder_name">{{
                                        form.errors.account_holder_name }}</span>
                                </div>
                            </div>
                            <div class="col-6 form_col full-width">
                                <label>Confirm bank account number</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter" class="" v-model="form.account_number">
                                    <span class="text-danger" v-if="form.errors.account_number">{{
                                        form.errors.account_number }}</span>
                                </div>
                            </div>
                            <div class="col-6 form_col full-width">
                                <label>IFSC code</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter" class="" v-model="form.ifsc_code">
                                    <span class="text-danger" v-if="form.errors.ifsc_code">{{ form.errors.ifsc_code
                                    }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <FrontendSubmitButton :isLoading="form.processing" :value="'Withdraw'" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import FrontendSubmitButton from '../FrontendSubmitButton.vue';
import { handle } from '@primeuix/themes/aura/imagecompare';
import { watch } from 'vue';
const props = defineProps({
    banks: Object,
});

const form = useForm({
    bank_id: '',
    bank_name: '',
    account_holder_name: '',
    account_number: '',
    ifsc_code: '',
    price: 100
});

const handleTransferMoney = () => {
    form.post(route('frontend.transfer-money'), {
        preserveScroll: true
        , onSuccess: () => {
            form.reset();
            document.body.classList.remove("no-scroll");
            document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
                panel.classList.remove("popopen");
            });
            document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
                backdrop.classList.remove("open");
            });
        }
    });
}

watch(() => form.bank_id, (value) => {
    if (!value) {
        form.bank_name = '';
        form.account_holder_name = '';
        form.account_number = '';
        form.ifsc_code = '';
        return;
    }

    const selectedBank = props.banks.find((bank) => bank.id == value);
    if (selectedBank) {
        form.bank_name = selectedBank.bank_name;
        form.account_holder_name = selectedBank.account_holder_name;
        form.account_number = selectedBank.account_number;
        form.ifsc_code = selectedBank.ifsc_code;
    }
});
</script>
