<template lang="">
    <div>
        <Head title="Create Category" v-if="!props.category" />
        <Head title="Edit Category" v-if="props.category" />

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
                                    <Link :href="route('admin.category.index')" class="btn btn-info">Cancel</Link>
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
import Datepicker from "../../../components/Datepicker.vue";
import SubmitButton from "../../../components/SubmitButton.vue";

const props = defineProps({
    errors: Object,
    category: Object,
});

const form = useForm({
    title: props.category?.title || null,
    status: props.category?.status || 1
});
onMounted(() => {
    const pageTitle = props.category ? "Edit Category" : "Create Category";

    emit.emit("pageName", "Blog Management", [
        {
            title: "Category List",
            routeName: "admin.category.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);

});

function submit() {
    if (props.category) {
        form.post(route("admin.category.update", props.category.id));
    } else {
        form.post(route("admin.category.store"));
    }
}
</script>
<style lang="">
</style>