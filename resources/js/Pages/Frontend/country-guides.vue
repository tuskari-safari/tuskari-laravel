<template>
    <Meta :cms="props.meta" />
    <Banner pageName="Country Guide" />
    <div class="safari-style-sec with-filter  cmn-gap">
        <div class="safari-style-outer">
            <div class="row">
                <div class="col-lg-3 col-md-4 custom-col" v-for="(destination, index) in countries.data" :key="index">
                    <div class="populr-safr-item">
                        <div class="ppulr-safr-itm-top">
                            
                            <figure class="ppulr-safrimgbx">
                                <img :src="destination?.thumbnail_url ?? 'frontend_assets/images/safari-d-img1.jpg'"
                                    alt="Safari Image">
                            </figure>
                        </div>
                        <div class="ppulr-safr-contbx">
                            <div class="cont">
                                <h3 class="ppulr-safrhd">
                                    <Link :href="route('frontend.country-guide', destination?.name)">{{ destination?.name ??
                                        'NA' }}</Link>
                                </h3>
                                <p>
                                    {{ destination?.subtitle ?? 'NA' }}
                                </p>
                            </div>
                            <div class="link">
                                <Link :href="route('frontend.safari-listings', { location: destination?.name })" class="ft-exp-itmbutn">
                               Explore {{ destination?.name }} Safaris</Link>
                            </div>
                    </div>
                    </div>
                </div>
                <div v-if="!countries?.data?.length">
                    <p class="text-center">No data found</p>
                </div>

            </div>
            <Pagination :pagination="countries" route-name="frontend.country-guides" />
        </div>
    </div>
</template>


<script setup>
import Pagination from '@/components/customPaginate.vue'
import Banner from '@/components/Frontend/Banner.vue';
import { homeJs } from "@/custom.js";
import { onMounted } from 'vue';
import Meta from '../../components/Frontend/Meta.vue';



const props = defineProps({
    countries: Object,
    meta: Object
});

onMounted(() => {
    homeJs();
});
</script>
