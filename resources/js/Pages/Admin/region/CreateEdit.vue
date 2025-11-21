<template>
    <div>
        <Head title="Create Region" v-if="!props.region" />
        <Head title="Edit Region" v-if="props.region" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>

                        <div class="form-group col-lg-6">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-control border-gray-200" placeholder="Region Name">
                            <span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="image">Image <span v-if="!region" class="text-danger">*</span></label>
                            <FileUpload @input="form.image = $event.target.files[0]"
                            :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</span>
                        </div>

                        <div class="form-group col-lg-12">
                            <label for="description">Description</label>
                            <textarea id="description" v-model="form.description" class="form-control border-gray-200" placeholder="Region Description" rows="4" style="resize: none;"></textarea>
                            <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
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
                                    <Link :href="route('admin.region.index')" class="btn btn-info">Cancel</Link>
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
import SubmitButton from "../../../components/SubmitButton.vue";
import FileUpload from "@/components/FileUpload.vue";

const props = defineProps({
    errors: Object,
    region: Object,
});

const imageUrl = ref("");

const form = useForm({
    name: props.region?.name || null,
    image: null,
    description: props.region?.description || null,
    status: props.region?.status || 1,
});

onMounted(() => {
    imageUrl.value = props.region?.image_url || "";
    const pageTitle = props.region ? "Edit Region" : "Create Region";

    emit.emit("pageName", "Master Management", [
        {
            title: "Region List",
            routeName: "admin.region.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.region) {
        form.post(route("admin.region.edit", props.region.id));
    } else {
        form.post(route("admin.region.create"));
    }
}
</script>
<style lang="">
</style>