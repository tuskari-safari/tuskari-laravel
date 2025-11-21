<template>
    <a :href="$page.props.isLogin &&
        $page.props.auth?.user?.role === 'SAFARI_OPERATOR' &&
        safari.user_id === $page.props.auth?.user?.id
        ? route('frontend.operator-safari-details', { slug: safari.slug })
        : isSavedSafari ? route('frontend.traveller-safari-details', { slug: safari.slug })
            : route('frontend.safari-details', { slug: safari.slug })">
        <div class="ft-exp-item">
            <div class="ft-exp-itm-tppnl">
                <figure class="ft-exp-itmimgbx">
                    <img :src="safari?.full_thumbnail_url ?? '/frontend_assets/images/safari-list-1.png'"
                        alt="Feature Image">
                </figure>
                <a v-if="$page.props?.isLogin && $page?.props?.auth?.user?.role == 'TRAVELLER'"
                    href="javascript:void(0)" @click="toggleSafariFavourite(safari.id)" class="ft-whstlstbutn"
                    tabindex="0">
                    <img :src="safari.is_favourite ? '/frontend_assets/images/favourite-on.svg' : '/frontend_assets/images/whishlisticon.svg'"
                        alt="Whishlist">
                </a>
                <a v-else-if="!$page.props?.isLogin" :href="route('frontend.login')" class="ft-whstlstbutn"
                    tabindex="0">
                    <img :src="'/frontend_assets/images/whishlisticon.svg'" alt="Whishlist">
                </a>
                <div class="ft-ratng">
                    <i class="ftrate-img"><img :src="'/frontend_assets/images/ratingwhtstr.svg'" alt="Rate Star"></i>
                    <span>{{ Number(safari?.safari_reviews_avg_rating).toFixed(1) }}</span>
                </div>
                <div class="ft-activ-tag"
                    :class="safari.is_approved == 1 ? 'kt-badge--success' : (safari.is_approved == 2 ? 'kt-badge--warning' : 'kt-badge--info')"
                    v-if="
                        $page.props.isLogin &&
                        $page.props.auth?.user?.role === 'SAFARI_OPERATOR' &&
                        safari.user_id === $page.props.auth?.user?.id &&
                        $page.url.startsWith('/safari-manage-listing')
                    ">
                    <span v-if="safari.is_approved === 1">Active</span>
                    <span v-else-if="safari.is_approved === 2">Rejected</span>
                    <span v-else>Pending</span>
                </div>
                <div class="ft-exptag-wrpr">
                    <div class="ft-exp-taglistbx">
                        <ul>
                            <li v-if="safari?.create_safari_type?.length">
                                {{ safari?.create_safari_type?.[0]?.type?.title }}
                            </li>
                            <li v-if="safari?.country?.name">{{ safari?.country?.name }}</li>
                        </ul>
                    </div>
                    <a :href="$page.props.isLogin &&
                        $page.props.auth?.user?.role === 'SAFARI_OPERATOR' &&
                        safari.user_id === $page.props.auth?.user?.id
                        ? route('frontend.operator-safari-details', { slug: safari.slug })
                        : isSavedSafari ? route('frontend.traveller-safari-details', { slug: safari.slug })
                            : route('frontend.safari-details', { slug: safari.slug })" class="ftview-butn"
                        tabindex="0"><img :src="'/frontend_assets/images/ftviewimg.svg'" alt="View Image"></a>
                </div>
            </div>
            <div class="ft-exp-itm-btmpnl">
                <div class="ft-exp-prs-bx">
                    <span class="ft-exp-prs" style="margin-right: 5px">
                        {{ formatLocalPrice(totalPriceWithFees)}} pp
                    </span>
                    <span class="ft-exp-daytime">{{ safari?.day_duration ?? 'NA' }} days {{ safari?.night_duration }}
                        nights</span>
                </div>
                <div class="h3-title ft-exp-itmhd">
                    <a :href="$page.props.isLogin &&
                        $page.props.auth?.user?.role === 'SAFARI_OPERATOR' &&
                        safari.user_id === $page.props.auth?.user?.id
                        ? route('frontend.operator-safari-details', { slug: safari.slug })
                        : isSavedSafari ? route('frontend.traveller-safari-details', { slug: safari.slug })
                            : route('frontend.safari-details', { slug: safari.slug })" tabindex="0">
                        {{ safari?.title ?? 'NA' }}</a>
                </div>
                <p>
                    {{ safari.description ? safari.description.trimStart().slice(0, 100) + '...' : 'NA' }}
                </p>
                <a :href="$page.props.isLogin &&
                    $page.props.auth?.user?.role === 'SAFARI_OPERATOR' &&
                    safari.user_id === $page.props.auth?.user?.id
                    ? route('frontend.operator-safari-details', { slug: safari.slug })
                    : isSavedSafari ? route('frontend.traveller-safari-details', { slug: safari.slug })
                        : route('frontend.safari-details', { slug: safari.slug })" class="ft-exp-itmbutn"
                    tabindex="0">View
                    trip details</a>
            </div>
        </div>
    </a>

</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { onMounted } from 'vue';
import { useCurrency } from '@/composables/useCurrency';
const { formatLocalPrice, initializeCurrency } = useCurrency();

const props = defineProps({
    safari: Object,
    isSavedSafari: Boolean
});
const form = useForm({
    safariId: '',
});

const setting = ref({});
const totalPriceWithFees = computed(() => {
    const basePrice = Number(props.safari?.total_price) || 0;
    const platformFeePercent = Number(setting.value?.platform_fee) || 0;
    const stripeFeePercent = Number(setting.value?.stripe_processing_fee) || 0;
    const platformFee = (basePrice * platformFeePercent) / 100;
    const stripeFee = (basePrice * stripeFeePercent) / 100;
    return basePrice + platformFee + stripeFee;
});

const getSettings = () => {
    axios.get(route('frontend.get-settings')).then(res => {
        setting.value = res.data;
    })
}
onMounted( async () => {
    getSettings();
    await initializeCurrency();
});

const toggleSafariFavourite = (id) => {
    form.safariId = id;
    form.post(route('frontend.toggle-favourite'), { preserveScroll: true });
};
</script>
