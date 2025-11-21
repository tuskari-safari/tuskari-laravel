<template>

    <Head title="Update Contact info" v-if="props.contactInfo" />

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="traveller_enquiries">Traveller Enquiries Email</label>
                        <input type="text" id="traveller_enquiries" v-model="form.traveller_enquiries"
                            class="form-control border-gray-200" placeholder="Traveller Enquiries" />
                        <span class="text-danger" v-if="form.errors.traveller_enquiries">{{
                            form.errors.traveller_enquiries }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="operator_partnerships">Operator Partnerships Email</label>
                        <input type="text" id="operator_partnerships" v-model="form.operator_partnerships"
                            class="form-control border-gray-200" placeholder="Operator Partnerships" />
                        <span class="text-danger" v-if="form.errors.operator_partnerships">{{
                            form.errors.operator_partnerships }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="press_media">Press & Media Email</label>
                        <input type="text" id="press_media" v-model="form.press_media"
                            class="form-control border-gray-200" placeholder="Press & Media" />
                        <span class="text-danger" v-if="form.errors.press_media">{{ form.errors.press_media }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="general">General Email</label>
                        <input type="text" id="general" v-model="form.general" class="form-control border-gray-200"
                            placeholder="General" />
                        <span class="text-danger" v-if="form.errors.general">{{ form.errors.general }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="facebook_link">Facebook Link</label>
                        <input type="text" id="facebook_link" v-model="form.facebook_link"
                            class="form-control border-gray-200" placeholder="Facebook Link" />
                        <span class="text-danger" v-if="form.errors.facebook_link">{{ form.errors.facebook_link
                            }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="instagram_link">Instagram Link</label>
                        <input type="text" id="instagram_link" v-model="form.instagram_link"
                            class="form-control border-gray-200" placeholder="Instagram Link" />
                        <span class="text-danger" v-if="form.errors.instagram_link">{{ form.errors.instagram_link
                            }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="tiktok_link">TikTok Link</label>
                        <input type="text" id="tiktok_link" v-model="form.tiktok_link"
                            class="form-control border-gray-200" placeholder="TikTok Link" />
                        <span class="text-danger" v-if="form.errors.tiktok_link">{{ form.errors.tiktok_link }}</span>
                    </div>
                    <div class="form-group col-lg-6">
                        <label for="youtube_link">YouTube Link</label>
                        <input type="text" id="youtube_link" v-model="form.youtube_link"
                            class="form-control border-gray-200" placeholder="YouTube Link" />
                        <span class="text-danger" v-if="form.errors.youtube_link">{{ form.errors.youtube_link }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="mr-2">
                        <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                    </div>
                    <div class="">
                        <Link :href="route('admin.dashboard')" class="btn btn-info">Cancel</Link>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import SubmitButton from "@/components/SubmitButton.vue";

const props = defineProps({
    errors: Object,
    blog: Object,
    contactInfo: Object
});

const form = useForm({
    traveller_enquiries: props.contactInfo?.traveller_enquiries ?? "",
    operator_partnerships: props.contactInfo?.operator_partnerships ?? "",
    press_media: props.contactInfo?.press_media ?? "",
    general: props.contactInfo?.general?? "",
    facebook_link: props.contactInfo?.facebook_link?? "",
    instagram_link: props.contactInfo ? props.contactInfo.instagram_link : "",
    tiktok_link: props.contactInfo ? props.contactInfo.tiktok_link : "",
    youtube_link: props.contactInfo ? props.contactInfo.youtube_link : "",
});


onMounted(() => {
    const pageTitle = props.contactInfo ? "Edit Contact Info" : "Create Contact Info";
    emit.emit("pageName", "Customer Support Management", [
        {
            title: pageTitle,
            routeName: "",
        },
    ]);
});

function submit() {
    form.post(route("admin.update.contact-info"));
}
</script>