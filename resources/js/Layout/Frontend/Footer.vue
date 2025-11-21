<template lang="">
    <footer>
        <div class="max-footer">
            <div class="container ful-container">
                <div class="inrwrap-gap-ftr">
                    <div class="row-footer">
                        <div class="left-footer">
                            <div class="inr-footer">
                                <Link :href="route('frontend.index')" class="ftr-logo"><img
                                    :src="'/frontend_assets/Final Tuskari Logo White.svg'" alt="img"></Link>
                                <ul class="socil-links">
                                        <li>
                                            <a :href="footerData?.contactInfo?.facebook_link || 'javascript:void(0)'"
                                                :target="footerData?.contactInfo?.facebook_link ? '_blank' : null">
                                                Facebook
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="footerData?.contactInfo?.instagram_link || 'javascript:void(0)'"
                                                :target="footerData?.contactInfo?.instagram_link ? '_blank' : null">
                                                Instagram
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="footerData?.contactInfo?.tiktok_link || 'javascript:void(0)'"
                                                :target="footerData?.contactInfo?.tiktok_link ? '_blank' : null">
                                                TikTok
                                            </a>
                                        </li>
                                        <li>
                                            <a :href="footerData?.contactInfo?.youtube_link || 'javascript:void(0)'"
                                                :target="footerData?.contactInfo?.youtube_link ? '_blank' : null">
                                                YouTube
                                            </a>
                                        </li>
                                </ul>
                            </div>
                        </div>
                        <div class="right-footer">
                            <div class="rgt-ftr-inr">
                                <p v-html="footerData?.footerContent?.text_content"></p>
                                <a :href="route('frontend.safari-listings')" class="cmn-butn wht-butn">Get started</a>
                            </div>
                        </div>
                    </div>
                    <div class="down-ftr">
                        <ul class="social-link-ftr">
                            <li> <a :href="route('frontend.index')">Home</a> </li>
                            <li> <a :href="route('frontend.safari-listings')">Safaris</a> </li>
                            <li> <a href="#url">About us</a> </li>
                            <li> <a :href="route('frontend.blogs')">Blogs</a> </li>
                            <li> <a :href="route('frontend.operators')">Operator</a> </li>
                            <li> <a :href="route('frontend.country-guides')">Country Guides</a> </li>
                            <li> <a :href="route('frontend.contact-us')">Contact us</a> </li>
                        </ul>
                        <ul class="tersm-ul">
                            <li><a :href="route('frontend.terms-and-conditions')">Terms & conditions </a></li>
                            <li><a :href="route('frontend.privacy-policy')"> Privacy policy </a></li>
                            <li><a :href="route('frontend.index')"> {{$page.props.appName}}.</a> All rights reserved ©
                                {{ new Date().getFullYear() }} </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </footer>

</template>
<script setup>
import { ref, onMounted } from 'vue';
import service from '@/helpers/service';

const footerData = ref(null);

onMounted(async () => {
    try {
        const response = await service.getData(route('frontend.get-footer'));
        footerData.value = response ?? null;
    } catch (error) {
        console.error('Failed to fetch footer data:', error);
        footerData.value = null;
    }
});
</script>
<style lang="">

</style>