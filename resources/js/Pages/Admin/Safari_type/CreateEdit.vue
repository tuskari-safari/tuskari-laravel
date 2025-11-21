<template >
    <div>
        <Head title="Create Safari type" v-if="!props.type" />
        <Head title="Edit Safari type" v-if="props.type" />

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
                            <label for="subtitle">Subtitle <span class="text-danger">*</span></label>
                            <input type="text" id="subtitle" v-model="form.subtitle" class="form-control border-gray-200" placeholder="subtitle" rows="2"
                            style="resize: none;">
                            <span class="text-danger" v-if="form.errors.subtitle">{{ form.errors.subtitle }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="photo">Thumbnail <span v-if="!type" class="text-danger">*</span></label>
                            <FileUpload @input="form.thumbnail = $event.target.files[0]"
                            :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.thumbnail">{{ form.errors.thumbnail }}</span>
                        </div>
                        <div class="form-group col-lg-6">
                            <label for="button_text">Button Text <span class="text-danger">*</span></label>
                            <input type="text" id="button_text" v-model="form.button_text" class="form-control border-gray-200" placeholder="button_text">
                            <span class="text-danger" v-if="form.errors.button_text">{{ form.errors.button_text }}</span>
                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.safari-type.index')" class="btn btn-info">Cancel</Link>
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
import FilePond from "../../../components/FilePond.vue";
import FileUpload from "@/components/FileUpload.vue";

const props = defineProps({
    errors: Object,
    type: Object,
});

const imageUrl = ref("");

const form = useForm({
    title: props.type?.title || null,
    thumbnail: null,
    subtitle: props.type?.subtitle || null,
    button_text: props.type?.button_text || null,
});

onMounted(() => {
    imageUrl.value = props.type?.thumbnail_url || "";
    const pageTitle = props.type ? "Edit Safari type" : "Create Safari type";

    emit.emit("pageName", "Master Management", [
        {
            title: "Safari type List",
            routeName: "admin.safari-type.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);

});

function submit() {
    if (props.type) {
        form.post(route("admin.safari-type.edit", props.type.id));
    } else {
        form.post(route("admin.safari-type.create"));
    }
}
</script>
<style lang="">
</style>