<template lang="">
    <div>
        <Head title="Create Wild life" v-if="!props.wildlife" />
        <Head title="Edit Wild life" v-if="props.wildlife" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>

                        <div class="form-group col-lg-6">
                            <label for="title">Name <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.name" class="form-control border-gray-200" placeholder="Name">
                            <span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="subtitle">Short Description <span class="text-danger">*</span></label>
                            <textarea id="subtitle" v-model="form.subtitle" class="form-control border-gray-200" placeholder="Short Description"></textarea>
                            <span class="text-danger" v-if="form.errors.subtitle">{{ form . errors . subtitle }}</span>
                        </div>

                        <div class="form-group col-lg-4">
                            <label for="icon">Icon</label>
                            <FileUpload @input="form.thumbnail = $event.target.files[0]"
                            :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.thumbnail">
                                {{ form.errors.thumbnail }}
                            </span>
                        </div>
                      

                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.wild-lives')" class="btn btn-info">Cancel</Link>
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
import FileUpload from "@/components/FileUpload.vue";

const props = defineProps({
    errors: Object,
    wildlife: Object,
});

const imageUrl = ref("");

const form = useForm({
    name: props.wildlife?.name || null,
    thumbnail: props.wildlife?.thumbnail || null,
    subtitle: props.wildlife?.subtitle || null
});

onMounted(() => {
    const pageTitle = props.wildlife ? "Edit wildlife" : "Create wildlife";
    imageUrl.value = props.wildlife?.full_thumbnail_url || "";
    emit.emit("pageName", "Master Management", [
        {
            title: "Wildlife List",
            routeName: "admin.wild-lives",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.wildlife) {
        form.post(route("admin.wild-life.edit", props.wildlife.id));
    } else {
        form.post(route("admin.wild-life.create"));
    }
}
</script>
<style lang="">
</style>