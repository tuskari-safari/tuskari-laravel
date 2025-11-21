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
                            <option value="Destination">Destination</option>
                            <option value="Blog Listing">Blog Listing</option>
                            <option value="Blog Details">Blog Details</option>
                            <option value="Story">Story</option>
                            <option value="Safari">Safari</option>
                            <option value="Safari Details">Safari Details</option>
                            <option value="Contact Us">Contact Us</option>
                            <option value="Country Guide">Country Guide</option>
                            <option value="National Park">National Park</option>
                            <option value="Private Reserves">Private Reserves</option>
                            <option value="Safari Style">Safari Style</option>
                            <option value="Operator">Operator</option>
                            <option value="how-it-works">How It Works</option>
                            <option value="why-it-different">Why it different</option>
                            <option value="responsible-travel">Responsible Travel</option>
                            <option value="faq">faq</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.page_name">{{ form.errors.page_name }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="title">Title <span class="text-danger">*</span></label>
                        <input id="title" v-model="form.title" class="form-control border-gray-200"
                            placeholder="Title">
                            <!-- <ckeditor v-model="form.title" :editor="editor" :config="editorConfig">
                            </ckeditor> -->
                        <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                    </div>
                    
                    <div class="form-group col-lg-6">
                        <label for="short_description">Short Description </label>
                        <textarea id="short_description" rows="5" v-model="form.short_description" class="form-control border-gray-200"
                            placeholder="Short Description"></textarea>
                        <span class="text-danger"
                            v-if="form.errors.short_description">{{ form.errors.short_description }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="banner_image">Banner Image <span v-if="!banner" class="text-danger">*</span></label>
                        <file-upload @input="form.banner_image = $event.target.files[0]" :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.banner_image">{{ form.errors.banner_image }}</span>
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
                                <Link :href="route('admin.banner_list')" class="btn btn-secondary">Cancel</Link>
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
    short_description: props.banner?.short_description || '',
    banner_image: null,
});

onMounted(() => {
    imageUrl.value = props.banner?.full_image || "";
    if (props.banner) {
        emit.emit("pageName", "Content Management", [
            {
                title: "Banner List",
                routeName: "admin.banner_list",
            },
            {
                title: "Edit Banner",
                routeName: "",
            },
        ]);
    } else {
        emit.emit("pageName", "Content Management", [
            {
                title: "Banner List",
                routeName: "admin.banner_list",
            },
            {
                title: "Add Banner",
                routeName: "",
            },
        ]);
    }
});

const submit = () => {
    if (props.banner) {
        form.post("/admin/update-banner/" + props.banner.id);
    } else {
        form.post(route("admin.create_banner"));
    }
}

const editorConfig = {
    height: 120,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
    ],
};
const editorConfig1 = {
    height: 220,
    resize_enabled: false,
    format_tags: 'p;h1;h2;h3;h4;h5;h6',
    toolbar: [
        { name: 'basicstyles', items: ['Bold', 'Italic'] },
        { name: 'styles', items: ['Format'] },
        { name: 'colors', items: ['FontColor', 'FontBackgroundColor'] }, // ✅ Add colors
        { name: 'lists', items: ['NumberedList', 'BulletedList'] },
    ],
};

</script>
