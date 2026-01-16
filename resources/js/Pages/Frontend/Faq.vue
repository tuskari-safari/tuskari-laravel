<template>
    <Meta :cms="props.meta" />
    <Banner pageName="faq" />
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
import Meta from '../../components/Frontend/Meta.vue';

const props = defineProps({
    contactInfo: Object,
    faqPart1: Object,
    faqPart2: Object,
    meta: Object
});

onMounted(() => {
    homeJs();
})
</script>