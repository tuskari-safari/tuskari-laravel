<template lang="">
    <div>
        <Head title="Create Activity" v-if="!props.key_experience" />
        <Head title="Edit Activity" v-if="props.key_experience" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>

                        <div class="form-group col-lg-6">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200" placeholder="Title">
                            <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="icon">Icon</label>
                            <FileUpload @input="form.icon = $event.target.files[0]"
                            :imageurl="imageUrl" accept="image/*" />
                            <span class="text-danger" v-if="form.errors.icon">
                                {{ form.errors.icon }}
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
                                    <Link :href="route('admin.activity.index')" class="btn btn-info">Cancel</Link>
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
    activity: Object,
});

const imageUrl = ref("");

const form = useForm({
    title: props.activity?.title || null,
    icon: props.activity?.icon || null,
});

onMounted(() => {
    const pageTitle = props.activity ? "Edit Activity" : "Create Activity";
    imageUrl.value = props.activity?.full_icon_url || "";
    emit.emit("pageName", "Master Management", [
        {
            title: "Activity List",
            routeName: "admin.activity.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);

});

function submit() {
    if (props.activity) {
        form.post(route("admin.activity.edit", props.activity.id));
    } else {
        form.post(route("admin.activity.create"));
    }
}
</script>
<style lang="">
</style>