<template>
    <Banner pageName="Contact Us" />
    <div class="contact-us-top">
        <div class="container">
            <div class="content-wrapper">
                <div class="row top-cont-row">
                    <div class="col-md-6 cont-left-col">
                        <div class="left-content">
                            <h2>Other Ways to Reach Us</h2>
                            <p>Prefer email? Reach out directly:</p>
                            <div class="eml-listing">
                                <ul>
                                    <li>
                                        <div class="eml-box">
                                            <div class="eml-icon">
                                                <img :src="'frontend_assets/images/cont-email.png'" alt="img">
                                            </div>
                                            <div class="dtl-box">
                                                <h3>Traveller enquiries</h3>
                                                <a :href="'mailto:' + contactInfo?.traveller_enquiries"
                                                    v-if="contactInfo?.traveller_enquiries">{{
                                                        contactInfo?.traveller_enquiries }}</a>
                                                <span v-else>Not available</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="eml-box">
                                            <div class="eml-icon">
                                                <img :src="'frontend_assets/images/cont-email.png'" alt="img">
                                            </div>
                                            <div class="dtl-box">
                                                <h3>Operator partnerships</h3>
                                                <a v-if="contactInfo?.operator_partnerships"
                                                    :href="'mailto:' + contactInfo?.operator_partnerships">{{
                                                        contactInfo?.operator_partnerships }}</a>
                                                <span v-else>Not available</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="eml-box">
                                            <div class="eml-icon">
                                                <img :src="'frontend_assets/images/cont-email.png'" alt="img">
                                            </div>
                                            <div class="dtl-box">
                                                <h3>Press & media</h3>
                                                <a v-if="contactInfo?.press_media"
                                                    :href="'mailto:' + contactInfo?.press_media">{{
                                                        contactInfo?.press_media }}</a>
                                                <span v-else>Not available</span>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="eml-box">
                                            <div class="eml-icon">
                                                <img :src="'frontend_assets/images/cont-email.png'" alt="img">
                                            </div>
                                            <div class="dtl-box">
                                                <h3>General</h3>
                                                <a v-if="contactInfo?.general"
                                                    :href="'mailto:' + contactInfo?.general">{{ contactInfo?.general
                                                    }}</a>
                                                <span v-else>Not available</span>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 cont-right-col">
                        <div class="form-outer">
                            <div class="form-title-bx">
                                <div class="form-head">Send us a message</div>
                                <p>
                                    Kindly submit your enquiry and we will respond shortly.
                                </p>
                            </div>
                            <div class="form_box">
                                <form @submit.prevent="sendContactForm">
                                    <div class="row form_row">
                                        <div class="col-12 form_col">
                                            <label>Your full name</label>
                                            <div class="input_hldr">
                                                <input type="text" required placeholder="Enter your name"
                                                    v-model="form.full_name">
                                                <span class="text-danger" v-if="form.errors.full_name">{{
                                                    form.errors.full_name }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Email address</label>
                                            <div class="input_hldr">
                                                <input type="email" required placeholder="Enter email address"
                                                    v-model="form.email">
                                                <span class="text-danger" v-if="form.errors.email">{{
                                                    form.errors.email }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <label>Message</label>
                                            <div class="input_hldr">
                                                <textarea placeholder="Enter your message..." required
                                                    v-model="form.message"></textarea>
                                                <span class="text-danger" v-if="form.errors.message">{{
                                                    form.errors.message }}</span>
                                            </div>
                                        </div>
                                        <div class="col-12 form_col">
                                            <input type="submit" value="Send message">
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

    <div class="the-wild-sec cont-page">
        <div class="wild-wrap">
            <img :src="'frontend_assets/images/wild-img.jpg'" alt="img" class="wild-img wild-img-dsktp">
            <img :src="'frontend_assets/images/mb-trvelbetterimg1.jpg'" alt="img" class="wild-img wild-img-mb">
            <div class="container">
                <div class="inr-wild">
                    <h2 class="whth2">Where We’re Based</h2>
                    <p>
                        We’re a remote-first company, with roots in London and deep ties
                        across Africa.
                    </p>
                    <div class="form-wild-wrap">
                        <form>
                            <div class="wrap-form-inr">
                                <input type="email" placeholder="Enter your email">
                                <input type="submit" value="Join the herd">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="faq-section-cont">
        <div class="container">
            <div class="h2-title">Frequently Asked Questions</div>
            <div class="faq faqoutwrpr">
                <div class="row faq-row">
                    <h3 class="text-center" v-if="!faqPart1 && !faqPart2">No Faq found</h3>
                    <div class="col-md-6">
                        <div class="faq-container" v-for="faq1 in faqPart1" :key="'faq1-' + faq1.id">
                            <div class="qution">
                                <h3>{{ faq1?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                </div>
                            </div>
                            <div class="anwers" style="display: block">
                                <p>
                                    {{ faq1?.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="faq-container" v-for="faq2 in faqPart2" :key="'faq2-' + faq2.id">
                            <div class="qution">
                                <h3>{{ faq2?.question }}</h3>
                                <div class="rightIcon">
                                    <img :src="'frontend_assets/images/faq-arrow.png'" alt="Arrow">
                                </div>
                            </div>
                            <div class="anwers" style="display: block">
                                <p>
                                    {{ faq2?.answer }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Banner from '@/components/Frontend/Banner.vue';
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    full_name: '',
    email: '',
    message: '',
});


const props = defineProps({
    contactInfo: Object,
    faqPart1: Object,
    faqPart2: Object
});

const sendContactForm = () => {
    form.post(route('frontend.contact-us'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
    });
}

onMounted(() => {
    homeJs();
})
</script>