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
                <div class="form_box add_card_popup">
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
                                </div>
                            </div>
                        </div>
                        <div class="row form_row">
                            <div class="col-12 form_col">
                                <label>Your full name</label>
                                <div class="input_hldr">
                                    <input type="text" placeholder="Kian Archie" class="" v-model="form.full_name">
                                    <span class="text-danger" v-if="form.errors.full_name">
                                        {{ form.errors.full_name }}</span>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Your email address</label>
                                <div class="input_hldr">
                                    <input type="email" placeholder="kian.archie@example.com" class=""
                                        v-model="form.email">
                                    <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
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
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';

const props = defineProps({
    profile: Object
});

const form = useForm({
    full_name: props?.profile?.full_name ?? '',
    email: props?.profile?.email ?? '',
    profilePhoto: ''
});

const handleSubmitProfileUpdate = () => {
    form.post(route('frontend.traveller-profile'), {
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

</script>