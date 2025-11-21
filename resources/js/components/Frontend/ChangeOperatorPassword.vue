<template>
    <div class="filtr-backdrop" data-popup="updtpassword"></div>
    <div class="filtr-rgtsdepnl" data-popup="updtpassword">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Change password</h2>
                <p>Click "Save" to update your password.</p>
            </div>
            <div class="popup_form_outer">
                <div class="form_box add_card_popup">
                    <form @submit.prevent="handleChangePassword">
                        <div class="row form_row">
                            <div class="col-12 form_col">
                                <label>Old password</label>
                                <div class="input_hldr">
                                    <input type="password" placeholder="****************" class="pass_inp"
                                        v-model="form.current_password">
                                    <span class="text-danger" v-if="form.errors.current_password">{{ form.errors.current_password
                                    }}</span>
                                    <button type="button" class="pass_shwHde_bttn">
                                        <img :src="'/frontend_assets/images/eye_slash_icon.svg'" alt="Eye Slash"
                                            class="eye_slsh_icon">
                                        <img :src="'/frontend_assets/images/eye_icon.svg'" alt="Eye" class="eye_icon">
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>New password</label>
                                <div class="input_hldr">
                                    <input type="password" placeholder="****************" class="pass_inp"
                                        v-model="form.new_password">
                                    <span class="text-danger" v-if="form.errors.new_password">{{ form.errors.new_password
                                    }}</span>
                                    <button type="button" class="pass_shwHde_bttn">
                                        <img :src="'/frontend_assets/images/eye_slash_icon.svg'" alt="Eye Slash"
                                            class="eye_slsh_icon">
                                        <img :src="'/frontend_assets/images/eye_icon.svg'" alt="Eye" class="eye_icon">
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 form_col">
                                <label>Confirm password</label>
                                <div class="input_hldr">
                                    <input type="password" placeholder="****************" class="pass_inp"
                                        v-model="form.confirm_password">
                                    <span class="text-danger" v-if="form.errors.confirm_password">{{ form.errors.confirm_password
                                    }}</span>
                                    <button type="button" class="pass_shwHde_bttn">
                                        <img :src="'/frontend_assets/images/eye_slash_icon.svg'" alt="Eye Slash"
                                            class="eye_slsh_icon">
                                        <img :src="'/frontend_assets/images/eye_icon.svg'" alt="Eye" class="eye_icon">
                                    </button>
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


const form = useForm({
    current_password: '',
    new_password: '',
    confirm_password: '',
});

const handleChangePassword = () => {
    form.post(route('frontend.operator-change-password'), {
        onSuccess: () => {
            document.body.classList.remove("no-scroll");
            document.querySelectorAll(".filtr-rgtsdepnl").forEach(function (panel) {
                panel.classList.remove("popopen");
            });
            document.querySelectorAll(".filtr-backdrop").forEach(function (backdrop) {
                backdrop.classList.remove("open");
            });
            form.reset();
        }
    });
};
</script>