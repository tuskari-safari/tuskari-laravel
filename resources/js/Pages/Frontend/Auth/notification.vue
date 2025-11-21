<template>
    <div class="panel_rght_cntnt_area each_equal_cntnr">
        <div class="ntfic_outer_wrapper">
            <div class="ntfc_list_outer">
                <ul v-if="props?.notifications?.data?.length > 0" class="list-unstyled m-0 p-0">
                    <li
                        v-for="notification in props.notifications.data"
                        :key="notification.id"
                        class="d-flex justify-content-between align-items-start gap-3 py-3 px-3 notification-item"
                    >
                        <div class="cnt_box d-flex">
                            <div class="prf_img">
                                <img
                                    :src="notification?.sender?.profile_photo_url || '/default-user.png'"
                                    alt="user profile"
                                    class="notification-avatar"
                                >
                            </div>
                            <div class="cnt_box_cntnt">
                                <div class="fw-bold mb-1">
                                    {{ notification.data.title }}
                                </div>
                                <div class="text-muted small">
                                    {{ notification.data.body }}
                                </div>
                                <div class="text-muted small mt-1">
                                    {{ ListHelper.dateFormat(notification.created_at, "MMM DD, YYYY") }}
                                </div>
                            </div>
                        </div>
                        <div class="act_btn">
                            <a href="javascript:void(0);" @click.prevent="handleClickDeleteNotification(notification.id)">
                                <img :src="'/frontend_assets/images/dlteicon.svg'" alt="delete" class="img-fluid" style="width: 18px;">
                            </a>
                        </div>
                    </li>
                </ul>
                <div v-else class="text-center py-4 text-muted">
                    <h6>No notifications found.</h6>
                </div>
            </div>
        </div>

        <div class="common-pagination mt-3">
            <Pagination :pagination="notifications" route-name="frontend.notifications" />
        </div>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted } from 'vue'
import { router } from "@inertiajs/vue3";
import { homeJs } from "@/custom.js";
import Pagination from '@/components/customPaginate.vue'
import ListHelper from "@/helpers/ListHelper";

const props = defineProps({
    notifications: Object
});

const handleClickDeleteNotification = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("frontend.notification.delete", id));
};

onMounted(() => {
    homeJs();
    emit.on("deleteConfirm", deleteConfirm);
});

onUnmounted(() => {
    emit.off("deleteConfirm");
});
</script>

<style scoped>
.notification-item {
    transition: background-color 0.2s ease;
    border-bottom: none; /* Removed borders */
}


</style>
