<template>
    <div class="dashboard_left_panel" v-if="$page?.props?.auth?.user?.role == 'TRAVELLER'">
        <div class="nav_panel">
            <div class="panel_logo_box">
                <a href="javascript:void(0)" class="ham_bttn_close"><img :src="'/frontend_assets/images/close.svg'"
                        alt=""></a>
                <a :href="route('frontend.index')" class="logo_lnk"><img :src="'/frontend_assets/Final Tuskari Logo White.svg'"
                        alt=""></a>
            </div>
            <div class="back_to_main_link">
                <a :href="route('frontend.index')" class="back_link"><span><i class="fas fa-arrow-left"></i></span>Back
                    to website</a>
            </div>
            <div class="menu_nav_outr">
                <ul class="menu_nav">
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/dashboard') }">
                        <a :href="route('frontend.dashboard')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon1.svg'"
                                    alt="Dashboard"></span>Dashboard</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/my-trips') }">
                        <a :href="route('frontend.my-trips')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon2.svg'" alt="MyTrip"></span>My
                            Trips</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/saved-safaris') }">
                        <a :href="route('frontend.saved-safaris')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon3.svg'" alt="Saved Safari"></span>Saved
                            Safaris</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/review-list') }">
                        <a :href="route('frontend.all-reviews')"><span>
                            <Icon icon="ic:round-star" width="20" height="20" />
                                </span>All Reviews</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/messages') }">
                        <a :href="route('frontend.messages')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon4.svg'" alt="Message"></span>Messages</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/payments') }">
                        <a :href="route('frontend.payments')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon5.svg'" alt="Payments"></span>Payments</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/travellerProfile') }">
                        <a :href="route('frontend.traveller-profile')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon6.svg'" alt="Profile"></span>Profile &
                            Preferences</a>
                    </li>
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/help-support') }">
                        <a :href="route('frontend.help-and-support')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon7.svg'" alt="Help"></span>Help &
                            Support</a>
                    </li>
                    <li>
                        <a href="javascript:void(0)" class="sf-filterbutn" data-target="logout">
                            <span>
                                <img :src="'/frontend_assets/images/dshbrd_icon8.svg'" alt="Log Out">
                            </span>
                            Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
    <div class="dashboard_left_panel" v-else>
        <div class="nav_panel">
            <div class="panel_logo_box">
                <a href="javascript:void(0)" class="ham_bttn_close"><img :src="'/frontend_assets/images/close.svg'"
                        alt="Close"></a>
                <a :href="route('frontend.index')" class="logo_lnk"><img :src="'/frontend_assets/Final Tuskari Logo White.svg'"
                        alt="Logo"></a>
            </div>
            <div class="back_to_main_link">
                <a :href="route('frontend.index')" class="back_link"><span><i class="fas fa-arrow-left"></i></span>Back
                    to website</a>
            </div>
            <div class="menu_nav_outr">
                <ul class="menu_nav">
                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-dashboard') }">
                        <a :href="route('frontend.operator-dashboard')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon1.svg'"
                                    alt="Dashboard"></span>Dashboard</a>
                    </li>
                    <li v-if="$page.props.auth.user.is_approved == 1"
                        :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-earnings') }">
                        <a :href="route('frontend.operator-earnings')"><span><img
                                    :src="'/frontend_assets/images/earningicon.svg'" alt="Payments"></span>Earnings</a>
                    </li>
                    <li v-if="$page.props.auth.user.is_approved == 1"
                        :class="{ 'active': $page.component.startsWith('Frontend/Auth/safari-manage-listing') || $page.component.startsWith('Frontend/Auth/create-new-listing') || $page.component.startsWith('Frontend/Auth/operator-safari-details') }">
                        <a :href="route('frontend.safari-manage-listing')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon2.svg'" alt="MyTrip"></span>My Safaris</a>
                    </li>

                     <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-review-details') }">
                        <a :href="route('frontend.operator-all-reviews')"><span>
                            <Icon icon="ic:round-star" width="20" height="20" />
                                </span>All Reviews</a>
                    </li>

                    <li v-if="$page.props.auth.user.is_approved == 1"
                        :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-messages') }">
                        <a :href="route('frontend.messages')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon4.svg'" alt="Message"></span>Messages</a>
                    </li>

                    <li v-if="$page.props.auth.user.is_approved == 1"
                        :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-bookings') }">
                        <a :href="route('frontend.operator-bookings')"><span><img
                                    :src="'/frontend_assets/images/bookiconsvg.svg'"
                                    alt="Saved Safari"></span>Bookings</a>
                    </li>


                    <li :class="{ 'active': $page.component.startsWith('Frontend/Auth/operator-my-profile') }">
                        <a :href="route('frontend.operator-my-profile')"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon6.svg'" alt="Profile"></span>Profile &
                            Settings</a>
                    </li>
                    <li>
                        <a href="#url" class="sf-filterbutn" data-target="logout"><span><img
                                    :src="'/frontend_assets/images/dshbrd_icon8.svg'" alt="Log Out"></span>Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="menu_ovrly"></div>
    <div class="filtr-backdrop" data-popup="logout"></div>
    <div class="filtr-rgtsdepnl" data-popup="logout">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="lgout_popup_wrap">
                <div class="lgout_inner_box">
                    <div class="lg_out_icon">
                        <img :src="'/frontend_assets/images/logout-pop.svg'" alt="logout icon">
                    </div>
                    <h2 class="filtrpnlhdng">Logout</h2>
                    <p>Do you want to logout from your dashboard?</p>
                    <a href="javascript:void(0)" @click="logout" class="cmn-butn">Logout</a>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>

import { router } from '@inertiajs/vue3';

const logout = () => {
    document.body.classList.remove('no-scroll');
    document.querySelector('body').classList.remove('menu_open');
    document.querySelector('html').classList.remove('menu_open');
    document.querySelector('.nav_panel').classList.remove('menu_show');
    router.post(route('frontend.logout'));
};


</script>

<style scoped>
.back_to_main_link {
    padding: 15px 20px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.back_link {
    color: #fff;
    font-size: 14px;
    font-weight: 500;
    opacity: 0.9;
    transition: opacity 0.3s ease;
    display: flex;
    align-items: center;
}

.back_link span {
    margin-right: 10px;
    display: flex;
    align-items: center;
}

.back_link:hover {
    opacity: 1;
}
</style>