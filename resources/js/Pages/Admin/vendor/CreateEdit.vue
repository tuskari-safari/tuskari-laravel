<template lang="">
    <div>
        <Head title="Create User" v-if="!props.user" />
        <Head title="Edit User" v-if="props.user" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>
                        <div class="form-group col-lg-6">
                            <label for="first_name">First name <span class="text-danger">*</span></label>
                            <input type="text" id="first_name" v-model="form.first_name" class="form-control border-gray-200" placeholder="First name" @input="form.first_name = form.first_name.replace(/[^a-zA-Z\s]/g, '')">
                            <span class="text-danger" v-if="form.errors.first_name">{{ form.errors.first_name }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="last_name">Last name <span class="text-danger">*</span></label>
                            <input type="text" id="last_name" v-model="form.last_name" class="form-control border-gray-200" placeholder="Last name">
                            <span class="text-danger" v-if="form.errors.last_name">{{ form.errors.last_name }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" autocomplete="new-password" id="email" v-model="form.email" class="form-control border-gray-200" placeholder="Email">
                            <span class="text-danger" v-if="form.errors.email">{{ form.errors.email }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <div style='display: flex;justify-content: center;align-items: center;'>
                                <input type="number" autocomplete='new_password' id="phone" v-model="form.phone" class="form-control border-gray-200" placeholder="Phone">
                            </div>
                            <span class="text-danger" v-if="form.errors.phone">{{ form.errors.phone }}</span>
                        </div>

                         <!-- <div class="col-md-6">
                                    <label for="is_approved"> <strong>Report Approval</strong></label>
                                    <select class="form-control" v-model="form.is_approved">
                                        <option value="">Select Status</option>
                                        <option value="1">Approve</option>
                                        <option value="0">Reject</option>
                                    </select>
                        </div> -->

                        <div class="form-group col-lg-6" v-if="!props.user">
                            <label for="new_password">Password <span class="text-danger">*</span></label>
                            <div class="password_box">
                                <input :type="showPassword ? 'text' : 'password'" id="password" autocomplete="new-password" v-model="form.password" class="form-control border-gray-200" placeholder="Password">
                                <div class="control">
                                    <span class="icon is-small is-right">
                                        <i @click="toggleShow" class="fas" :class="{ 'fa-eye-slash': showPassword, 'fa-eye': !showPassword }"></i>
                                    </span>
                                </div>
                            </div>
                            <span class="text-danger" v-if="form.errors.password">{{ form.errors.password }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="dob">DOB <span class="text-danger">*</span></label>
                            <datepicker v-model="form.dob" :format='"MMM dd, yyyy"' :max-date="new Date(new Date().setFullYear(new Date().getFullYear()-18))" />
                            <br>
                            <span class="text-danger" v-if="form.errors.dob">{{ form.errors.dob }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="status">Status <span class="text-danger">*</span></label>
                            <select id="status" class="form-control border-gray-200" v-model="form.status">
                                <option value="">Select Status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.status">{{ form.errors.status }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="profile_photo">Profile Photo <span v-if="!user" class="text-danger">*</span></label>
                            <file-upload @input="form.profile_photo = $event.target.files[0]" :imageurl="imageUrl" />
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="license">Upload License <span v-if="!user" class="text-danger">*</span></label>
                            <document-upload @input="form.license = $event.target.files[0]" :fileUrl="fileUrl" accept="application/pdf"/>
                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.vendors')" class="btn btn-info btn-secondary">Cancel</Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, watch, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import FilePond from "../../../components/FilePond.vue";
import FileUpload from "../../../components/FileUpload.vue";
import Datepicker from "../../../components/Datepicker.vue";
import SubmitButton from "../../../components/SubmitButton.vue";
import DocumentUpload from "../../../components/DocumentUpload.vue";


const props = defineProps({
    errors: Object,
    user: Object,
});

const form = useForm({
    first_name: props.user?.first_name || null,
    last_name: props.user?.last_name || null,
    email: props.user?.email || null,
    password: null,
    phone: props.user?.phone || null,
    dob: props.user?.dob
        ? new Date(props.user?.dob)
        : new Date(new Date().setFullYear(new Date().getFullYear() - 18)),

    status: props.user?.active || 1,
    profile_photo: null,
    license: null
});

watch(() => props.errors.profile_photo, (newValue) => {
    emit.emit("fileuploadmessage", newValue);
})

const imageUrl = ref("");
const fileUrl = ref("");

onMounted(() => {
    imageUrl.value = props.user?.profile_photo_url || "";
    fileUrl.value = props.user?.license_doc_url || "";

    const pageTitle = props.user ? "Edit Vendor" : "Create Vendor";
    emit.emit("pageName", "User Management", [
        {
            title: "Vendor List",
            routeName: "admin.vendors",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.user) {
        form.post(route("admin.editVendor", props.user.id));
    } else {
        form.post(route("admin.createVendor"));
    }
}

const showPassword = ref(false);

const toggleShow = async () => {
    showPassword.value = !showPassword.value;
};
</script>