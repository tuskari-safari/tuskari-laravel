<template>
    <div class="common-pagination" v-if="pagination?.last_page > 1">
        <ul>
            <li>
                <a href="javascript:void(0)" class="prev-arrow-btn"
                    @click.prevent="changePage(pagination.current_page - 1)"
                    :class="{ disabled: pagination.current_page === 1 }">
                    <span class="arrow">
                        <img :src="prevIcon" alt="pagination left" />
                    </span>
                </a>
            </li>
            <li v-for="page in totalPages" :key="page"
                :class="{ 'pagi-count': true, active: pagination.current_page === page }">
                <a href="javascript:void(0)" @click.prevent="changePage(page)">
                    {{ page < 10 ? '0' + page : page }} </a>
            </li>
            <li>
                <a href="javascript:void(0)" class="next-arrow-btn"
                    @click.prevent="changePage(pagination.current_page + 1)"
                    :class="{ disabled: pagination.current_page === pagination.last_page }">
                    <span class="arrow">
                        <img :src="nextIcon" alt="pagination right" />
                    </span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    pagination: Object,
    routeName: String,
    extraParams: Object,
    routeParams: Object,
    prevIcon: {
        type: String,
        default: '/frontend_assets/images/pagi-left-arrow.png'
    },
    nextIcon: {
        type: String,
        default: '/frontend_assets/images/pagi-right-arrow.png'
    }
});

const totalPages = computed(() => {
    const pages = []
    for (let i = 1; i <= props.pagination.last_page; i++) {
        pages.push(i)
    }
    return pages
})

const changePage = (page) => {
    if (page < 1 || page > props.pagination.last_page) return

    const params = {
        ...props.extraParams,
        page: page
    }
    router.get(props.routeParams ? route(props.routeName, props.routeParams) : route(props.routeName), {
        ...props.extraParams,
        page
    }, {
        preserveScroll: true,
        preserveState: true,
    });
}
</script>

<style scoped>
.disabled {
    pointer-events: none;
    opacity: 0.5;
}
</style>
