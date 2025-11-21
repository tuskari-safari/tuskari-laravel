<template>
    <div>
        <Head title="Settings" />
        
        <div class="kt-portlet kt-portlet--mobile">
            <div class="kt-portlet__body">
                <form @submit.prevent="submit">
                    <div class="form-group validated row">
                        <div class="form-group col-lg-6">
                            <label for="first_deposit_percentage">First Deposit Percentage (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" id="first_deposit_percentage" v-model="form.first_deposit_percentage" class="form-control border-gray-200" placeholder="Enter first deposit percentage">
                            <span class="text-danger" v-if="form.errors.first_deposit_percentage">{{ form.errors.first_deposit_percentage }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="auto_balance_duration_days">Auto Balance Duration (Days) <span class="text-danger">*</span></label>
                            <input type="number" min="0" id="auto_balance_duration_days" v-model="form.auto_balance_duration_days" class="form-control border-gray-200" placeholder="Enter auto balance duration in days">
                            <span class="text-danger" v-if="form.errors.auto_balance_duration_days">{{ form.errors.auto_balance_duration_days }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="platform_fee">Platform Fee (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" id="platform_fee" v-model="form.platform_fee" class="form-control border-gray-200" placeholder="Enter platform fee percentage">
                            <span class="text-danger" v-if="form.errors.platform_fee">{{ form.errors.platform_fee }}</span>
                        </div>

                        <div class="form-group col-lg-6">
                            <label for="stripe_processing_fee">Stripe Processing Fee (%) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" max="100" id="stripe_processing_fee" v-model="form.stripe_processing_fee" class="form-control border-gray-200" placeholder="Enter Stripe processing fee percentage">
                            <span class="text-danger" v-if="form.errors.stripe_processing_fee">{{ form.errors.stripe_processing_fee }}</span>
                        </div>
                    </div>
                    <div class="kt-portlet__foot">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="mr-2">
                                    <submit-button :disabled="form.processing" :isLoading="form.processing">Update Settings</submit-button>
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
    setting: Object
});

const form = useForm({
    first_deposit_percentage: props.setting?.first_deposit_percentage || 0,
    auto_balance_duration_days: props.setting?.auto_balance_duration_days || 0,
    platform_fee: props.setting?.platform_fee || 0,
    stripe_processing_fee: props.setting?.stripe_processing_fee || 0,
});

onMounted(() => {
    emit.emit("pageName", "System Settings", [
        {
            title: "Settings",
            routeName: "",
        },
    ]);
});

function submit() {
    form.post(route('admin.settings.update'));
}
</script>