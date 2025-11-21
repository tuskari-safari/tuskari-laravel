<template lang="">
         <Head title="Create support" v-if="!props.support" />
        <Head title="Edit support" v-if="props.support" />
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">

                     <div class="form-group col-lg-6">
                            <label for="status">Tag <span class="text-danger">*</span></label>
                            <select id="status" class="form-control border-gray-200" v-model="form.tag">
                                <option value="">Select tag</option>
                                <option value="quick-help">Quick Help</option>
                                <option value="booking-trip-support">Booking & Trip Support</option>
                                <option value="payments-pricing">Payments & Pricing</option>
                                <option value="trip-customisation">Trip Customisation</option>
                                <option value="account-dashboard">Account & Dashboard</option>
                            
                            </select>
                            <span class="text-danger" v-if="form.errors.status">{{
                                form.errors.status
                                }}</span>
                        </div>

                    <div class="form-group col-lg-6">
                        <label for="question">Question <span class="text-danger">*</span></label>
                        <textarea id="question" v-model="form.question" class="form-control border-gray-200" placeholder="Question"></textarea>
                        <span class="text-danger" v-if="form.errors.question">{{ form . errors . question }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="answer">Answer <span class="text-danger">*</span></label>
                        <textarea id="answer" v-model="form.answer" class="form-control border-gray-200" placeholder="Answer"></textarea>
                        <span class="text-danger" v-if="form.errors.answer">{{ form . errors . answer }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="active">Status <span class="text-danger">*</span></label>

                        <select id="active" class="form-control border-gray-200" v-model="form.status">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.status">{{ form . errors . active }}</span>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="mr-2">
                                <submit-button :disabled="form.processing"
                                    :isLoading="form.processing">Submit</submit-button>
                            </div>
                            <div class="">
                                <Link :href="route('admin.help-support.index')" class="btn btn-secondary">Cancel</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script setup>
import { onMounted, reactive, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import FileUpload from "../../../components/FileUpload.vue";
import Datepicker from "../../../components/Datepicker.vue";
import SubmitButton from "../../../components/SubmitButton.vue";


const props = defineProps({
  errors: Object,
  support: Object,
});

const form = useForm({
  tag: props.support?.tag || "",
  question: props.support?.question || null,
  answer: props.support?.answer || null,
  status: props.support?.status || 1,
});

onMounted(() => {
  if (props.support) {
    emit.emit("pageName", "Customer Support Management", [
      {
        title: "Help & Support List",
        routeName: "admin.help-support.index",
      },
      {
        title: "Edit Help & Support",
        routeName: "",
      },
    ]);
  } else {
    emit.emit("pageName", "Customer Support Management", [
      {
        title: "Help & Support List",
        routeName: "admin.help-support.index",
      },
      {
        title: "Create Help & Support",
        routeName: "",
      },
    ]);
  }
});

function submit() {
   if (props.support) {
        form.post(route("admin.help-support.edit", props.support.id));
    } else {
        form.post(route("admin.help-support.create"));
    }
}
</script>
