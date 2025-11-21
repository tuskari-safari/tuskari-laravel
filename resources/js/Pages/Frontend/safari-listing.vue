<template>
    <Banner pageName="Safari" />
    <div class="safr-list-frmwrpr">
        <div class="bnr-form-lft">
            <div class="bnrformlbel">Your Safari</div>
            <div class="bnr-form-wrpr new_frm_bnnr new_frm_bnnr_alt">
                <form ref="countryModalRef">
                    <div class="bnrform">
                        <div class="bnrfrmleft-prt">
                            <div class="hm-bnrfrmleft-inr">
                                <div class="bnrfrm-fld bnrfrm-fld-1">
                                    <div class="bnrfrm-inputwrp bnr-inputadrs bnr-loacteinput updted_lctn_input">
                                        <i class="bnrfrmicon"><img :src="'frontend_assets/images/locationicon.svg'"
                                                alt="location"></i>
                                        <AutoComplete v-model="selectedDestinations" optionLabel="label"
                                            placeholder="Where do you want to go safari?"
                                            :suggestions="filteredDestinations" @complete="searchDestinations"
                                            @item-select="storeSelection" class="multiselect-dropdwn"
                                            :show-clear="true" />
                                    </div>
                                </div>
                                <div class="bnrfrm-fld bnrfrm-fld-2">
                                    <div class="bnrfrm-inputwrp date datefrmat">
                                            <DateRange v-model="form.dateRange" :minDate="new Date()" :format="formatDate" />
                                        </div>
                                </div>
                                <div class="bnrfrm-fld bnrfrm-fld-3">
                                    <div class="bnrfrm-inputwrp">
                                        <div class="bnrad-trveler-butn" @click="visible = !visible">
                                            <i class="bnrfrmicon"><img :src="'frontend_assets/images/usericon.svg'"
                                                    alt="location"></i>

                                            <p v-if="form.numberOfAdults || form.numberOfChildren"> {{
                                                form.numberOfAdults }} {{ form.numberOfAdults > 1 ? 'Adults' : 'Adult'
                                                }} {{ form.numberOfChildren }} {{ form.numberOfChildren > 1 ? 'Children'
                                                    : 'Child' }}</p>
                                            <p v-else>How many travellers?</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="hmbnr-quantity-pop" v-if="visible">
                                <div class="ftrppup-chckbxwrpr hmbnr-quantity">

                                    <div class="fltr-trv-qunttybx">
                                        <div class="fltr-trv-quntcont">
                                            <div class="fltr-trv-qunthd">Adult</div>
                                        </div>
                                        <div class="fltr-qunt-new">
                                            <InputNumber v-model="form.numberOfAdults" showButtons
                                                buttonLayout="horizontal" style="width: 20%" :min="0">
                                                <template #incrementbuttonicon>
                                                    <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                                </template>
                                                <template #decrementbuttonicon>
                                                    <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                                </template>
                                            </InputNumber>
                                        </div>

                                    </div>
                                    <div class="fltr-trv-qunttybx">
                                        <div class="fltr-trv-quntcont">
                                            <div class="fltr-trv-qunthd">Child</div>
                                        </div>
                                        <div class="fltr-qunt-new">
                                            <InputNumber v-model="form.numberOfChildren" showButtons
                                                buttonLayout="horizontal" style="width: 20%" :min="0">
                                                <template #incrementbuttonicon>
                                                    <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                                </template>
                                                <template #decrementbuttonicon>
                                                    <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                                </template>
                                            </InputNumber>
                                        </div>
                                    </div>

                                     <div class="fltr-trv-qunttybx">
                                          <button type="button" class="cmn-butn" @click="visible = !visible">Done</button>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="sf-lst-bnrfrmbutnbx">
                            <button type="button" class="cmn-butn bnrfrmbutn sf-filterbutn"
                                data-target="popup1">Filter</button>
                            <button type="button" class="cmn-butn wht-butn sflst-restbutn ml-1" @click="resetSearch"
                                style="border: 1px solid #e2e5eb;">
                                <Icon icon="iconamoon:close-bold" />
                            </button>
                        </div>
                    </div>
                    <!-- <div v-if="openCountryModal" class="locate-modal-wrpr">
                        <div v-if="Object.keys(filteredCountries).length > 0" class="locate-modal-inr">
                            <div v-for="(item, key) in filteredCountries" :key="key" class="search-tag-section">
                                <label class="search-tag-chckbx">
                                    <input type="checkbox" :value="item.name" v-model="form.location" />
                                    <span>{{ item?.name }}</span>
                                </label>
                            </div>
                        </div>
                        <div v-else>
                            <p>No results found.</p>
                        </div>
                        <div class="srchlcate-rmbebutn-bx" v-if="Object.keys(filteredCountries).length > 0">
                            <a href="javascript:void(0)" @click.prevent="form.location = []" class="srch-clearallbutn">
                                Clear All
                            </a>
                            <button @click="applySelection" class="cmn-butn sechlcte-donebutn">
                                Done
                            </button>
                        </div>
                    </div> -->
                </form>
            </div>
        </div>
        <div class="bnr-form-rgt">
            <div class="bnrformlbel">Sort by:</div>
            <div class="bnr-form-wrpr">
                <form>
                    <div class="bnr-form-slctpart">
                        <div class="bnrfrm-fld bnrfrm-fld-slct1">
                            <div class="bnrfrm-inputwrp">
                                <select class="bnrfrm-select" v-model="form.sortByPrice" @change="search('price')">
                                    <option value="">Price</option>
                                    <option value="price_high">Highest</option>
                                    <option value="price_low">Lowest</option>
                                </select>
                            </div>
                        </div>
                        <div class="bnrfrm-fld bnrfrm-fld-slct2">
                            <div class="bnrfrm-inputwrp">
                                <select class="bnrfrm-select" v-model="form.sortByRating" @change="search('rating')">
                                    <option value="">Rating</option>
                                    <option value="asc">Asc</option>
                                    <option value="desc">Desc</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- listing filter menu start  -->
    <div class="listingfiltermenu">
        <div class="container">
            <ul class="listingfiltermenu-list safari-collection">
                <li :class="{ active: activeFilter == 'all' }">
                    <button class="cmn-butn" @click.prevent="filterSafaris('all')">All safaris</button>
                </li>
                <li v-for="collection in collections" :key="collection.id"
                    :class="{ active: activeFilter == collection.id }">
                    <button class="cmn-butn" @click.prevent="filterSafaris(collection.id)">
                        {{ collection.name }}
                    </button>
                </li>

            </ul>
        </div>
    </div>
    <!-- listing filter menu end  -->

    <!-- listing section start -->
    <div class="safarilistingsec cmn-gap" v-if="safaris.data.length">
        <div class="container">
            <div class="listingsec-inr">
                <div class="row safari-list-row" v-if="safaris.data.length">
                    <div class="col-xl-3 col-lg-4 col-md-6 safari-list-col" v-for="safari in safaris.data"
                        :key="safari.id">
                        <SafariCard :safari="safari" />
                    </div>

                </div>

            </div>
            <div class="common-pagination">
                <Pagination :pagination="safaris" route-name="frontend.safari-listings" :extraParams="filteredParams" />
            </div>
        </div>
    </div>
    <div v-else>
        <h3 class="text-center mt-5 mb-5">No safaris found.</h3>
    </div>

    <!-- listing section end -->

    <!--filter popup-->
    <div class="filtr-backdrop" data-popup="popup1"></div>
    <div class="filtr-rgtsdepnl" data-popup="popup1">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Filter Your Journey</h2>
                <p>Narrow it down. Explore better.</p>
            </div>
            <div class="fltr-ppupfrm">
                <form @submit.prevent="handleSubmitFilter">
                    <!-- <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">1. Trip Duration</div>
                        <div class="ftrppup-checkbx-rw">
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" value="1-2" v-model="form.tripDurations">
                                    <span>1-2 day</span>
                                </label>

                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" value="3-5" v-model="form.tripDurations">
                                    <span>3–5 days</span>
                                </label>
                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" value="6-8" v-model="form.tripDurations">
                                    <span>6-8 days</span>
                                </label>
                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" value="9+" v-model="form.tripDurations">
                                    <span>9+ days</span>
                                </label>
                            </div>
                        </div>
                    </div> -->

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">1. Trip Duration</div>
                        <div class="fltrpop-rangewrpr trpduraton-slidr">
                            <Slider v-model="range" :min="1" :max="9" range class="w-full" />
                        </div>

                        <div class="slider-marks range-slidr-marks">
                            <span v-for="n in 9" :key="n">
                                {{ n === 9 ? '9+' : n }}
                            </span>
                        </div>

                        <!-- <div class="mt-2 text-sm text-gray-600">
                            Final value sent:
                            <strong>{{ form.tripDurations }}</strong>
                        </div> -->
                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">2. Travel Season</div>
                        <div class="ftrppup-checkbx-rw">
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" name="season" value="Green / Wet Season"
                                        v-model="form.seasons">
                                    <span>Green / Wet Season</span>
                                </label>
                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" name="season" value="Dry Season" v-model="form.seasons">
                                    <span>Dry Season</span>
                                </label>
                            </div>

                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" name="season" value="Great Migration Season"
                                        v-model="form.seasons">
                                    <span>Great Migration Season</span>
                                </label>
                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" name="season" value="Shoulder Season"
                                        v-model="form.seasons">
                                    <span>Shoulder Season</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">3. Region / Destination</div>
                        <Multiselect v-model="form.destinations" placeholder="Select" class="multislect-dropdwn"
                            mode="tags" :close-on-select="true" :searchable="true" :hide-selected="false"
                            :create-option="false" :options="[
                                { value: 'Countries', label: 'Countries (e.g. Kenya, Botswana)' },
                                { value: 'Regions', label: 'Regions (e.g. Southern Africa, East Africa)' },
                                { value: 'Parks/Reserves', label: 'Parks/Reserves (e.g. Maasai Mara, Kruger)' }
                            ]" />
                    </div> -->

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">3. Safari Type</div>
                        <div class="ftrppup-checkbx-rw">
                            <div class="ftrppup-checkbx-col checkbx-col6" v-for="type in safariTypes" :key="type.id">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" :value="type.id" name="safariTypeIds"
                                        v-model="form.safariTypeIds">
                                    <span>{{ type.title }}</span>
                                </label>
                            </div>
                        </div>

                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">4. Wildlife Highlights</div>
                        <Multiselect v-model="form.wildlifeHighlights" placeholder="Select Wildlife" mode="tags"
                            class="multislect-dropdwn" :close-on-select="true" :searchable="true" :create-option="false"
                            :options="wildlives" />
                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">5. Activities</div>
                        <div class="ftrppup-checkbx-rw actvity-checkbx">
                            <div class="ftrppup-checkbx-col checkbx-col6" v-for="activity in props?.activities"
                                :key="activity.id">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="checkbox" :value="activity.id" v-model="form.activities">
                                    <span><i class="activity-icon"><img
                                                :src="activity.full_icon_url ?? 'frontend_assets/images/gamedrives-icon.svg'"
                                                alt="Icon"></i> {{ activity.title }}</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">6. Ecosystems / Landscape</div>
                        <label class="enbromrnylbel">Environment</label>
                        <Multiselect v-model="form.landscapes" placeholder="Select" class="multislect-dropdwn"
                            mode="tags" :close-on-select="true" :searchable="true" :create-option="false" :options="[
                                { value: 'Rainforest', label: 'Rainforest' },
                                { value: 'Savannah', label: 'Savannah' },
                                { value: 'Desert', label: 'Desert' },
                                { value: 'Wetlands', label: 'Wetlands' },
                                { value: 'Mountains', label: 'Mountains' },
                                { value: 'Rivers / Delta', label: 'Rivers / Delta' },
                                { value: 'Coastal', label: 'Coastal' },
                                { value: 'Crater / Caldera', label: 'Crater / Caldera' },
                                { value: 'Open Plains', label: 'Open Plains' }
                            ]" />
                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">7. Activity Level</div>
                        <div class="ftrppup-checkbx-rw">
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="radio" name="name" value="Relaxed" v-model="form.activityLevel">
                                    <span>Relaxed</span>
                                </label>
                            </div>
                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="radio" name="name" value="Moderate" v-model="form.activityLevel">
                                    <span>Moderate</span>
                                </label>
                            </div>

                            <div class="ftrppup-checkbx-col">
                                <label class="ftrppup-chkbx-lbl">
                                    <input type="radio" name="name">
                                    <span>Active</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="ftrppup-chckbxwrpr">
                        <div class="ftrppup-checkbxhd">8. Traveller Group</div>
                        <div class="fltr-trv-qunttybx">
                            <div class="fltr-trv-quntcont">
                                <div class="fltr-trv-qunthd">Number of Adults</div>
                            </div>
                            <div class="fltr-qunt-new">
                                <InputNumber v-model="form.numberOfAdults" showButtons buttonLayout="horizontal"
                                    style="width: 20%" :min="0">
                                    <template #incrementbuttonicon>
                                        <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                    </template>
                                    <template #decrementbuttonicon>
                                        <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                    </template>
                                </InputNumber>
                            </div>

                        </div>
                        <div class="fltr-trv-qunttybx">
                            <div class="fltr-trv-quntcont">
                                <div class="fltr-trv-qunthd">Number of Children</div>
                            </div>
                            <div class="fltr-qunt-new">
                                <InputNumber v-model="form.numberOfChildren" showButtons buttonLayout="horizontal"
                                    style="width: 20%" :min="0">
                                    <template #incrementbuttonicon>
                                        <img :src="'frontend_assets/images/quntityplusicon.svg'">
                                    </template>
                                    <template #decrementbuttonicon>
                                        <img :src="'frontend_assets/images/quantity-minusicon.svg'">
                                    </template>
                                </InputNumber>
                            </div>

                        </div>
                    </div>

                    <div class="ftrppup-chckbxwrpr prcrangwrpr">
                        <div class="ftrppup-checkbxhd">9. Price</div>
                        <div class="fltrpop-rangewrpr">
                            <Slider v-model="form.priceRange" :min="0" :max="20000" :step="100" range />
                            <div class="fltrpop-range-inputbx">
                                <div class="fltrpop-range-input">
                                    <label>Minimum</label>
                                    <input id="input-rngeform" type="text" class="rangeiput js-input-from"
                                        v-model="form.priceRange[0]" readonly>
                                </div>
                                <span class="fltrpop-rnge-inline">-</span>
                                <div class="fltrpop-range-input">
                                    <label>Maximum</label>
                                    <input id="input-rngeto" type="text" class="rangeiput js-input-to"
                                        v-model="form.priceRange[1]" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ftrppup-chckbxwrpr" v-if="tags.length">
                        <div class="ftrppup-checkbxhd">10. Availability Tags</div>
                        <div class="ftrppup-checkbx-rw">
                            <div class="ftrppup-checkbx-col checkbx-col6" v-for="tag in tags" :key="tag.id">
                                <label class="ftrppup-chkbx-lbl" >
                                    <input type="radio" name="name" :value="tag.id" v-model="form.availabilityTagId">
                                    <span>{{ tag.name }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="ftrppup-butnbx">
                        <button type="button" class="cmn-butn wht-butn ppcncelbutn"
                            @click="closeFilterPopup">Cancel</button>
                        <button type="submit" class="cmn-butn">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--filter popup-->
</template>
<script setup>
import { onMounted, onUnmounted, reactive, ref, computed, watch } from 'vue'
import { homeJs } from "@/custom.js";
import { router } from "@inertiajs/vue3";
import Banner from '@/components/Frontend/Banner.vue';
import Multiselect from '@vueform/multiselect'
import Pagination from '@/components/customPaginate.vue'
import { pickBy, debounce } from "lodash";
import SafariCard from '@/components/Frontend/SafariCard.vue';
import DateRange from "@/components/DateRange.vue";


const params = new URLSearchParams(window.location.search);
const visible = ref(false);
const countryModalRef = ref(null);
// const openCountryModal = ref(false);

const props = defineProps({
    safaris: Object,
    safariTypes: Object,
    experiences: Object,
    activities: Object,
    countryGuides: Array,
    collections: Object,
    wildlives: Object,
    tags: Object
});

const activeFilter = ref(params.get('collection_id') || 'all')

let data = reactive({
    multiselect: [],
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

const form = reactive({
    collection_id: params.get('collection_id') || null,
    location: params.get('location') || '',
    tripDurations: getArrayParam('tripDurations') || [],
    dateRange: (params.get("dateRange[0]") && params.get("dateRange[1]")) ? [params.get("dateRange[0]"), params.get("dateRange[1]")] : params.get('dateRange'),
    seasons: getArrayParam('seasons') || [],
    destinations: getArrayParam('destinations') || [],
    safariTypeIds: getArrayParam('safariTypeIds') || [],
    wildlifeHighlights: getArrayParam('wildlifeHighlights') || [],
    activities: getArrayParam('activities') || [],
    landscapes: getArrayParam('landscapes') || [],
    activityLevel: params.get('activityLevel') || null,
    numberOfAdults: params.get('numberOfAdults') || 0,
    numberOfChildren: params.get('numberOfChildren') || 0,
    priceRange: getArrayParam('priceRange') && getArrayParam('priceRange').length ? getArrayParam('priceRange') : [],
    availabilityTagId: params.get('availabilityTagId') || '',
    sortByPrice: params.get('sortByPrice') || '',
    sortByRating: params.get('sortByRating') || '',
});

const minGap = 50;
let prevValue = [...form.priceRange];

watch(() => form.priceRange, (newVal) => {
    if (newVal[0] < 0) {
        form.priceRange[0] = 0;
    }
    if ((newVal[1] - newVal[0]) < minGap) {
        if (prevValue[0] !== newVal[0]) {
            form.priceRange[1] = newVal[0] + minGap;
        } else {
            form.priceRange[0] = newVal[1] - minGap;
        }
    }
    prevValue = [...form.priceRange];
}, { deep: true });

watch(() => [form.location, form.numberOfAdults, form.numberOfChildren, form.dateRange], debounce(() => {
    const data = pickBy(form);
    router.visit(route('frontend.safari-listings'), {
        method: 'get',
        data,
        preserveState: true,
        preserveScroll: true
    });
}, 500));

// const searchQuery = ref([]);
// const filteredCountries = computed(() => {
//     if (!searchQuery.value) {
//         return props.countryGuides;
//     }
//     return props.countryGuides.filter(item =>
//         item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
//     );
// });
// const applySelection = () => {
//     searchQuery.value = form.location.join(", ");
//     openCountryModal.value = false;

//     const data = pickBy(form);
//     router.visit(route('frontend.safari-listings'), {
//         method: 'get',
//         data,
//         preserveState: true,
//         preserveScroll: true
//     });
// }
// const openModal = () => {
//     openCountryModal.value = !openCountryModal.value;
//     if (openCountryModal.value) {
//         searchQuery.value = "";
//     }
// };


const filteredParams = computed(() => {
    const query = {}
    for (const [key, value] of Object.entries(form)) {
        if (
            value !== null &&
            value !== '' &&
            !(Array.isArray(value) && value.length === 0)
        ) {
            query[key] = value
        }
    }
    return query
});

const handleSubmitFilter = () => {
    router.get(route('frontend.safari-listings'), pickBy(form), {
        preserveScroll: true,
        onSuccess: () => {
            const popupPanel = document.querySelector('.filtr-rgtsdepnl[data-popup="popup1"]');
            const backdrop = document.querySelector('.filtr-backdrop[data-popup="popup1"]');
            if (popupPanel) popupPanel.classList.remove('popopen');
            if (backdrop) backdrop.classList.remove('open');
            document.body.classList.remove('no-scroll');
        }
    });
};

const closeFilterPopup = () => {
    const popupPanel = document.querySelector('.filtr-rgtsdepnl[data-popup="popup1"]');
    const backdrop = document.querySelector('.filtr-backdrop[data-popup="popup1"]');
    if (popupPanel) popupPanel.classList.remove('popopen');
    if (backdrop) backdrop.classList.remove('open');
    document.body.classList.remove('no-scroll');
    resetSearch();
}

const filterSafaris = (filter) => {
    activeFilter.value = filter;

    if (filter === 'all') {
        form.collection_id = null;
    } else {
        form.collection_id = filter;
    }

    router.get(route('frontend.safari-listings'), pickBy(form), {
        preserveScroll: true,
        preserveState: true,
    });
};

const search = (filter) => {
    if (filter === 'price') {
        form.sortByRating = null;
    } else if (filter === 'rating') {
        form.sortByPrice = null;
    }

    router.visit(route("frontend.safari-listings"), {
        method: "get",
        data: pickBy(form),
        preserveScroll: true,
    });
};

const resetSearch = () => {
    router.visit(route("frontend.safari-listings"), {
        method: "get",
        preserveScroll: true,
    });
}

// const handleClickOutside = (event) => {
//     if (
//         openCountryModal.value &&
//         countryModalRef.value &&
//         !countryModalRef.value.contains(event.target)
//     ) {
//         openCountryModal.value = false;
//     }
// };

onMounted(async () => {
    homeJs();
    const locationName = params.get('location[name]');
    const locationType = params.get('location[type]');

    if (locationName && locationType) {
        let match = null;
        props.countryGuides.forEach(country => {
            if (locationType === 'country' && country.name === locationName) {
                match = {
                    id: country.id,
                    name: country.name,
                    label: country.name,
                    type: "country"
                };
            }

            country.parks.forEach(park => {
                if (locationType === 'park' && park.name === locationName) {
                    match = {
                        id: park.id,
                        name: park.name,
                        label: `${park.name} (${country.name})`,
                        type: "park"
                    };
                }
            });
        });

        if (match) {
            selectedDestinations.value = match;
            form.location = {
                name: match.name,
                type: match.type
            };
        }
    }

    if (form.tripDurations.length) {
        const val = form.tripDurations[0]; // e.g. "1-3" or "9+"
        if (val === "9+") {
            range.value = [9, 9];
        } else {
            const [min, max] = val.split("-");
            range.value = [Number(min), max.includes("+") ? 9 : Number(max)];
        }
    }

});

onUnmounted(() => {
    // document.removeEventListener("click", handleClickOutside);
    homeJs();
});

function formatDate(dates) {
    if (!dates || dates.length < 2) return '';

    const [start, end] = dates;

    return `${singleFormat(start)} - ${singleFormat(end)}`;
}

function singleFormat(date) {
    if (!date) return '';
    date = new Date(date); // make sure it's a Date object

    const day = date.getDate();
    const month = date.toLocaleString('default', { month: 'short' }); // 'Oct', 'Nov'
    const year = date.getFullYear();

    let suffix = 'th';
    if (day % 10 === 1 && day !== 11) suffix = 'st';
    else if (day % 10 === 2 && day !== 12) suffix = 'nd';
    else if (day % 10 === 3 && day !== 13) suffix = 'rd';

    return `${day}${suffix} ${month} ${year}`;
}

const selectedDestinations = ref();
const filteredDestinations = ref([]);

const searchDestinations = (event) => {
    const query = event.query.toLowerCase()
    let results = []

    props?.countryGuides.forEach(country => {
        const countryName = country.name.toLowerCase()

        if (countryName.includes(query)) {
            results.push({
                id: country.id,
                name: country.name,
                label: country.name,
                type: "country"
            })

            country.parks.forEach(park => {
                results.push({
                    id: park.id,
                    name: park.name,
                    label: `${park.name} (${country.name})`,
                    type: "park"
                })
            })
        } else {
            country.parks.forEach(park => {
                if (park.name.toLowerCase().includes(query)) {
                    results.push({
                        id: park.id,
                        name: park.name,
                        label: `${park.name} (${country.name})`,
                        type: "park"
                    })
                }
            })
        }
    })

    filteredDestinations.value = results
}

watch(selectedDestinations, (newValue) => {
    if (!newValue || (Array.isArray(newValue) && newValue.length === 0)) {
        router.visit(route("frontend.safari-listings"), {
            method: "get",
            preserveScroll: true,
            preserveState: true
        });
    }
});

const storeSelection = (event) => {
    const item = event.value
    form.location = {
        name: item.name,
        type: item.type
    }
}

const range = ref([]);

// convert slider value into ["1-2"] style
watch(range, (newVal) => {
    if (newVal.length === 2) {
        const [min, max] = newVal;
        if (min === 9 && max === 9) {
            form.tripDurations = ["9+"]; // special case
        } else {
            form.tripDurations = [`${min}-${max === 9 ? "9+" : max}`];
        }
    } else {
        form.tripDurations = [];
    }
});
</script>
<style src="@vueform/multiselect/themes/default.css"></style>

<style scoped>
.slider-marks {
    display: flex;
    justify-content: space-between;
    padding: 0 4px;
    margin-top: 6px;
    font-size: 12px;
    color: #666;
}
</style>
