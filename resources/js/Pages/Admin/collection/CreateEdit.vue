<template lang="">
    <div>
        <Head title="Create Collection" v-if="!props.collection" />
        <Head title="Edit Collection" v-if="props.collection" />

        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row" v-auto-animate>
                        <div class="form-group col-lg-6">
                            <label for="name">Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" v-model="form.name" class="form-control border-gray-200" placeholder="Collection Name">
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
                                    <Link :href="route('admin.collection.index')" class="btn btn-info">Cancel</Link>
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
    collection: Object,
});

const form = useForm({
    name: props.collection?.name || null,
    show_in_frontend: props.collection?.show_in_frontend || false
});

onMounted(() => {
    const pageTitle = props.collection ? "Edit Collection" : "Create Collection";

    emit.emit("pageName", "Collection Management", [
        {
            title: "Collection List",
            routeName: "admin.collection.index",
        },
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    if (props.collection) {
        form.post(route("admin.collection.edit", props.collection.id));
    } else {
        form.post(route("admin.collection.create"));
    }
}
</script>