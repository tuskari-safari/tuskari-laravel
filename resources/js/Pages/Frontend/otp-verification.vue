<template>
    <div class="register_section cmn-gap pt-0">
        <div class="container ful-container">
            <div class="rgstrtn_box">
                <div class="row regstrtn_row gx-0">
                    <div class="col-lg-5 rgstrtn_lft_col align-self-center">
                        <div class="rgstr_frm_box form_box">
                            <h1>OTP verification</h1>
                            <p>Just one last step — enter the code we sent to confirm it’s really you.</p>
                            <form @submit.prevent="submitOtp">
                                <div class="row form_row">
                                    <div class="col-12 form_col">
                                        <label>Please enter the code</label>
                                        <div class="otp_input_row">
                                            <div class="otp_input_col" v-for="(digit, index) in otpDigits" :key="index">
                                                <input class="otp_input" type="text" maxlength="1" inputmode="numeric"
                                                    placeholder="0" v-model="otpDigits[index]"
                                                    :ref="el => inputRefs[index] = el" @input="focusNext(index, $event)"
                                                    @paste="handlePaste(index, $event)"
                                                    @keydown.ctrl.v.prevent="handleCtrlVPaste(index, $event)" />
                                            </div>

                                            <span v-if="form.errors.otp" class="text-danger mt-2">
                                                {{ form.errors.otp }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col-12 form_col">
                                        <FrontendSubmitButton :isLoading="form.processing" :value="'Verify OTP'" />
                                    </div>

                                    <div class="col-12 form_col">
                                        <span class="back_login">Back to <a
                                                :href="route('frontend.login')">Login</a></span>
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
                                    width="100%" height="100%" style="clip-path: url(#svgClipping);" />
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
import { onMounted, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { homeJs } from "@/custom.js"
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue'

onMounted(() => {
    homeJs();
})

const otpDigits = ref(['', '', '', ''])
const inputRefs = []

const props = defineProps({
    email: String
});

const form = useForm({
    otp: '',
    email: props.email
});

const focusNext = (index, event) => {
    const value = event.target.value
    if (value && index < otpDigits.value.length - 1) {
        inputRefs[index + 1]?.focus()
    }
}

const handlePaste = (index, event) => {
    event.preventDefault()
    const pastedData = event.clipboardData.getData('text').trim()
    applyPastedOtp(pastedData)
}

const handleCtrlVPaste = async (index, event) => {
    try {
        const text = await navigator.clipboard.readText()
        applyPastedOtp(text.trim())
    } catch (err) {
        console.error('Clipboard read failed:', err)
    }
}

const applyPastedOtp = (text) => {
    if (!/^\d+$/.test(text)) return

    otpDigits.value = Array(otpDigits.value.length).fill('')

    inputRefs.forEach((input) => {
        if (input) input.value = ''
    })

    const digits = text.slice(0, otpDigits.value.length).split('')
    digits.forEach((digit, i) => {
        otpDigits.value[i] = digit
        if (inputRefs[i]) inputRefs[i].value = digit
    })

    inputRefs[digits.length - 1]?.focus()
}

const submitOtp = () => {
    form.otp = otpDigits.value.join('')
    form.post(route('frontend.otp-validation'))
}



</script>
