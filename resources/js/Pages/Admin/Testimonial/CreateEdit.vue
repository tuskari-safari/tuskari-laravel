<template lang="">

    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <!-- {{ $form }} -->


                    <div class="form-group col-lg-6">
                            <label for="title">Title <span class="text-danger">*</span></label>
                            <input type="text" id="title" v-model="form.title" class="form-control border-gray-200" placeholder="Title">
                            <span class="text-danger" v-if="form.errors.title">{{ form.errors.title }}</span>
                    </div>

                        <div class="form-group col-lg-6">
                            <label for="full_name">Full Name</label>
                            <input type="text" id="full_name" v-model="form.full_name" class="form-control border-gray-200" placeholder="Full Name">
                            <span class="text-danger" v-if="form.errors.full_name">{{ form.errors.full_name }}</span>
                        </div>

                    <div class="form-group col-lg-6">
                        <label for="rating">Rating <span class="text-danger">*</span></label>
                        <select id="rating" class="form-control border-gray-200" v-model="form.rating">
                            <option value="">Select Rating</option>
                            <option value="1">1 Star</option>
                            <option value="2">2 Stars</option>
                            <option value="3">3 Stars</option>
                            <option value="4">4 Stars</option>
                            <option value="5">5 Stars</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.rating">{{ form.errors.rating }}</span>
                   </div>
                   <div class="form-group col-lg-12">
                        <label for="description">Description <span class="text-danger">*</span></label>
                        <textarea id="description" v-model="form.description" class="form-control border-gray-200" placeholder="description"></textarea>
                        <span class="text-danger" v-if="form.errors.description">{{ form.errors.description }}</span>
                   </div>
                   <div class="form-group col-lg-6">                   
                        <label for="user_image">User Image</label>
                        <file-upload @input="form.user_image = $event.target.files[0]" :imageurl="imageUrl" />
                        <span class="text-danger" v-if="form.errors.user_image">{{ form.errors.user_image }}</span>
                    </div> 
                
                   <div class="form-group col-lg-6">
                        <label for="status">Status <span class="text-danger">*</span></label>
                        <select id="status" class="form-control border-gray-200" v-model="form.status">
                            <option value="" selected>Select Status</option>
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
                                    <Link href="/admin/testimonial" class="btn btn-secondary">Cancel</Link>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </div>
    
    </template>
<script setup>
import { onMounted, ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import FileUpload from '@/components/FileUpload.vue'
import SubmitButton from '@/components/SubmitButton.vue'

const props = defineProps({
    errors: Object,
    testimonial: Object
})

const form = useForm({
    title: props.testimonial?.title || null,
    description: props.testimonial?.description || null,
    full_name: props.testimonial?.full_name || "",
    rating: props.testimonial?.rating || "",
    status: props.testimonial?.status ?? 1,
    user_image: null,
})

const imageUrl = ref("");

watch(() => props.errors.user_image, (newValue) => {
    emit.emit("fileuploadmessage", newValue);
})

onMounted(() => {
    imageUrl.value = props.testimonial?.testimonial_user_image_path || "";
    const pageTitle = props.testimonial ? "Edit Testimonial" : "Create Testimonial";
    emit.emit('pageName', 'Testimonial Management', [{ title: "Testimonial list", routeName: "admin.testimonial.list" }, { title: pageTitle, routeName: "" }]);

})


function submit() {
    if (props.testimonial) {
        form.post(route("admin.testimonial.edit", props.testimonial.id));
    } else {
        form.post(route("admin.testimonial.create"));
    }
}


</script>