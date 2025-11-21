<template lang="">
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="page_name">Page Name <span class="text-danger">*</span></label>
                        <select id="page_name" v-model="form.page_name" class="form-control border-gray-200">
                            <option value="">Select Page</option>
                            <option value="Homepage">Homepage</option>
                            <option value="Contact-us">Contact Us</option>
                            <option value="Country Guide">Country Guide</option>
                            <option value="National Park">National Park</option>
                            <option value="Private Reserves">Private Reserves</option>
                            <option value="Safari Style">Safari Style</option>
                            <option value="Operator">Operator</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.page_name">{{ form.errors.page_name }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input id="title" v-model="form.title" class="form-control border-gray-200"
                            placeholder="Title">
                        <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                    </div>
                    
                    <div class="form-group col-lg-6">
                        <label for="subtitle">Short Description </label>
                        <textarea id="subtitle" rows="5" v-model="form.subtitle" class="form-control border-gray-200"
                            placeholder="Short Description"></textarea>
                        <span class="text-danger"
                            v-if="form.errors.subtitle">{{ form.errors.subtitle }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="banner_image">Banner Image <span v-if="!banner" class="text-danger">*</span></label>
                        <file-upload @input="form.thumbnail = $event.target.files[0]" :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.thumbnail">{{ form.errors.thumbnail }}</span>
                    </div>
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="pr-2">
                                <submit-button :disabled="form.processing"
                                    :isLoading="form.processing">Submit</submit-button>
                            </div>
                            <div>
                                <Link :href="route('admin.bottom-banner-list')" class="btn btn-secondary">Cancel</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

</template>
<script setup>
import { onMounted, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import { component as ckeditor } from "@mayasabha/ckeditor4-vue3";
import SubmitButton from "@/components/SubmitButton.vue";
import FileUpload from '@/components/FileUpload.vue'
import { route } from "ziggy-js";

const props = defineProps({
    errors: Object,
    banner: Object,
});
const imageUrl = ref('');

const form = useForm({
    page_name: props.banner?.page_name || '',
    title: props.banner?.title || '',
    subtitle: props.banner?.subtitle || '',
    thumbnail: null,
});

onMounted(() => {
    imageUrl.value = props.banner?.full_image || "";
    if (props.banner) {
        emit.emit("pageName", "Content Management", [
            {
                title: "Bottom Banner List",
                routeName: "admin.bottom-banner-list",
            },
            {
                title: "Edit Bottom Banner",
                routeName: "",
            },
        ]);
    } else {
        emit.emit("pageName", "Content Management", [
            {
                title: "Bottom Banner List",
                routeName: "admin.bottom-banner-list",
            },
            {
                title: "Add Bottom Banner",
                routeName: "",
            },
        ]);
    }
});

const submit = () => {
    if (props.banner) {
        form.post("/admin/update-bottom-banner/" + props.banner.id);
    } else {
        form.post(route("admin.create-bottom-banner"));
    }
}

</script>
