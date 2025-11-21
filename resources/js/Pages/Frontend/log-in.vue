<template>
    <div class="register_section cmn-gap pt-0">
        <div class="container ful-container">
            <div class="rgstrtn_box">
                <div class="row regstrtn_row gx-0">
                    <div class="col-lg-5 rgstrtn_lft_col align-self-center">
                        <div class="rgstr_frm_box form_box">
                            <h1>Login to your account</h1>
                            <p>Welcome back — pick up where you left off and get back to planning your next
                                adventure.</p>
                            <form @submit.prevent="handleSubmitLogin">
                                <div class="row form_row">
                                    <div class="col-12 form_col">
                                        <div class="login_toggle">
                                            <label class="switch switch_flat">
                                                <input class="switch_input" type="checkbox" v-model="form.role"
                                                    :true-value="'SAFARI_OPERATOR'" :false-value="'TRAVELLER'">
                                                <span class="switch_label" data-on="Traveller"
                                                    data-off="Safari operator"></span>
                                                <span class="switch_handle"></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-12 form_col">
                                        <label>Email address</label>
                                        <div class="input_hldr">
                                            <input type="email" placeholder="Enter email address" class="email_inp" v-model="form.email">
                                            <span class="text-danger" v-if="form.errors.email">{{form.errors.email}}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 form_col">
                                        <label>Password</label>
                                        <div class="input_hldr">
                                            <input type="password" placeholder="**************" class="pass_inp" v-model="form.password">
                                            <span class="text-danger" v-if="form.errors.password">{{form.errors.password}}
                                            </span>
                                            <button type="button" class="pass_shwHde_bttn">
                                                <img :src="'/frontend_assets/images/eye_slash_icon.svg'" alt=""
                                                    class="eye_slsh_icon">
                                                <img :src="'/frontend_assets/images/eye_icon.svg'" alt=""
                                                    class="eye_icon">
                                            </button>
                                            <span class="frgt_psswrd"><a
                                                    :href="route('frontend.forgot-password')">Forgot your
                                                    password?</a></span>
                                        </div>
                                    </div>
                                    <div class="col-12 form_col">
                                        <FrontendSubmitButton :isLoading="form.processing" :value="'Login now'" />
                                    </div>
                                    <div class="col-12 form_col">
                                        <p>Don't have an account? <a :href="form.role == 'TRAVELLER' ? route('frontend.register') : route('frontend.safari-operator-register-step-one')">Register</a></p>
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
                                <image :xlink:href="'/frontend_assets/images/dash_rght_img2.jpg'" x="0" y="0"
                                    width="100%" height="100%" style="clip-path: url(#svgClipping);"></image>
                            </svg>
                            <div class="small_corner_img_box">
                                <img :src="'/frontend_assets/images/small_dsh_rght_img2.jpg'" alt="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import { useForm } from '@inertiajs/vue3';
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';

const form = useForm({
    role: 'TRAVELLER',
    email:'',
    password: '',
});

const handleSubmitLogin = () => {
    form.post(route('frontend.login'),{
        preserveScroll: true
    });
};

onMounted(() => {
    homeJs();
    document.body.classList.remove("no-scroll");
});
</script>
