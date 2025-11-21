<template>
    <div class="filtr-backdrop" data-popup="uploadDoc"></div>
    <div class="filtr-rgtsdepnl" data-popup="uploadDoc">
        <button class="fltrpop-closebutn" type="button">
            <img :src="'/frontend_assets/images/popclose-img.svg'" alt="Close">
        </button>
        <div class="filtr-rgtsdepnl-inr">
            <div class="filtr-rgtsdepnlhd">
                <h2 class="filtrpnlhdng">Upload Documents</h2>
                <p>Click "Save" to update your documents.</p>
            </div>
            <div class="popup_form_outer">
                <div class="form_box add_card_popup profile-popup full-width">
                    <form @submit.prevent="handleUploadOperatorDocument">
                        <div class="row form_row">
                            <div class="col-12 form_col">
                                <label for="icon">Company registration certificate </label>
                                <DocumentUplaod @input="form.registration_certificate = $event.target.files[0]" :fileUrl="fileUrl1" accept="application/pdf" />
                                <span class="text-danger" v-if="form.errors.registration_certificate">
                                    {{ form.errors.registration_certificate }}
                                </span>
                            </div>
                            <div class="col-12 form_col">
                                <label for="icon">Tourism operating license</label>
                                <DocumentUplaod @input="form.tourism_operating_license = $event.target.files[0]" :fileUrl="fileUrl2" accept="application/pdf" />
                                <span class="text-danger" v-if="form.errors.tourism_operating_license">
                                    {{ form.errors.tourism_operating_license }}
                                </span>
                            </div>
                            <div class="col-12 form_col">
                                <label for="icon">Insurance </label>
                                <DocumentUplaod @input="form.insurance = $event.target.files[0]" :fileUrl="fileUrl3" accept="application/pdf" />
                                <span class="text-danger" v-if="form.errors.insurance">
                                    {{ form.errors.insurance }}
                                </span>
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
import { onMounted, ref } from 'vue';
import Multiselect from '@vueform/multiselect';
import DocumentUplaod from "../DocumentUpload.vue";
import FrontendSubmitButton from '@/components/FrontendSubmitButton.vue';


const props = defineProps({
    profile: Object,

});
const fileUrl1 = ref("");
const fileUrl2 = ref("");
const fileUrl3 = ref("");

const form = useForm({
    registration_certificate: null,
    tourism_operating_license: null,
    insurance: null
});

const handleUploadOperatorDocument = () => {
    form.post(route('frontend.operator-upload-documents'), {
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


onMounted(() => {
    fileUrl1.value = props.profile?.registration_pdf_url || "";
    fileUrl2.value = props.profile?.license_pdf_url || "";
    fileUrl3.value = props.profile?.insurance_pdf_url || "";
})

</script>
<style src="@vueform/multiselect/themes/default.css"></style>
