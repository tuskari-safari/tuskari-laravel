<template>
    <Meta :cms="props.meta" />
    <Banner :pageName="pageName" />
    <div class="safari-style-sec with-filter cmn-gap">
        <div class="safari-style-filer">
            <div class="row">
                <div class="col-lg-9">
                    <div class="parktype-searchbox">
                        <ul class="listingfiltermenu-list">
                        <li :class="{ active: sortParam == 'ALL' }">
                            <button class="cmn-butn" @click="handleFilterPark('ALL')">All Parks</button>
                        </li>
                        <li :class="{ active: sortParam == 'is_most_popular' }">
                            <button class="cmn-butn" @click="handleFilterPark('is_most_popular')">Most Popular</button>
                        </li>
                        <li :class="{ active: sortParam == 'is_hidden_gem' }">
                            <button class="cmn-butn" @click="handleFilterPark('is_hidden_gem')">Hidden Gems</button>
                        </li>
                    </ul>
                    </div>
                </div>

                <div class="col-lg-3">
                    <div class="parktype-searchbox">
                        <TreeSelect v-model="selectedCities" :options="treeData" placeholder="Select countries" selectionMode="checkbox" display="comma" filter 
                            filterPlaceholder="Search countries" filterBy="label" filterMode="lenient" :filterExpanded="true" @update:modelValue="onTreeSelectChange"/>
                        <button type="button" v-if="Object.keys(selectedCities).length" 
                            class="cmn-butn wht-butn sflst-restbutn ml-1" 
                            @click="resetTree">
                            <Icon icon="iconamoon:close-bold" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="safari-style-outer">
            <div class="row">
                <div class="col-lg-3 col-md-4 custom-col" v-for="(park, index) in parksReserves.data" :key="index">
                    <div class="populr-safr-item">
                        <div class="ppulr-safr-itm-top">
                            
                            <figure class="ppulr-safrimgbx">
                                <img :src="park.full_thumbnail_url ?? '/frontend_assets/images/safari-d-img1.jpg'"
                                    alt="Safari Image">
                            </figure>
                        </div>
                        <div class="ppulr-safr-contbx">
                            <div class="cont">
                                <h3 class="ppulr-safrhd">
                                    <Link :href="route('frontend.national-park-details', park.name)">{{ park.name ??
                                        'NA' }}</Link>
                                </h3>
                                <p>
                                    {{ park?.short_description && park.short_description.length > 150
                                        ? park.short_description.slice(0, 150) + '...'
                                        : park?.short_description }}
                                </p>
                            </div>
                            <div class="link">
                                <Link :href="route('frontend.national-park-details', park.name)" class="ft-exp-itmbutn">
                                View Details</Link>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!parksReserves?.data?.length">
                    <p class="text-center">{{ 'No national parks or private reserve Found' }}</p>
                </div>
            </div>
            <Pagination :pagination="parksReserves" route-name="frontend.national-park-reserves"  :extraParams="computedExtraParams"/>
        </div>
    </div>
</template>

<script setup>
import Pagination from '@/components/customPaginate.vue'
import Banner from '@/components/Frontend/Banner.vue';
import { computed, onMounted, ref, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { router } from '@inertiajs/vue3';
import _ from "lodash";
import Meta from '../../components/Frontend/Meta.vue';
const { debounce } = _;

const params = new URLSearchParams(typeof window !== 'undefined' ? window.location.search : '');
const selectedCities = ref({});
const filteredCountries = ref([]);
const sortParam = ref('ALL');

const props = defineProps({
    parksReserves: Object,
    regionData: Array,
    meta: Object
});
const treeData = ref(props.regionData);

const onTreeSelectChange = (newVal) => {
    selectedCities.value = newVal;

    const childKeys = [];
    const traverse = (nodes) => {
      nodes.forEach(node => {
        if (node.children) {
          traverse(node.children);
        } else {
          if (newVal[node.key]?.checked) {
            childKeys.push(node.key);
          }
        }
      });
    };

    traverse(treeData.value);
    filteredCountries.value = childKeys;
};

const heading = computed(() => {
    if (props.type === 'national_park') {
        return 'Every park has a story. Which one will you step into next?';
    } else if (props.type === 'private_reserve') {
        return 'Smaller crowds, deeper moments — this is safari, reimagined';
    } else {
        return 'Explore All Parks & Reserves';
    }
});

watch(()=>filteredCountries.value, debounce((filteredCountries) => {
    const data = {};
    if (sortParam.value && sortParam.value !== 'ALL') {
        data.sort = sortParam.value;
    }
    if (filteredCountries) {
        data.countryId = filteredCountries;
    }

     router.visit(route('frontend.national-park-reserves', {type: props.type}), {
        method: 'get',
        data,
        preserveState: true,
        preserveScroll: true
    });
}, 500)); 

const pageName = computed(() => {
    if (props.type === 'national_park') {
        return 'National Park';
    } else if (props.type === 'private_reserve') {
        return 'Private Reserves';
    } else {
        return 'National Park';
    }
});

const computedExtraParams = computed(() => {
    const params = {};
    if (props.type) params.type = props.type;
    if (sortParam.value && sortParam.value !== 'ALL') params.sort = sortParam.value;

     if (filteredCountries.value.length) {
        params.countryId = filteredCountries.value;
    }

    return params;
});

const handleFilterPark = (sort) => {
    resetTree();
    sortParam.value = sort;
    const data = {};

    if (sort !== 'ALL') {
        data.sort = sort;
    }

    router.visit(route('frontend.national-park-reserves'), {
        method: 'get',
        data,
        preserveState: true,
        preserveScroll: true
    });
}

onMounted(() => {
    homeJs();
    const selectedFromUrl = getArrayParam('countryId');
    if (selectedFromUrl.length) {
        const initialSelected = {};
        selectedFromUrl.forEach(id => {
            initialSelected[id] = { checked: true };
        });
        selectedCities.value = initialSelected;
    }
});
const getArrayParam = (paramName) => {
    const values = [];
    for (const [key, value] of params.entries()) {
        if (key.startsWith(`${paramName}[`)) {
            values.push(value);
        }
    }
    return values;
}

const resetTree = () => {
    selectedCities.value = {};
    filteredCountries.value = [];
}
</script>
