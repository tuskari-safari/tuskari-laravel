<template lang="">
    <Head title="Create Page Meta" v-if="!props.pageMeta" />
    <Head title="Edit Page Meta" v-if="props.pageMeta" />
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="page_name">Page Name <span class="text-danger">*</span></label>
                        <select id="page_name" class="form-control border-gray-200" v-model="form.page_name">
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
                            <option value="why-it-different">Why It Different</option>
                            <option value="responsible-travel">Responsible Travel</option>
                            <option value="faq">FAQ</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.page_name">{{ form.errors.page_name }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="index_follow">Index/Follow <span class="text-danger">*</span></label>
                        <select id="index_follow" class="form-control border-gray-200" v-model="form.index_follow">
                            <option value="">Select Option</option>
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.index_follow">{{ form.errors.index_follow }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="meta_title">Meta Title</label>
                        <input id="meta_title" type="text" v-model="form.meta_title" class="form-control border-gray-200" placeholder="Meta Title" />
                        <span class="text-danger" v-if="form.errors.meta_title">{{ form.errors.meta_title }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="meta_description">Meta Description</label>
                        <textarea id="meta_description"  v-model="form.meta_description" class="form-control border-gray-200" placeholder="Meta Description" rows="4"></textarea>
                        <span class="text-danger" v-if="form.errors.meta_description">{{ form.errors.meta_description }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_title">OG Title</label>
                        <input id="og_title" type="text" v-model="form.og_title" class="form-control border-gray-200" placeholder="OG Title" />
                        <span class="text-danger" v-if="form.errors.og_title">{{ form.errors.og_title }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_type">OG Type</label>
                        <input id="og_type" type="text" v-model="form.og_type" class="form-control border-gray-200" placeholder="OG Type" />
                        <span class="text-danger" v-if="form.errors.og_type">{{ form.errors.og_type }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_image">OG Image URL</label>
                        <input id="og_image" type="url" v-model="form.og_image" class="form-control border-gray-200" placeholder="OG Image URL" />
                        <span class="text-danger" v-if="form.errors.og_image">{{ form.errors.og_image }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_image_width">OG Image Width</label>
                        <input id="og_image_width"  v-model="form.og_image_width" type="number" class="form-control border-gray-200" placeholder="OG Image Width" />
                        <span class="text-danger" v-if="form.errors.og_image_width">{{ form.errors.og_image_width }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_image_height">OG Image Height</label>
                        <input id="og_image_height" v-model="form.og_image_height" type="number" class="form-control border-gray-200" placeholder="OG Image Height" />
                        <span class="text-danger" v-if="form.errors.og_image_height">{{ form.errors.og_image_height }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_url">OG URL</label>
                        <input id="og_url" type="url" v-model="form.og_url" class="form-control border-gray-200" placeholder="OG URL" />
                        <span class="text-danger" v-if="form.errors.og_url">{{ form.errors.og_url }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="canonical_url">Canonical URL</label>
                        <input id="canonical_url" v-model="form.canonical_url" type="url" class="form-control border-gray-200" placeholder="Canonical URL" />
                        <span class="text-danger" v-if="form.errors.canonical_url">{{ form.errors.canonical_url }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="og_description">OG Description</label>
                        <textarea id="og_description"  v-model="form.og_description" class="form-control border-gray-200" placeholder="OG Description" rows="4"></textarea>
                        <span class="text-danger" v-if="form.errors.og_description">{{ form.errors.og_description }}</span>
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="schema_details">Schema Details</label>
                        <textarea id="schema_details" v-model="form.schema_details" class="form-control border-gray-200" placeholder="Schema Details (JSON)" rows="5"></textarea>
                        <span class="text-danger" v-if="form.errors.schema_details">{{ form.errors.schema_details }}</span>
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
                                <Link :href="route('admin.page-meta.index')" class="btn btn-secondary">Cancel</Link>
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
import SubmitButton from "../../../components/SubmitButton.vue";

const props = defineProps({
    errors: Object,
    pageMeta: Object,
});

const form = useForm({
    page_name: props.pageMeta?.page_name || "",
    meta_title: props.pageMeta?.meta_title || "",
    meta_description: props.pageMeta?.meta_description || "",
    og_title: props.pageMeta?.og_title || "",
    og_type: props.pageMeta?.og_type || "",
    og_image: props.pageMeta?.og_image || "",
    og_image_width: props.pageMeta?.og_image_width || "",
    og_image_height: props.pageMeta?.og_image_height || "",
    og_url: props.pageMeta?.og_url || "",
    og_description: props.pageMeta?.og_description || "",
    canonical_url: props.pageMeta?.canonical_url || "",
    index_follow: props.pageMeta?.index_follow ?? true,
    schema_details: props.pageMeta?.schema_details || "",
});

onMounted(() => {
    if (props.pageMeta) {
        emit.emit("pageName", "Content Management", [
            {
                title: "Page Meta List",
                routeName: "admin.page-meta.index",
            },
            {
                title: "Edit Page Meta",
                routeName: "",
            },
        ]);
    } else {
        emit.emit("pageName", "Content Management", [
            {
                title: "Page Meta List",
                routeName: "admin.page-meta.index",
            },
            {
                title: "Create Page Meta",
                routeName: "",
            },
        ]);
    }
});

function submit() {
    if (props.pageMeta) {
        form.put("/admin/page-meta/" + props.pageMeta.id);
    } else {
        form.post("/admin/page-meta");
    }
}
</script>