<template>
    <div class="panel_rght_cntnt_area">
        <div class="trips_wrppr mt-0">
            <div class="sfri-serch-list-wrap svd-sfr-wrpr">
                <div class="row sfl-list-row">
                    <div class="col-xl-3 col-lg-4 col-md-6 sfl-list-col" v-for="safari in safaris.data"
                        :key="safari.id">
                        <SafariCard :safari="safari" :isSavedSafari="true" />
                    </div>
                </div>
                <div>
                    <h3 v-if="!safaris.data.length" class="text-center mt-5">No safaris found.</h3>
                </div>
            </div>
            <div class="common-pagination save-pagination">
                <Pagination :pagination="safaris" route-name="frontend.saved-safaris" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import SafariCard from '@/components/Frontend/SafariCard.vue'
import { homeJs } from '@/custom.js'
import Pagination from '@/components/customPaginate.vue'

const props = defineProps({
    safaris: Object
})

onMounted(() => {
    homeJs();
    emit.emit("pageName", "Your Saved Safaris");
    emit.emit("pageSubTitle", "Safaris you’ve bookmarked for later");

})


</script>