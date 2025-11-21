<template>
    <div class="main_wrppr_alt">
        <div class="register_section cmn-gap pt-0">
            <div class="container ful-container">
                <div class="rgstrtn_box">
                    <div class="row regstrtn_row gx-0">
                        <div class="col-lg-5 rgstrtn_lft_col align-self-center">
                            <div class="rgstr_frm_box form_box">
                                <h1>Start exploring</h1>
                                <p>Join Tuskari and share your safaris with the world.</p>
                                <form @submit.prevent="submitStepOne">
                                    <div class="row form_row">
                                        <div class="col-12 form_col">
                                            <label>Your full name <span class="text-danger">*</span></label>
                                            <div class="input_hldr">
                                                <input type="text" placeholder="Enter your name" class="user_inp"
                                                    v-model="form.full_name">
                                                <span class="text-danger" v-if="form.errors.full_name">{{
                                                    form.errors.full_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Safari Company Name<span class="text-danger">*</span></label>
                                            <div class="input_hldr">
                                                <input type="text" placeholder="Enter business/trading name"
                                                    class="trading_inp" v-model="form.business_name">
                                                <span class="text-danger" v-if="form.errors.business_name">{{
                                                    form.errors.business_name }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Email address<span class="text-danger">*</span></label>
                                            <div class="input_hldr">
                                                <input type="email" placeholder="Enter email address" class="email_inp"
                                                    v-model="form.email">
                                                <span class="text-danger" v-if="form.errors.email">{{ form.errors.email
                                                }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Your phone number<span class="text-danger">*</span></label>
                                            <div class="input_hldr input-extra phnenmbr-sect">
                                                <div class="sortng_input_col">
                                                    <Multiselect v-model="form.country_code" placeholder="Select"
                                                        class="slect-dropdwn-new" mode="single" :close-on-select="true"
                                                        :searchable="true" :create-option="false"
                                                        :options="countryCodes" />
                                                </div>
                                                <input type="text" placeholder="Enter phone number" class="phone_inp"
                                                    v-model="form.phone"
                                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">

                                            </div>
                                            <span class="text-danger" v-if="form.errors.phone">{{ form.errors.phone }}
                                            </span>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Your country of operation<span class="text-danger">*</span></label>
                                            <div class="input_hldr">
                                                <div class="input_hldr">
                                                    <div class="sortng_input_col">
                                                        <Multiselect v-model="form.country_id" placeholder="Select"
                                                            class="slect-dropdwn-new" mode="single"
                                                            :close-on-select="true" :searchable="true"
                                                            :create-option="false" :options="countries" />
                                                        <span class="text-danger" v-if="form.errors.country_id">{{
                                                            form.errors.country_id }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <input type="submit" value="Next">
                                        </div>
                                      
                                        <div class="col-12 form_col">
                                            <p>Already have an account? <a :href="route('frontend.login')">Login</a></p>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-lg-7 rgstrtn_rght_col">
                            <div class="rght_box">
                                <svg width="100%" viewBox="0 0 818 708" xmlns="http://www.w3.org/2000/svg">
                                    <clipPath id="svgClipping">
                                        <path
                                            d="M773 0C797.853 5.15408e-07 818 20.1472 818 45V638C818 676.66 786.66 708 748 708H429C390.34 708 359 676.66 359 638V424C359 399.147 338.853 379 314 379H70C31.3401 379 0 347.66 0 309V39C7.34552e-06 17.4609 17.4609 8.85859e-07 39 0H773Z"
                                            fill="white" />
                                    </clipPath>
                                    <image :xlink:href="'/frontend_assets/images/dash_rght_img.jpg'" x="0" y="0"
                                        width="100%" height="100%" style="clip-path: url(#svgClipping);"></image>
                                </svg>
                                <div class="small_corner_img_box">
                                    <img :src="'/frontend_assets/images/small_dsh_rght_img.jpg'" alt="Image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script setup>
import { onMounted, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { useForm, router } from '@inertiajs/vue3'
import Multiselect from '@vueform/multiselect'


const props = defineProps({
    countries: Object,
    countryCodes: Object
});

const form = useForm({
    full_name: '',
    email: '',
    business_name: '',
    phone: '',
    country_code: '',
    country_id: '',
});
onMounted(() => {
    homeJs()
    const stored = sessionStorage.getItem('stepOneForm')
    if (stored) {
        const parsed = JSON.parse(stored)
        for (const key in parsed) {
            form[key] = parsed[key]
        }
    }
})

const submitStepOne = () => {
    form.post(route('frontend.safari-operator-register-step-one'), {
        onSuccess: () => {
            sessionStorage.setItem('stepOneForm', JSON.stringify(form.data()))
            router.visit(route('frontend.safari-operator-register-step-two'))
        }
    })
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>