<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="supp_bnr_wrap">
            <div class="wild-wrap">
                <img :src="'/frontend_assets/images/support-banner.jpg'" alt="img" class="wild-img wild-img-dsktp">
                <div class="container">
                    <div class="inr-wild">
                        <h2 class="whth2">How can we help?</h2>
                        <div class="form-wild-wrap">
                            <form>
                                <div class="wrap-form-inr">
                                    <input type="text" placeholder="Search here something" />
                                    <button type="submit" class="cmn-butn">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="banner_under_menu suprtlist-menu">
            <ul class="listingfiltermenu-list">
                <li :class="{ active: activeSection === 'quick-help' }">
                    <a class="cmn-butn" href="javascript:void(0)"
                        @click.prevent="handleClickHelpSupport('quick-help')">Quick
                        Help (FAQs)</a>
                </li>
                <li :class="{ active: activeSection === 'booking-trip-support' }"><a class="cmn-butn"
                        href="javascript:void(0)"
                        @click.prevent="handleClickHelpSupport('booking-trip-support')">Booking & Trip Support</a></li>
                <li :class="{ active: activeSection === 'payments-pricing' }"><a class="cmn-butn"
                        href="javascript:void(0)" @click.prevent="handleClickHelpSupport('payments-pricing')">Payments &
                        Pricing</a></li>
                <li :class="{ active: activeSection === 'trip-customisation' }"><a class="cmn-butn"
                        href="javascript:void(0)" @click.prevent="handleClickHelpSupport('trip-customisation')">Trip
                        Customisation</a></li>
                <li :class="{ active: activeSection === 'account-dashboard' }"><a class="cmn-butn"
                        href="javascript:void(0)" @click.prevent="handleClickHelpSupport('account-dashboard')">Account &
                        Dashboard</a></li>
                <li :class="{ active: activeSection === 'contact' }"><a class="cmn-butn" href="javascript:void(0)"
                        @click.prevent="handleClickHelpSupport('contact')">Contact Support</a></li>
                <li :class="{ active: activeSection === 'feedback' }"><a class="cmn-butn" href="javascript:void(0)"
                        @click.prevent="handleClickHelpSupport('feedback')">Feedback & Improvements</a></li>
            </ul>
        </div>

        <div class="accrdn_card" v-if="!showContactSection && !showFeedbackSection">
            <div class="row faq-row">
                <h3 class="text-center" v-if="faqPart1.length === 0 && faqPart2.length === 0">No FAQ found</h3>
                <Accordion value="0" expandIcon="pi pi-angle-right" collapseIcon="pi pi-angle-down">
                    <div class="col-md-12 mb-3" v-for="(faq1, index) in currentFaqs" :key="'faq1-' + index">
                        <AccordionPanel :value="`${activeSection}-${index + 1}`" class="faq_contnr">
                            <AccordionHeader class="qstn">{{ faq1?.question }}</AccordionHeader>
                            <AccordionContent class="answr_pnl">
                                <p class="m-0">
                                    {{ faq1.answer }}
                                </p>
                            </AccordionContent>
                        </AccordionPanel>
                    </div>
                </Accordion>
            </div>
        </div>

        <div class="contact-section" v-if="showContactSection">
            <div class="accrdn_card">
                <div class="row faq-row col-two">
                    <Accordion value="0" expandIcon="pi pi-angle-right" collapseIcon="pi pi-angle-down">
                        <div class="col-md-6 mb-3">
                            <AccordionPanel :value="0" class="faq_contnr">
                                <AccordionHeader class="qstn">In dashboard live Chat</AccordionHeader>
                                <AccordionContent class="answr_pnl">
                                    <p class="m-0">
                                        Live chat is currently offline. Please leave us a message and we’ll get back to
                                        you
                                        ASAP.
                                    </p>
                                </AccordionContent>
                            </AccordionPanel>
                        </div>
                        <div class="col-md-6 mb-3">
                            <AccordionPanel :value="1" class="faq_contnr">
                                <AccordionHeader class="qstn">Email Support (support@tuskari.com)</AccordionHeader>
                                <AccordionContent class="answr_pnl">
                                    <p class="m-0">
                                        <a class="cmn-accor-btn"
                                            href="https://mail.google.com/mail/?view=cm&fs=1&to=support@tuskari.com"
                                            target="_blank"> <span><svg xmlns="http://www.w3.org/2000/svg" width="1024"
                                                    height="1024" viewBox="0 0 1024 1024">
                                                    <path fill="currentColor"
                                                        d="M128 224v512a64 64 0 0 0 64 64h640a64 64 0 0 0 64-64V224zm0-64h768a64 64 0 0 1 64 64v512a128 128 0 0 1-128 128H192A128 128 0 0 1 64 736V224a64 64 0 0 1 64-64" />
                                                    <path fill="currentColor"
                                                        d="M904 224L656.512 506.88a192 192 0 0 1-289.024 0L120 224zm-698.944 0l210.56 240.704a128 128 0 0 0 192.704 0L818.944 224z" />
                                                </svg></span>
                                            Email support
                                        </a>
                                    </p>
                                </AccordionContent>
                            </AccordionPanel>
                        </div>
                        <div class="col-md-6 mb-3">
                            <AccordionPanel :value="2" class="faq_contnr">
                                <AccordionHeader class="qstn">Message your operator</AccordionHeader>
                                <AccordionContent class="answr_pnl">
                                    <p class="m-0">
                                        <a class="cmn-accor-btn" :href="route('frontend.messages')" target="_blank">
                                            <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24">
                                                    <g fill="currentColor">
                                                        <path
                                                            d="M2 6a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2h-4.586l-2.707 2.707a1 1 0 0 1-1.414 0L8.586 19H4a2 2 0 0 1-2-2zm18 0H4v11h5a1 1 0 0 1 .707.293L12 19.586l2.293-2.293A1 1 0 0 1 15 17h5z" />
                                                        <path
                                                            d="M13.5 11.5a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0m4 0a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0m-8 0a1.5 1.5 0 1 1-3 0a1.5 1.5 0 0 1 3 0" />
                                                    </g>
                                                </svg></span> Message
                                            your operator</a>
                                    </p>
                                </AccordionContent>
                            </AccordionPanel>
                        </div>
                    </Accordion>
                </div>
            </div>
            <p>Need help from a real person? Our team’s got your back every step of the way - just
                reach out.</p>
        </div>
        <div v-if="showFeedbackSection" class="feedback-section p-4 bg-white rounded feed-sec-new">
            <!-- 1. Something didn’t work as expected -->
            <button @click="somethingDidNotWorkVisible = true" class="cmn-accor-btn" label="Show"> <span>
                    <Icon icon="material-symbols:error-outline" width="24" height="24" />
                </span>Something
                didn’t work as
                expected?</button>
            <Dialog v-model:visible="somethingDidNotWorkVisible" modal header="Something didn’t work as expected?"
                :style="{ width: '60rem' }" :draggable="false">
                <form @submit.prevent="HandleSubmitSomethingDidntWork">
                    <div class="p-fluid grid">
                        <div class="col-12 md:col-6">
                            <label for="issue">Describe the issue </label>
                            <Textarea id="issue" v-model="somethingDidntWorkform.issue" rows="5" autoResize
                                placeholder="Describe the issue..." class="mb-3" required />
                            <span class="text-danger" v-if="somethingDidntWorkform.errors.issue">{{
                                somethingDidntWorkform.errors.issue
                            }}</span>
                        </div>

                        <div class="col-12 md:col-6">
                            <label for="pageUrl">Page URL </label>
                            <InputText id="pageUrl" v-model="somethingDidntWorkform.pageUrl" placeholder="https://"
                                class="mb-3" requierd />
                            <span class="text-danger" v-if="somethingDidntWorkform.errors.pageUrl">{{
                                somethingDidntWorkform.errors.pageUrl
                            }}</span>
                        </div>

                        <div class="col-12 md:col-6">
                            <label for="deviceInfo">Device/Browser Info </label>
                            <InputText id="deviceInfo" required v-model="somethingDidntWorkform.deviceInfo"
                                placeholder="Eg: Chrome, iOS, etc." class="mb-3" />
                            <span class="text-danger" v-if="somethingDidntWorkform.errors.deviceInfo">{{
                                somethingDidntWorkform.errors.deviceInfo
                            }}</span>
                        </div>

                        <div class="col-12 md:col-6">
                            <label for="userId">Submitted by </label>
                            <InputText :value="$page.props.auth.user?.full_name" disabled class="mb-3" />
                        </div>

                        <!-- Screenshot Upload -->
                        <div class="col-12 mb-3">
                            <label for="screenshot">Screenshot Upload </label>
                            <file-upload @input="somethingDidntWorkform.screenshot = $event.target.files[0]"
                                :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="somethingDidntWorkform.errors.screenshot">{{
                                somethingDidntWorkform.errors.screenshot
                            }}
                            </span>
                        </div>

                        <!-- Submit Button -->
                        <div class="col-12">
                            <FrontendSubmitButton :isLoading="somethingDidntWorkform.processing"
                                :value="'Submit Feedback'" />
                        </div>
                    </div>
                </form>
            </Dialog>

            <!-- 2. Rate your experience with Tuskari -->
            <button @click="ratingVisible = true" class="cmn-accor-btn" label="Show"> <span>
                    <Icon icon="material-symbols:star-outline" width="32" height="32" />
                </span>Rate your experience
                with
                Tuskari</button>

            <Dialog v-model:visible="ratingVisible" modal header="Rate your experience with Tuskari"
                :style="{ width: '40rem' }" :draggable="false">
                <div class="p-fluid grid">
                    <form @submit.prevent="HandleSubmitRating">
                        <div class="col-12">
                            <div class="flex align-items-center gap-3 mb-3">
                                <label for="rating" class="mb-0 font-medium">Your Rating</label>
                                <Rating v-model="ratingForm.rating">
                                    <template #onicon>
                                        <Icon icon="tabler:star-filled" width="25" height="25" />
                                    </template>
                                    <template #officon>
                                        <Icon icon="tabler:star" width="25" height="25" />
                                    </template>
                                </Rating>
                                <span class="text-danger" v-if="ratingForm.errors.rating">{{ ratingForm.errors.rating
                                }}</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label for="comment">Comments </label>
                            <Textarea id="comment" v-model="ratingForm.comment" placeholder="Tell us more..." autoResize
                                rows="4" class="w-full mb-3" />
                            <span class="text-danger" v-if="ratingForm.errors.comment">{{ ratingForm.errors.comment
                            }}</span>
                        </div>

                        <div class="col-12">
                            <FrontendSubmitButton :isLoading="ratingForm.processing" :value="'Submit'" />
                        </div>
                    </form>
                </div>
            </Dialog>

            <p>Tuskari is still growing — your feedback helps shape a better experience for everyone
                travelling Africa. Thanks for being part of the journey.</p>
        </div>
        <div class="bttm_bttn_hlder">
            <div class="allbttn_hlder">

                <div class="indvdl_bttn_hlder">
                    <a class="cmn-butn" :href="route('frontend.contact-us')" target="_blank">Email Us</a>
                </div>
                <div class="indvdl_bttn_hlder">
                    <a class="cmn-butn" href="javascript:void(0)">Start Live Chat</a>
                </div>
                <div class="indvdl_bttn_hlder">
                    <a class="cmn-butn" href="javascript:void(0)" @click="isVisible">Report a Problem</a>
                </div>
            </div>
        </div>
    </div>
    <ReportIssueDialog v-model:visible="reportProblemVisible" :key="reportProblemVisible" />

    <Dialog v-model:visible="searchModalVisible" modal header="Search Results:" :style="{ width: '60rem' }">
        <div v-if="Object.keys(groupedSearchResults).length > 0">
            <div v-for="(items, tag) in groupedSearchResults" :key="tag" class="search-tag-section">
                <h3 class="tag-heading">{{tag.replace(/-/g, ' ').replace(/\b\w/g, char => char.toUpperCase())}} :</h3>
                <div v-for="(item, index) in items" :key="index" class="qa-box">
                    <p class="question"><strong>Q:</strong> {{ item.question }}</p>
                    <p class="answer"><strong>A:</strong> {{ item.answer }}</p>
                </div>
            </div>
        </div>
        <div v-else>
            <p>No results found.</p>
        </div>
    </Dialog>
</template>

<script setup>
import { onMounted, ref, computed, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { route } from 'ziggy-js';
import FileUpload from "@/components/FileUpload.vue";
import { useForm } from '@inertiajs/vue3';
import ReportIssueDialog from "@/components/Frontend/ReportProblemDialog.vue";
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';

const props = defineProps({
    faqs: Object,
    websiteRating: Object
});

const somethingDidNotWorkVisible = ref(false);
const ratingVisible = ref(false);
const reportProblemVisible = ref(false);
const activeSection = ref('quick-help');
const expandedPanel = ref(null);
const showContactSection = ref(false);
const showFeedbackSection = ref(false);
const searchModalVisible = ref(false);
const searchQuery = ref('');
const groupedSearchResults = ref({});

const somethingDidntWorkform = useForm({
    issue: '',
    pageUrl: '',
    deviceInfo: '',
    screenshot: null
});
const isVisible = () => {
    reportProblemVisible.value = true
}
const ratingForm = useForm({
    rating: props.websiteRating?.rating ?? '',
    comment: props.websiteRating?.comment ?? ''
});

const HandleSubmitRating = () => {
    const ratingId = props.websiteRating?.id;
    const routeName = 'frontend.submit-rating-for-website';
    const routeParams = ratingId ? route(routeName, ratingId) : route(routeName);

    ratingForm.post(routeParams, {
        preserveScroll: true,
        onSuccess: () => {
            ratingVisible.value = false;
        },
    });
};

const HandleSubmitSomethingDidntWork = () => {
    somethingDidntWorkform.post(route('frontend.something-did-not-work.store'), {
        preserveScroll: true,
        onSuccess: () => {
            somethingDidntWorkform.reset();
            somethingDidNotWorkVisible.value = false;
        },
    });
}

const currentFaqs = computed(() => {
    return props.faqs[activeSection.value] || [];
});

const faqPart1 = computed(() => {
    const half = Math.ceil(currentFaqs.value.length / 2);
    return currentFaqs.value.slice(0, half);
});

const faqPart2 = computed(() => {
    const half = Math.ceil(currentFaqs.value.length / 2);
    return currentFaqs.value.slice(half);
});

onMounted(() => {
    homeJs();
     emit.emit("pageName", "Need a Hand?");
    emit.emit("pageSubTitle", "Get answers, tips, and support anytime");

});

const handleClickHelpSupportSearch = async () => {
    try {
        if (!searchQuery.value) {
            return
        }
        const response = await axios.get('/search-help-support', {
            params: {
                searchQuery: searchQuery.value
            }
        })
        groupedSearchResults.value = response.data.groupedSearchResults || {}
        searchModalVisible.value = true
    } catch (error) {
        console.error('Search failed:', error)
    }
}

watch(searchModalVisible, (newVal) => {
    if (!newVal) {
        searchQuery.value = ''
        groupedSearchResults.value = {}
    }
})

const handleClickHelpSupport = (name) => {
    activeSection.value = name;
    expandedPanel.value = null;
    showContactSection.value = (name === 'contact');
    showFeedbackSection.value = (name === 'feedback');
};
</script>