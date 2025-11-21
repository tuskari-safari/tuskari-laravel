<template lang="">
    <div>
        <Head title="Create Available tag" v-if="!props.tag" />
        <Head title="Edit Available tag" v-if="props.tag" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>
                        <div class="form-group col-lg-6">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-control border-gray-200" placeholder="Available tag Name">
                            <span class="text-danger" v-if="form.errors.name">{{ form.errors.name }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="show_in_frontend">Show in Frontend</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="show_in_frontend" v-model="form.show_in_frontend" />
                                <label class="form-check-label" for="show_in_frontend">Enable to show in frontend</label>
                            </div>
                            <span class="text-danger" v-if="form.errors.show_in_frontend">{{ form.errors.show_in_frontend }}</span>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="pr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                                </div>
                                <div>
                                    <Link :href="route('admin.available-tags.index')" class="btn btn-info">Cancel</Link>
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
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "../../../components/SubmitButton.vue";

const props = defineProps({
    errors: Object,
    tag: Object,
});

const form = useForm({
    name: props.tag?.name || null,
    show_in_frontend: props.tag?.show_in_frontend || false
});

onMounted(() => {
    const pageTitle = props.tag ? "Edit Available tag" : "Create Available tag";

    emit.emit("pageName", "Available tag Management", [
        {
            title: "Available tag List",
            routeName: "admin.available-tags.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.tag) {
        form.post(route("admin.available-tags.edit", props.tag.id));
    } else {
        form.post(route("admin.available-tags.create"));
    }
}
</script>