<template>
    <div>

        <Head title="Create Accomodation" v-if="!props.accomodation" />

        <Head title="Edit Accomodation" v-if="props.accomodation" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-6">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200"
                                placeholder="Title" />
                            <span class="text-danger" v-if="form.errors.title">{{
                                form.errors.title
                                }}</span>
                        </div>

                      <div class="form-group col-lg-6">
                            <label for="types"> Type <span class="text-danger">*</span></label>
           
                            <Multiselect placeholder="Select Types" v-model="form.types" mode="tags"
                                :close-on-select="true" :searchable="true" :create-option="false" :groups="true"
                                :options="[{  options: props.types }]" />
                                <span class="text-danger" v-if="form.errors.types">{{ form.errors.types }}</span>
                        </div>


                        <div class="form-group col-lg-6">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select id="status" class="form-control border-gray-200" v-model="form.status">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.status">{{
                                form.errors.status
                                }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <ckeditor v-model="form.description"></ckeditor>
                            <span class="text-danger" v-if="form.errors.description">{{
                                form.errors.description
                                }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="photo">Image <span v-if="!accomodation" class="text-danger">*</span></label>
                            <FilePond v-model="form.photo" :myFile="imageUrl" />
                            <span class="text-danger" v-if="form.errors.photo">{{ form.errors.photo }}</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mr-2">
                            <submit-button :disabled="form.processing"
                                :isLoading="form.processing">Submit</submit-button>
                        </div>
                        <div class="">
                            <Link :href="route('admin.accomodations.index')" class="btn btn-info">Cancel</Link>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import SubmitButton from "../../../components/SubmitButton.vue";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import FileUpload from "../../../components/FileUpload.vue";
import { watch } from "vue";
import FilePond from "../../../components/FilePond.vue";
import Multiselect from '@vueform/multiselect'

let data = reactive({
    multiselect: [],
});
const props = defineProps({
    errors: Object,
    accomodation: Object,
    types: Array,
    selected_Type: Array

});

const imageUrl = ref("");

const form = useForm({
    title: props.accomodation?.title || null,
    description: props.accomodation?.description || null,
    photo: null,
    status: props.accomodation?.status || 1,
    types: props.selected_Type || []
});

// watch(() => props.errors.thumbnail, (newValue) => {
//     emit.emit("fileuploadmessage", newValue);
// });

onMounted(() => {
    imageUrl.value = props.accomodation?.full_photo_url || "";
    const pageTitle = props.accomodation ? "Edit accomodation" : "Create accomodation";
    emit.emit("pageName", "Master Management", [
        {
            title: "Accomodation List",
            routeName: "admin.accomodations.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.accomodation) {
        form.post(route("admin.accomodation.edit", props.accomodation.id));
    } else {
        form.post(route("admin.accomodation.create"));
    }
}
</script>
<style src="@vueform/multiselect/themes/default.css"></style>
