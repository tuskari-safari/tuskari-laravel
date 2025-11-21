<template>
    <div>

        <Head title="Create Safari Operator" v-if="!props.operator" />

        <Head title="Edit Safari Operator" v-if="props.operator" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-6">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-control border-gray-200"
                                placeholder="Name" />
                            <span class="text-danger" v-if="form.errors.name">{{
                                form.errors.name
                                }}</span>
                        </div>
                         <div class="form-group col-lg-6">
                            <label for="details">Details <span class="text-danger">*</span></label>
                            <textarea id="details" v-model="form.details" class="form-control border-gray-200" placeholder="Details"></textarea>
                            <span class="text-danger" v-if="form.errors.details">{{ form . errors . details }}</span>
                        </div>

                         <div class="form-group col-lg-6">
                            <label for="location">Location <span class="text-danger">*</span></label>
                            <input type="text" id="location" v-model="form.location" class="form-control border-gray-200"
                                placeholder="Location" />
                            <span class="text-danger" v-if="form.errors.location">{{
                                form.errors.location
                                }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="no_of_employees">No of Employees <span class="text-danger">*</span></label>
                            <input type="text" id="no_of_employees" v-model="form.no_of_employees" class="form-control border-gray-200"
                                placeholder="No of Employees" />
                            <span class="text-danger" v-if="form.errors.no_of_employees">{{
                                form.errors.no_of_employees
                                }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="type">Type <span class="text-danger">*</span></label>
                            <input type="text" id="type" v-model="form.type" class="form-control border-gray-200"
                                placeholder="Type" />
                            <span class="text-danger" v-if="form.errors.type">{{
                                form.errors.type
                                }}</span>
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
                            <label for="logo">Logo <span v-if="!operator" class="text-danger">*</span></label>
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
                            <Link :href="route('admin.operators')" class="btn btn-info">Cancel</Link>
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

const props = defineProps({
    errors: Object,
    operator: Object,
    
});
const imageUrl = ref("");

const form = useForm({
    name: props.operator?.name || null,
    details: props.operator?.details || null,
    location: props.operator?.location || null,
    no_of_employees: props.operator?.no_of_employees || null,
    photo: null,
    status: props.operator?.status || 1,
    type: props.operator?.type || ""
});

onMounted(() => {
    imageUrl.value = props.operator?.operator_logo_url || "";
    
    const pageTitle = props.operator ? "Edit Safari Operator" : "Create Safari Operator";
    emit.emit("pageName", "Content Management", [
        {
            title: "Safari Operator List",
            routeName: "admin.operators",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.operator) {
        form.post(route("admin.editOperator", props.operator.id));
    } else {
        form.post(route("admin.createOperator"));
    }
}
</script>