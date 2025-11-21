<template>
    <div class="panel_rght_cntnt_area serv-profilesec">
        <div class="prfpref-row">
            <div class="prfpref-lftcol">
                <div class="prfpref-lftcard">
                    <div class="prfpref-infotoppnl">
                        <figure class="prfpref-img">
                            <img :src="profile?.profile_photo_url" alt="image">
                        </figure>
                        <h2 class="prfpref-name">{{ profile?.full_name }}</h2>
                        <p class="prfpref-email">{{ profile?.email }}</p>

                    </div>
                    <div class="prfpref-infocontnt">
                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Full name
                            </div>
                            <div class="prfpref-info">
                                {{ profile?.full_name }}
                            </div>
                        </div>

                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Phone
                            </div>
                            <div class="prfpref-info">
                                +{{ profile?.country_code }} {{ profile?.phone }}
                            </div>
                        </div>
                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Email
                            </div>
                            <div class="prfpref-info">
                                {{ profile?.email }}
                            </div>
                        </div>
                        <div class="prfpref-infocontnt-wrp">
                            <div class="prfpref-infohd">
                                Country of operation
                            </div>

                            <div class="prfpref-info">
                                {{ profile?.country?.name }}
                            </div>
                        </div>
                    </div>
                    <div class="prfpref-butnbx">
                        <button type="button" class="cmn-butn sf-filterbutn" data-target="edit">Edit
                            profile</button>
                        <button type="button" class="cmn-butn wht-butn ppcncelbutn chngpswrdbutn sf-filterbutn"
                            data-target="updtpassword">Change password</button>
                    </div>
                </div>
            </div>
            <div class="prfpref-rgtcol">
                <div class="prfpref-card mb-2">
                    <div class="prfpref-topcard mb-0">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt">
                                <h2>Business details</h2>
                                <p>{{ profile?.about_operation ?? 'NA' }}</p>
                            </div>
                        </div>

                        <ul class="name-ul">
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/building.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Business name</h4>
                                    <p>{{ profile?.business_name ?? 'NA' }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/bus-2.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Business type</h4>
                                    <p>{{ profile?.business_type ?? 'NA' }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/bus-2.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Years of Experience</h4>
                                    <p>{{ profile?.business_years ?? '0' }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/bus-3.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Main Destination</h4>
                                    <p>{{ profile?.main_destination ? profile?.main_destination.join(', ') : 'NA' }}
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/bus-4.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Website link</h4>
                                    <p>{{ profile?.website_link ?? 'NA' }}</p>
                                </div>
                            </li>
                            <li>
                                <div class="img-name">
                                    <img :src="'/frontend_assets/images/bus-5.svg'" alt="">
                                </div>
                                <div class="rgt-hdng">
                                    <h4>Instagram Link</h4>
                                    <p>{{ profile?.instagram_link ?? 'NA' }}</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="prfpref-card mb-2">
                    <div class="prfpref-topcard mb-0">
                        <div class="trips_hder">
                            <div class="hdr_lft_prt extra">
                                <h2>Document</h2>
                                <button type="button" class="cmn-butn sf-filterbutn" data-target="uploadDoc">Edit
                                </button>
                            </div>
                        </div>
                        <div class="row prfpref-cardrow">
                            <div class="col-md-4 prfpref-card-col">
                                <div class="prfpref-cardbx gap">
                                    <div class="wrap-pin">
                                        <img :src="'/frontend_assets/images/pin.svg'" alt="">
                                    </div>
                                    <div class="rgt-pin">
                                        <h4>Company registration certificate</h4>
                                        <a v-if="profile?.registration_pdf_url" :href="profile?.registration_pdf_url"
                                            target="_blank" style="font-size: smaller;"> View Documents</a>
                                        <p v-else>No document</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 prfpref-card-col">
                                <div class="prfpref-cardbx gap">
                                    <div class="wrap-pin">
                                        <img :src="'/frontend_assets/images/pin.svg'" alt="">
                                    </div>
                                    <div class="rgt-pin">
                                        <h4>Tourism operating license</h4>
                                        <!-- <p>View document</p> -->

                                        <a v-if="profile?.license_pdf_url" :href="profile?.license_pdf_url"
                                            target="_blank" style="font-size: smaller;"> View Documents</a>
                                        <p v-else>No document</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 prfpref-card-col">
                                <div class="prfpref-cardbx gap">
                                    <div class="wrap-pin">
                                        <img :src="'/frontend_assets/images/pin.svg'" alt="">
                                    </div>
                                    <div class="rgt-pin">
                                        <h4>Insurance</h4>


                                        <a v-if="profile?.insurance_pdf_url" :href="profile?.insurance_pdf_url"
                                            target="_blank" style="font-size: smaller;"> View Documents</a>
                                        <p v-else>No document</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="prfpref-card">
                    <div class="prfpref-topcard mb-0">
                        <div class="">
                            <div class="hdr_lft_prt extra">
                                <h2>Stripe Connect</h2>
                                <button type="button" class="cmn-butn" @click="HandleClickStripeConnect"
                                    v-if="!stripeConnectLoading && !profile?.stripe_account_link">Connect</button>
                                <a :href="profile?.stripe_account_link" target="_blank" class="cmn-butn"
                                    v-if="profile?.stripe_account_link">Go to Dashboard</a>
                                <SpinnerOverlay v-if="stripeConnectLoading" />
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
    </div>
    <!--edit filter popup-->
    <UpdateOperatorProfile :profile="profile" :countries="countries" :countryCodes="countryCodes" />
    <ChangeOperatorPassword />
    <UploadOperatorDocument :profile="profile" />
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { homeJs } from "@/custom.js";
import UpdateOperatorProfile from '@/components/Frontend/UpdateOperatorProfile.vue';
import ChangeOperatorPassword from '@/components/Frontend/ChangeOperatorPassword.vue';
import UploadOperatorDocument from '@/components/Frontend/UploadOperatorDocument.vue';
import SpinnerOverlay from '@/components/Frontend/Loader.vue';

const fileUrl1 = ref("");
const fileUrl2 = ref("");
const fileUrl3 = ref("");
const stripeConnectLoading = ref(false);

const props = defineProps({
    profile: Object,
    countries: Object,
    countryCodes: Array
});

onMounted(() => {
    homeJs();
    fileUrl1.value = props.profile?.registration_pdf_url || "";
    fileUrl2.value = props.profile?.license_pdf_url || "";
    fileUrl3.value = props.profile?.insurance_pdf_url || "";
    emit.emit("pageName", "Your Operator Profile");
    emit.emit("pageSubTitle", "Update your details and preferences");
});

// const HandleClickStripeConnect = async () => {
//     try {
//         stripeConnectLoading.value = true;
//         const { data } = await axios.post(route("frontend.stripe-connect"));

//         if (data?.status && data?.data?.url) {
//             window.location.href = data.data.url;
//         } else {
//             console.error("Stripe connect error:", data?.message || "No URL returned");
//         }
//     } catch (error) {
//         console.error("Stripe connect request failed:", error);
//     } finally {
//         stripeConnectLoading.value = false;
//     }
// };

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
