<template lang="">
    <div>
        <!-- begin:: Aside -->
        <button class="kt-aside-close " v-if="mobileMenuHideShow" @click="mobileMenuHideShow = !mobileMenuHideShow, closeMobileSidebar()" id="kt_aside_close_btn"><i class="la la-close"></i></button>
        <div class="kt-aside  kt-aside--fixed  kt-grid__item kt-grid kt-grid--desktop kt-grid--hor-desktop" :class="{'kt-aside--on': mobileMenuHideShow}" id="kt_aside">
            <!-- begin:: Aside -->
            <div class="kt-aside__brand kt-grid__item " id="kt_aside_brand">
                <div class="kt-aside__brand-logo">
                    <Link :href="route('admin.dashboard')" class="logo_text_custom">
                    <img :src="'/frontend_assets/Final Tuskari Logo White.svg'" alt="">
                    <!-- <span>{{ $page.props.appName }}</span> -->
                    </Link>
                </div>
                <div class="kt-aside__brand-tools">
                    <button class="kt-aside__brand-aside-toggler" id="kt_aside_toggler" @click="toggleSideMenu">
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                    <path
                                        d="M5.29288961,6.70710318 C4.90236532,6.31657888 4.90236532,5.68341391 5.29288961,5.29288961 C5.68341391,4.90236532 6.31657888,4.90236532 6.70710318,5.29288961 L12.7071032,11.2928896 C13.0856821,11.6714686 13.0989277,12.281055 12.7371505,12.675721 L7.23715054,18.675721 C6.86395813,19.08284 6.23139076,19.1103429 5.82427177,18.7371505 C5.41715278,18.3639581 5.38964985,17.7313908 5.76284226,17.3242718 L10.6158586,12.0300721 L5.29288961,6.70710318 Z"
                                        id="Path-94" fill="#000000" fill-rule="nonzero"
                                        transform="translate(8.999997, 11.999999) scale(-1, 1) translate(-8.999997, -11.999999) " />
                                    <path
                                        d="M10.7071009,15.7071068 C10.3165766,16.0976311 9.68341162,16.0976311 9.29288733,15.7071068 C8.90236304,15.3165825 8.90236304,14.6834175 9.29288733,14.2928932 L15.2928873,8.29289322 C15.6714663,7.91431428 16.2810527,7.90106866 16.6757187,8.26284586 L22.6757187,13.7628459 C23.0828377,14.1360383 23.1103407,14.7686056 22.7371482,15.1757246 C22.3639558,15.5828436 21.7313885,15.6103465 21.3242695,15.2371541 L16.0300699,10.3841378 L10.7071009,15.7071068 Z"
                                        id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                        transform="translate(15.999997, 11.999999) scale(-1, 1) rotate(-270.000000) translate(-15.999997, -11.999999) " />
                                </g>
                            </svg>
                        </span>
                        <span>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                                height="24px" viewBox="0 0 24 24" version="1.1" class="kt-svg-icon">
                                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <polygon id="Shape" points="0 0 24 0 24 24 0 24" />
                                    <path
                                        d="M12.2928955,6.70710318 C11.9023712,6.31657888 11.9023712,5.68341391 12.2928955,5.29288961 C12.6834198,4.90236532 13.3165848,4.90236532 13.7071091,5.29288961 L19.7071091,11.2928896 C20.085688,11.6714686 20.0989336,12.281055 19.7371564,12.675721 L14.2371564,18.675721 C13.863964,19.08284 13.2313966,19.1103429 12.8242777,18.7371505 C12.4171587,18.3639581 12.3896557,17.7313908 12.7628481,17.3242718 L17.6158645,12.0300721 L12.2928955,6.70710318 Z"
                                        id="Path-94" fill="#000000" fill-rule="nonzero" />
                                    <path
                                        d="M3.70710678,15.7071068 C3.31658249,16.0976311 2.68341751,16.0976311 2.29289322,15.7071068 C1.90236893,15.3165825 1.90236893,14.6834175 2.29289322,14.2928932 L8.29289322,8.29289322 C8.67147216,7.91431428 9.28105859,7.90106866 9.67572463,8.26284586 L15.6757246,13.7628459 C16.0828436,14.1360383 16.1103465,14.7686056 15.7371541,15.1757246 C15.3639617,15.5828436 14.7313944,15.6103465 14.3242754,15.2371541 L9.03007575,10.3841378 L3.70710678,15.7071068 Z"
                                        id="Path-94" fill="#000000" fill-rule="nonzero" opacity="0.3"
                                        transform="translate(9.000003, 11.999999) rotate(-270.000000) translate(-9.000003, -11.999999) " />
                                </g>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

            <!-- end:: Aside -->

            <!-- begin:: Aside Menu -->
            <perfect-scrollbar>
                <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
                    <div id="kt_aside_menu" class="kt-aside-menu" data-ktmenu-vertical="1" data-ktmenu-scroll="1"
                        data-ktmenu-dropdown-timeout="500">

                        <ul class="kt-menu__nav ">
                            <li class="kt-menu__item  " :class="{ 'kt-menu__item--active': $page.url === '/admin/dashboard' }"
                                aria-haspopup="true">
                                <Link :href="route('admin.dashboard')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="bx:home" color="white" width="22" height="22" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Dashboard</span>
                                </Link>
                            </li>

                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">User Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">

                                </i>
                            </li>

                            <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/user') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.users')" :preserve-scroll="true" class="kt-menu__link">
                                <Icon icon="heroicons:users-solid" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />

                                <span class="kt-menu__link-text">Customers</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/vendor') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.vendors')" :preserve-scroll="true" class="kt-menu__link">
                                <Icon icon="material-symbols:storefront" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />

                                <span class="kt-menu__link-text">Vendors/Providers</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item " :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/withdrawal_requests') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.withdrawal-requests.index')" :preserve-scroll="true" class="kt-menu__link">
                                <Icon icon="material-symbols:payments" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />

                                <span class="kt-menu__link-text">Withdrawal Requests</span>
                                </Link>
                            </li>
                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Master Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">
                                </i>
                            </li>


                            <!-- <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/operators') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.operators')" class="kt-menu__link ">
                                <Icon icon="material-symbols-light:featured-play-list-sharp" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Safari Operator</span>
                                </Link>
                            </li> -->

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/accomodation') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.accomodations.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:hotel" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Accomodations</span>
                                </Link>
                            </li>


                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/key_experience') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.key-experience.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:star" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Key Experience</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/activity') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.activity.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:directions-run" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Activity</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Safari_type') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.safari-type.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:visibility" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Safari Type</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/wild_life') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.wild-lives')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:pets" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Wild Life</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/region') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.region.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:location-on" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Regions</span>
                                </Link>
                            </li>


                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Safari/Listing Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">

                                </i>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/safari') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.safari.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:explore" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Safari/Listing</span>
                                </Link>
                            </li>


                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/national_parks') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.national-park-reserves.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:forest" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">National Parks & Private Reserves</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/country_guide') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.country-guides')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:map" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Country Guides</span>
                                </Link>
                            </li>

                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Booking Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">

                                </i>
                            </li>
                              <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/booking') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.booking.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:calendar-month" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Booking/Transaction</span>
                                </Link>
                            </li>

                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Blog Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">

                                </i>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/category') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.category.index')" :preserve-scroll="true" class="kt-menu__link ">
                                <Icon icon="material-symbols:label" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Blog Category</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/blog') }"
                                aria-haspopup="true">
                                <Link href="/admin/blogs" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:article" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Blogs</span>
                                </Link>
                            </li>

                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Content Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">
                                </i>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/cms') }"
                                aria-haspopup="true">
                                <Link href="/admin/cms" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:content-copy" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">CMS Pages</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Pages') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.create-edit-pages')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:edit" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Pages</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/banner') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.banner_list')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:image" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Banner</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/uploads') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.uploads.index')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:upload" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Uploads</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/bottom_banner') }"
                                aria-haspopup="true">
                                <Link :href="route('admin.bottom-banner-list')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:layers" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Bottom Banner</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/Testimonial') }"
                                aria-haspopup="true">
                                <Link href="/admin/testimonial" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:chat" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Testimonial</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/OperatorReview') }"
                                aria-haspopup="true">
                                <Link href="/admin/operator-review" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:rate-review" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Operator Reviews</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/faq') }" aria-haspopup="true">
                                <Link :href="route('admin.faq.index')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:help" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">FAQ</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/page_meta') }" aria-haspopup="true">
                                <Link :href="route('admin.page-meta.index')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:search" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Page Meta</span>
                                </Link>
                            </li>
                            <li class="kt-menu__section ">
                                <h4 class="kt-menu__section-text">Customer Support Management</h4>
                                <i class="kt-menu__section-icon flaticon-more-v2">
                                </i>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/help_support') }" aria-haspopup="true">
                                <Link :href="route('admin.help-support.index')" class="kt-menu__link" preserve-scroll>
                                <Icon icon="material-symbols:support" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Help And Support</span>
                                </Link>
                            </li>

                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/contact_us') }" aria-haspopup="true">
                                <Link :href="route('admin.contact-us')" class="kt-menu__link" :preserve-scroll="true">
                                <Icon icon="material-symbols:mail" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                <span class="kt-menu__link-text">Contact Us</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/contact_info') }" aria-haspopup="true">
                                <Link :href="route('admin.update.contact-info')" class="kt-menu__link" :preserve-scroll="true">
                                    <Icon icon="material-symbols:info" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                    <span class="kt-menu__link-text">Contact Information</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/something_didnt_work') }" aria-haspopup="true">
                                <Link :href="route('admin.something-did-not-work.index')" class="kt-menu__link" :preserve-scroll="true">
                                    <Icon icon="material-symbols:warning" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                    <span class="kt-menu__link-text">Something didn't work Report</span>
                                </Link>
                            </li>
                             <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/report_issue') }" aria-haspopup="true">
                                <Link :href="route('admin.report-issue.index')" class="kt-menu__link" :preserve-scroll="true">
                                    <Icon icon="material-symbols:flag" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                    <span class="kt-menu__link-text">Report A Problem</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/WebsiteRating') }" aria-haspopup="true">
                                <Link :href="route('admin.website-rating.list')" class="kt-menu__link" :preserve-scroll="true">
                                    <Icon icon="material-symbols:star-rate" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                    <span class="kt-menu__link-text">Website Ratings</span>
                                </Link>
                            </li>
                            <li class="kt-menu__item" :class="{ 'kt-menu__item--active': $page.component.startsWith('Admin/settings') }" aria-haspopup="true">
                                <Link :href="route('admin.settings.edit')" class="kt-menu__link" :preserve-scroll="true">
                                    <Icon icon="material-symbols:settings" color="white" width="20" height="20" class="kt-menu__link-icon mr-3" />
                                    <span class="kt-menu__link-text">Settings</span>
                                </Link>
                            </li>
                        </ul>
                    </div>
                </div>
            </perfect-scrollbar>
            <!-- end:: Aside Menu -->
        </div>

        <!-- end:: Aside -->
    </div>
</template>
<script setup>

import { onMounted, ref } from "vue";

const hideShow = ref(false);
const mobileMenuHideShow = ref(false);

onMounted(() => {
    document.querySelectorAll('.kt-menu__link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth <= 1024) {
                mobileMenuHideShow.value = false;
                closeMobileSidebar();
            }
        });
    });
});
const closeMobileSidebar = () => {
    emit.emit('closeMobileSideBar', false);
};


const toggleSideMenu = () => {
    hideShow.value = !hideShow.value;
    emit.emit('toggleSideMenu', hideShow.value);
}

emit.on('toggleMobileMenu', function (arg1) {
    mobileMenuHideShow.value = arg1;
});

</script>
