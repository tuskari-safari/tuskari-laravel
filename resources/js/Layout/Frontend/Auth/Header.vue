<template>
    <div class="rght_panel_header">
        <div class="panel_hdr_lft">
            <a href="javascript:void(0)" class="ham_bttn">
                <img :src="'/frontend_assets/images/ham_icon.svg'" alt="ham icon">
            </a>

            <div class="user-info-head">
                <h1><span class="user_grtngs">{{ title }}</span></h1>
                <p class="user-info-subtitle">{{ subtitle }}</p>
            </div>
        </div>
        <div class="panel_hdr_rght">
            <div class="hder_srch_box">
            </div>
            <div class="ntfctn_holder">
                <a :href="route('frontend.notifications')" class="ntfctn_bttn">
                    <img :src="'/frontend_assets/images/noti_icon.svg'" alt="">
                    <span class="ntfctn_dot" v-if="unread_count > 0"></span>
                </a>

            </div>
            <div class="user_dropbox dropdown" v-if="screenWidth > 578">
                <a href="javascript:void(0)" class="usr_bttn dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="user_drp_prfle_box">
                        <div class="usrDrp_prfle_imgbox">
                            <img :src="$page?.props?.auth?.user?.profile_photo_url" alt=""
                                v-if="$page?.props?.auth?.user?.profile_photo_url">
                            <span v-else>{{ $page?.props?.auth?.user.full_name.substr(0, 1) }}</span>
                        </div>
                        <span>{{ $page?.props?.auth?.user?.first_name }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item"
                            :href="$page.props.auth.user.role == 'TRAVELLER' ? route('frontend.traveller-profile') : route('frontend.operator-my-profile')">My
                            Profile</a></li>
                </ul>
            </div>
        </div>
        <!-- <h1><span class="user_grtngs mb-usr-title">{{ title }}</span></h1> -->
    </div>
</template>
<script setup>
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import { onMounted } from 'vue';

const unread_count = ref(0);

const page = usePage();
const title = ref('');
const subtitle = ref('');
const screenWidth = ref(window.innerWidth);

onMounted(() => {
    getReadNotifications();
    Echo.private('send-notification.' + page.props.auth.user.id).listen('SendNotificationEvent', (e) => {
        getReadNotifications();
    });
    emit.on('pageName', function (pageTitle) {
        title.value = pageTitle;

    });
    emit.on('pageSubTitle', function (pageSubtitle) {
        subtitle.value = pageSubtitle;
    });
});

const getReadNotifications = () => {
    axios.get(route('frontend.read-count-notification')).then(res => {
        unread_count.value = res.data.unread_count;
    });
}
</script>
