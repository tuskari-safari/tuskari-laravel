<template lang="">
    <div>
        <Head title="Create Product" v-if="!props.product" />
        <Head title="Edit Product" v-if="props.product" />
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>
                        <div class="form-group col-lg-6">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200" placeholder="Title">
                            <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="product_id">Category <span class="text-danger">*</span></label>
                            <select id="category_id" class="form-control border-gray-200" v-model="form.category_id">
                                <option value="">Select Category</option>
                                <option v-for="category in props.categories" :value="category.id">{{ category.title }}</option>
                            </select>
                            <span class="text-danger" v-if="form.errors.category_id">{{ form.errors.category_id }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control border-gray-200" v-model="form.description" placeholder="Description"></textarea>
                            <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="specification">Specification <span class="text-danger">*</span></label>
                            <textarea class="form-control border-gray-200" v-model="form.specification" placeholder="Specification"></textarea>
                            <span class="text-danger" v-if="form.errors.specification">{{ form.errors.specification }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="usage_sectors">Usage Sectors <span class="text-danger">*</span></label>
                            <input type="text" id="usage_sectors" v-model="form.usage_sectors" class="form-control border-gray-200" placeholder="Usage Sectors">
                            <span class="text-danger" v-if="form.errors.usage_sectors">{{ form.errors.usage_sectors }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="dealer_price">Dealer Price ($) <span class="text-danger">*</span></label>
                            <input type="text" id="dealer_price" v-model="form.dealer_price" class="form-control border-gray-200" placeholder="Dealer Price">
                            <span class="text-danger" v-if="form.errors.dealer_price">{{ form.errors.dealer_price }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="product_images">Product images <span v-if="!product" class="text-danger">*</span></label>
                            <FileUploadMultiple @input="form.product_images = $event.target.files" :imageurl="imageUrl" />
                            <span class="text-danger" v-if="form.errors.product_images">{{ form.errors.product_images }}</span>
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

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.products.index')" class="btn btn-info">Cancel</Link>
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
import { onMounted, reactive, ref } from "vue";
import { useForm, router } from "@inertiajs/vue3";
import FileUploadMultiple from "../../../components/FileUploadMultiple.vue";
import Datepicker from "../../../components/Datepicker.vue";
import SubmitButton from "../../../components/SubmitButton.vue";

const props = defineProps({
    errors: Object,
    product: Object,
    categories: Object,
});

const imageUrl = ref([]);
const removeImg = ref([]);

const form = useForm({
    title: props.product?.title || null,
    category_id: props.product?.category_id || "",
    description: props.product?.description || null,
    specification: props.product?.specification || null,
    usage_sectors: props.product?.usage_sectors || null,
    dealer_price: props.product?.dealer_price || null,
    status: props.product?.status || 1,
    product_images: null,
    removeImg: removeImg.value,
});

onMounted(() => {
    imageUrl.value = props.product?.images || "";
    const pageTitle = props.product ? "Edit Product" : "Create Product";

    emit.emit("pageName", "Resource Management", [
        {
            title: "Product List",
            routeName: "admin.products.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
    emit.on("imageRemove", function (img) {
        removeImg.value.push(img.id);
    });

    emit.on("imageRemoveLocal", function (index) {
        let fileListArr = Array.from(form.product_images);
        fileListArr.splice(index, 1); // here u remove the file
        form.product_images = fileListArr;
    });
});

function submit() {
    if (props.product) {
        form.post(route("admin.products.update", props.product.id));
    } else {
        form.post(route("admin.products.store"));
    }
}
</script>
<style lang="">
</style>