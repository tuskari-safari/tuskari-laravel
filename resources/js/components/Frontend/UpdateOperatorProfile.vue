<template>
    <div class="filtr-backdrop" data-popup="edit"></div>
    <div class="filtr-rgtsdepnl" data-popup="edit">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Edit Profile</h2>
                <p>Click "Save" to update your profile.</p>
            </div>
            <div class="popup_form_outer">
                <div class="form_box add_card_popup profile-popup full-width">
                    <form @submit.prevent="handleSubmitProfileUpdate">
                        <div class="pfofile_pic_wrapper">
                            <div class="profile_pic_wrapper">
                                <div class="profile_image_box">
                                    <img :src="profile?.profile_photo_url ?? '/frontend_assets/images/user_img-edit.jpg'"
                                        alt="User Image" class="user_edit_img" id="user_profile">
                                    <label for="user-profile-icon" class="camera_icon">
                                        <img :src="'/frontend_assets/images/camera-profile.svg'" alt="Camera Icon">
                                    </label>
                                    <label for="user-profile-icon" class="upload_label">Upload Image</label>
                                    <input class="d-none" type="file" id="user-profile-icon"
                                        @input="form.profilePhoto = $event.target.files[0]">
                                    <span class="text-danger" v-if="form.errors.profile_photo">{{
                                        form.errors.profile_photo }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="row form_row">
                            <div class="col-12 form_col">
                                <label>Your full name <span class="text-danger">*</span></label>

                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter your name" v-model="form.full_name">
                                    <span class="text-danger" v-if="form.errors.full_name">{{ form.errors.full_name
                                        }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Safari Company Name<span class="text-danger">*</span></label>

                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter business/trading name"
                                        v-model="form.business_name">
                                    <span class="text-danger" v-if="form.errors.business_name">{{
                                        form.errors.business_name }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Email address<span class="text-danger">*</span></label>

                                <div class="input_hldr">
                                    <input type="email" placeholder="Enter email address" v-model="form.email">
                                    <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Your phone number<span class="text-danger">*</span></label>

                                <div class="input_hldr input-extra phnenmbr-sect">
                                    <div class="sortng_input_col">
                                        <Multiselect v-model="form.country_code" placeholder="+1"
                                            class="slect-dropdwn-new" mode="single" :close-on-select="true"
                                            :searchable="true" :create-option="false" :options="props?.countryCodes" />
                                    </div>
                                    <input type="text" placeholder="Enter phone number" class="phone_inp"
                                        v-model="form.phone"
                                        oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1')">

                                </div>
                                <span class="text-danger" v-if="form.errors.phone">{{ form.errors.phone }}</span>
                            </div>
                            <div class="col-12 form_col">
                                <label>Your country of operation<span class="text-danger">*</span></label>
                                <div class="input_hldr">
                                    <div class="input_hldr input-extra">
                                        <div class="sortng_input_col">
                                            <Multiselect v-model="form.country_id" placeholder="Select"
                                                class="slect-dropdwn-new" mode="single" :close-on-select="true"
                                                :searchable="true" :create-option="false" :options="props?.countries" />
                                            <span class="text-danger" v-if="form.errors.country_id">{{
                                                form.errors.country_id }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Main Destination Countries You Offer<span class="text-danger">*</span></label>
                                <div class="input_hldr">
                                    <div class="input_hldr input-extra">
                                        <div class="sortng_input_col">
                                            <Multiselect placeholder="Select" v-model="form.main_destination"
                                                class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                                mode="tags" :searchable="true" :options="destinations"
                                                :create-option="false" />
                                            <span class="text-danger" v-if="form.errors.main_destination">{{
                                                form.errors.main_destination }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>How Many Employees Do You Currently Have? <span
                                        class="text-danger">*</span></label>

                                <div class="input_hldr">
                                    <div class="input_hldr input-extra">
                                        <div class="sortng_input_col">
                                            <Multiselect placeholder="Select" v-model="form.business_type"
                                                class="multislect-dropdwn multislect-cmn-adj" :close-on-select="true"
                                                mode="single" :searchable="true" :options="[
                                                    { value: '1-5 Staff', label: '1-5 Staff' },
                                                    { value: '6-15 Staff', label: '6-15 Staff' },
                                                    { value: '16+ Staff', label: '16+ Staff' }
                                                ]" :create-option="false" />
                                            <span class="text-danger" v-if="form.errors.business_type">{{
                                                form.errors.business_type }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Years of Experience <span
                                        class="text-danger">*</span></label>

                                <div class="input_hldr">
                                    <div class="input_hldr input-extra">
                                        <div class="sortng_input_col">
                                           <input type="text" placeholder="Enter years of experience" v-model="form.business_years">
                                            <span class="text-danger" v-if="form.errors.business_years">{{
                                                form.errors.business_years }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Website Link</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter website link" v-model="form.website_link">
                                    <span class="text-danger" v-if="form.errors.website_link">{{
                                        form.errors.website_link }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Instagram Link</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Enter instagram link" v-model="form.instagram_link">
                                    <span class="text-danger" v-if="form.errors.instagram_link">{{
                                        form.errors.instagram_link }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Tell us briefly about your operation</label>
                                <div class="input_hldr text-extra">
                                    <textarea placeholder="Type here" v-model="form.about_operation"></textarea>
                                    <span class="text-danger" v-if="form.errors.about_operation">
                                        {{ form.errors.about_operation }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <FrontendSubmitButton :isLoading="form.processing" :value="'Save'" />
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { useForm } from '@inertiajs/vue3';
import Multiselect from '@vueform/multiselect'
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';
import { ref } from 'vue';

const props = defineProps({
    profile: Object,
    countries: Object,
    countryCodes: Array
});

const form = useForm({
    full_name: props?.profile?.full_name ?? '',
    main_destination: props?.profile?.main_destination ?? [],
    email: props?.profile?.email ?? '',
    business_name: props?.profile?.business_name ?? '',
    country_code: props?.profile?.country_code ?? '+94',
    country_id: props?.profile?.country_id ?? '',
    business_type: props?.profile?.business_type ?? '',
    business_years: props?.profile?.business_years ?? '',
    website_link: props?.profile?.website_link ?? '',
    instagram_link: props?.profile?.instagram_link ?? '',
    about_operation: props?.profile?.about_operation ?? '',
    phone: props?.profile?.phone ?? '',
    profilePhoto: ''
});

const handleSubmitProfileUpdate = () => {
    form.post(route('frontend.operator-my-profile'), {
        onSuccess: () => {
            document.body.classList.remove("no-scroll");
            document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
                panel.classList.remove("popopen");
            });
            document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
                backdrop.classList.remove("open");
            });
        }
    });
};

const destinations = ref([
    { value: 'South Africa', label: 'South Africa' },
    { value: 'Botswana', label: 'Botswana' },
    { value: 'Namibia', label: 'Namibia' },
    { value: 'Zambia', label: 'Zambia' },
    { value: 'Zimbabwe', label: 'Zimbabwe' },
    { value: 'Madagascar', label: 'Madagascar' },
    { value: 'Mozambique', label: 'Mozambique' },
    { value: 'Malawi', label: 'Malawi' },
    { value: 'Tanzania', label: 'Tanzania' },
    { value: 'Kenya', label: 'Kenya' },
    { value: 'Uganda', label: 'Uganda' },
    { value: 'Rwanda', label: 'Rwanda' },
    { value: 'Burundi', label: 'Burundi' },
    { value: 'Republic of Congo', label: 'Republic of Congo' },
    { value: 'Democratic Republic of Congo (DRC)', label: 'Democratic Republic of Congo (DRC)' },
    { value: 'Cameroon', label: 'Cameroon' },
    { value: 'Gabon', label: 'Gabon' },
    { value: 'Central African Republic', label: 'Central African Republic' }
]);

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
