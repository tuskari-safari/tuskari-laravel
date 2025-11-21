<template>
    <div class="panel_rght_cntnt_area">
        <div class="trips_wrppr mt-0">
            <div class="trips_hder trips_header updtsflst-hdr">
                <div class="sf-lst-hdr-lftpnl">
                    <div class="srtng_box">
                         <button @click="fetchSafari('all')" class="cmn-butn mr-1">All Safaris</button>
                        <button @click="fetchSafari('draft')" class="cmn-butn mr-1">Draft Safaris</button>
                    </div>
                </div>
                <div class="hdr_rght_prt ms-auto">
                    <div class="srtng_box">
                        <a :href="route('frontend.safari-create-listing')" class="cmn-butn">Create new listing</a>
                    </div>
                </div>
            </div>
            <div class="sfri-serch-list-wrap">
                <div class="row sfl-list-row">
                    <div class="col-xl-3 col-lg-4 col-md-6 safari-list-col" v-for="safari in safaris.data"
                        :key="safari.id">
                        <SafariCard :safari="safari" />
                    </div>
                </div>
                <div>
                    <h3 v-if="!safaris.data.length" class="text-center mt-5">No safaris found.</h3>
                </div>
            </div>
            <div class="common-pagination">
                <Pagination :pagination="safaris" route-name="frontend.safari-manage-listing" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import SafariCard from '@/components/Frontend/SafariCard.vue'
import { homeJs } from '@/custom.js'
import Pagination from '@/components/customPaginate.vue'
import { router } from "@inertiajs/vue3";

const props = defineProps({
    safaris: Object
});

onMounted(() => {
    homeJs();
    emit.emit("pageName", "Your Safari Listings");
    emit.emit("pageSubTitle", "Manage and update your trips");
});

const fetchSafari = (slug) => {
    if (slug === 'all') {
        router.visit(route("frontend.safari-manage-listing"), {
            method: "get",
            preserveScroll: true,
        });
    }
    else{
        router.visit(route("frontend.safari-manage-listing"), {
            method: "get",
            data: {
                status: slug
            },
            preserveScroll: true,
        });
    }
}


</script>
