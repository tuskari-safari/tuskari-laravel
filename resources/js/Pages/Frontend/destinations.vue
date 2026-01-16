<template>
    <Banner pageName="Destination" />
    <div class="safari-style-sec with-filter  cmn-gap">
        <!-- <div class="dst-srch-flter">
            <Multiselect v-model="form.regionIds" placeholder="Select" class="multislect-dropdwn" mode="tags"
                :close-on-select="true" :searchable="true" :create-option="false" :options="regions" />
        </div> -->
        <div class="dstintion-chkbox-wrpr">
            <div class="ftrppup-checkbx-rw">
                <div class="ftrppup-checkbx-col">
                    <label class="ftrppup-chkbx-lbl"><input type="radio" value="" v-model="form.type"><span>All</span></label>
                </div>
                <div class="ftrppup-checkbx-col">
                    <label class="ftrppup-chkbx-lbl"><input type="radio" value="national_park" v-model="form.type"><span>National Park</span></label>
                </div>
                <div class="ftrppup-checkbx-col">
                    <label class="ftrppup-chkbx-lbl"><input type="radio" value="private_reserve" v-model="form.type"><span>Private Reserve</span></label>
                </div>
               
            </div>
        </div>
        <div class="safari-style-outer">
            <div class="row">
                <div class="col-lg-3 col-md-4 custom-col" v-for="(destination, index) in destinations.data"
                    :key="index">
                    <div class="populr-safr-item">
                        <div class="ppulr-safr-itm-top">

                            <figure class="ppulr-safrimgbx">
                                <img :src="destination?.full_thumbnail_url ?? 'frontend_assets/images/safari-d-img1.jpg'"
                                    alt="Safari Image">
                            </figure>
                        </div>
                        <div class="ppulr-safr-contbx">
                            <div class="cont">
                                <h3 class="ppulr-safrhd">
                                    <p>{{ destination?.name ??
                                        'NA' }}</p>
                                </h3>
                                <p>
                                    {{ destination?.short_description ?? 'NA' }}
                                </p>
                            </div>
                           
                            <div class="link">
                                <Link :href="route('frontend.national-park-details', destination?.name?.toLowerCase().replace(/\s+/g, '-'))"
                                    class="ft-exp-itmbutn">
                                Explore {{ destination?.name }}</Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!destinations?.data?.length">
                    <p class="text-center">No data found</p>
                </div>

            </div>
            <Pagination :pagination="destinations" :extraParams="{ type: form.type}" route-name="frontend.destinations" />
        </div>
    </div>
</template>


<script setup>
import Pagination from '@/components/customPaginate.vue'
import Banner from '@/components/Frontend/Banner.vue';
import { homeJs } from "@/custom.js";
import { onMounted, watch } from 'vue';
import Multiselect from '@vueform/multiselect'
import { router, useForm } from '@inertiajs/vue3';
import _ from "lodash";
const { pickBy } = _;

const props = defineProps({
    destinations: Object,
    regions: Object,
});

const urlParams = new URLSearchParams(typeof window !== 'undefined' ? window.location.search : '');
const form = useForm({
  regionIds: urlParams.getAll('regionIds') || [],
  type: urlParams.get('type') || ''
});

watch(() => form.regionIds, (newValue) => {
    form.get(route("frontend.destinations"), {
        preserveState: true,
        preserveScroll: true
    })
})

watch(() => form.type, () => {
  form.get(route("frontend.destinations"), {
    preserveState: true,
    preserveScroll: true
  })
})

onMounted(() => {
    homeJs();
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
