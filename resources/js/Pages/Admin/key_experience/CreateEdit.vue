<template lang="">
    <div>
        <Head title="Create key experience" v-if="!props.key_experience" />
        <Head title="Edit key experience" v-if="props.key_experience" />

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
                            <label for="photo">Thumbnail <span v-if="!key_experience" class="text-danger">*</span></label>
                            <FileUpload @input="form.thumbnail = $event.target.files[0]"
                            :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.thumbnail">{{ form.errors.thumbnail }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="photo">Image <span v-if="!key_experience" class="text-danger">*</span></label>
                            <FileUpload  @input="form.image = $event.target.files[0]" :imageurl="imageUrl2" />
                            <span class="text-danger" v-if="form.errors.image">{{ form.errors.image }}</span>
                        </div>

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.key-experience.index')" class="btn btn-info">Cancel</Link>
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
    key_experience: Object,
});

const imageUrl = ref("");
const imageUrl2 = ref("");

const form = useForm({
    title: props.key_experience?.title || null,
    thumbnail: null,
    image: null,
    subtitle: props.key_experience?.subtitle || null
});

onMounted(() => {
    imageUrl.value = props.key_experience?.thumbnail_url || "";
    imageUrl2.value = props.key_experience?.image_url || "";
    const pageTitle = props.key_experience ? "Edit Key Experience" : "Create Key Experience";

    emit.emit("pageName", "Master Management", [
        {
            title: "Key Experience List",
            routeName: "admin.key-experience.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);

});

function submit() {
    if (props.key_experience) {
        form.post(route("admin.key-experience.edit", props.key_experience.id));
    } else {
        form.post(route("admin.key-experience.create"));
    }
}
</script>
<style lang="">
</style>