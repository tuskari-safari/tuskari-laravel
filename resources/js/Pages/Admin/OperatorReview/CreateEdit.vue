<template>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <form @submit.prevent="submit">
                <div class="form-group validated row">
                    <div class="form-group col-lg-6">
                        <label for="operator_id">Operator <span class="text-danger">*</span></label>
                        <select id="operator_id" v-model="form.operator_id" class="form-control border-gray-200">
                            <option value="">Select Operator</option>
                            <option v-for="operator in operators" :key="operator.id" :value="operator.id">{{ operator.full_name }}</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.operator_id">{{ form.errors.operator_id }}</span>
                    </div>

                    <div class="form-group col-lg-6">
                        <label for="source">Source <span class="text-danger">*</span></label>
                        <input type="text" id="source" v-model="form.source" class="form-control border-gray-200" placeholder="e.g., From Google">
                        <span class="text-danger" v-if="form.errors.source">{{ form.errors.source }}</span>
                    </div>

                    <div class="form-group col-lg-12">
                        <label for="review_text">Review Text <span class="text-danger">*</span></label>
                        <textarea id="review_text" v-model="form.review_text" class="form-control border-gray-200" placeholder="Review Text" rows="5"></textarea>
                        <span class="text-danger" v-if="form.errors.review_text">{{ form.errors.review_text }}</span>
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
                    <div class="form-group col-lg-6">
                        <label for="status">Rating <span class="text-danger">*</span></label>
                        <select id="status" class="form-control border-gray-200" v-model="form.rating">
                            <option value="" selected>Select Rating</option>
                            <option v-for="i in 5" :value="i" :key="i">{{ i }}</option>
                        </select>
                        <span class="text-danger" v-if="form.errors.rating">{{ form.errors.rating }}</span>
                    </div>                           
                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <div class="row">
                            <div class="pr-2">
                                <submit-button :disabled="form.processing" :isLoading="form.processing">Submit</submit-button>
                            </div>
                            <div>
                                <Link href="/admin/operator-review" class="btn btn-secondary">Cancel</Link>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import SubmitButton from '@/components/SubmitButton.vue'

const props = defineProps({
    errors: Object,
    review: Object,
    operators: Array
})

const form = useForm({
    operator_id: props.review?.operator_id || "",
    review_text: props.review?.review_text || "",
    source: props.review?.source || "",
    status: props.review?.status ?? 1,
    rating: props.review?.rating ?? '',
})

onMounted(() => {
    const pageTitle = props.review ? "Edit Operator Review" : "Create Operator Review";
    emit.emit('pageName', 'Operator Review Management', [{ title: "Operator Review list", routeName: "admin.operator-review.list" }, { title: pageTitle, routeName: "" }]);
})

function submit() {
    if (props.review) {
        form.post(route("admin.operator-review.edit", props.review.id));
    } else {
        form.post(route("admin.operator-review.create"));
    }
}
</script>