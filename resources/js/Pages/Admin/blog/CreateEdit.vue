<template>
    <div>

        <Head title="Create Blog" v-if="!props.blog" />

        <Head title="Edit Blog" v-if="props.blog" />

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
                            <label for="illness_type">Category<span class="text-danger">*</span></label>
                            <select class="form-control border-gray-200" v-model="form.category_id">
                                <option value="">Select Category</option>
                                <option v-for="category in categories" :value="category.id">{{ category.title }}</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.category_id">{{ form.errors.category_id }}</span>
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

                        <div class="form-group col-lg-6">
                            <label for="tags">Tags <span class="text-danger">*</span></label>
                            <input type="text" id="tags" v-model="form.tags" class="form-control border-gray-200"
                                placeholder="Tags" />
                            <span class="text-danger" v-if="form.errors.tags">{{
                                form.errors.tags
                                }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="posted_by">Posted By <span class="text-danger">*</span></label>
                            <input type="text" id="posted_by" v-model="form.posted_by" class="form-control border-gray-200"
                                placeholder="Posted By" />
                            <span class="text-danger" v-if="form.errors.posted_by">{{
                                form.errors.posted_by
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
                            <label for="photo">Thumbnail <span v-if="!blog" class="text-danger">*</span></label>
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
                            <Link :href="route('admin.blog.index')" class="btn btn-info">Cancel</Link>
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
    blog: Object,
    categories: Array
});
const imageUrl = ref("");

const form = useForm({
    title: props.blog?.title || null,
    description: props.blog?.description || null,
    photo: null,
    status: props.blog?.status || 1,
    category_id: props.blog?.category_id || "",
    tags: props.blog?.tags || "",
    posted_by: props.blog?.posted_by || "",
});

// watch(() => props.errors.thumbnail, (newValue) => {
//     emit.emit("fileuploadmessage", newValue);
// });

onMounted(() => {
    imageUrl.value = props.blog?.full_photo_url || "";
    const pageTitle = props.blog ? "Edit Blog" : "Create Blog";
    emit.emit("pageName", "Content Management", [
        {
            title: "Blog List",
            routeName: "admin.blog.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.blog) {
        form.post(route("admin.blog.edit", props.blog.id));
    } else {
        form.post(route("admin.blog.create"));
    }
}
</script>