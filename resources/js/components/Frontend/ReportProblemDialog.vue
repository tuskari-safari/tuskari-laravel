<template>
    <Dialog :visible="isVisible" modal header="Report a problem" @update:visible="val => emit('update:visible', val)"
        :style="{ width: '40rem' }" :draggable="false">
        <div class="p-fluid grid">
            <form @submit.prevent="submitReportIssueForm">
                <div class="col-12 md:col-6">
                    <label for="issueType" class="block mb-1 font-medium mb-2 mt-2">Issue Type: </label>
                    <br>
                    <Dropdown id="issueType" v-model="issueForm.issueType" :options="[
                        { label: 'Inaccurate Information', value: 'inaccurate_info' },
                        { label: 'Pricing Issue', value: 'pricing' },
                        { label: 'Availability Problem', value: 'availability' },
                        { label: 'Other', value: 'other' }
                    ]" optionLabel="label" optionValue="value" placeholder="Select an issue type"
                        class="w-full mb-1" />
                    <span class="text-danger" style="display:flex" v-if="issueForm.errors.issueType">{{
                        issueForm.errors.issueType
                    }}</span>
                </div>
                <div class="col-12 md:col-6">
                    <label for="description" class="block mb-1 font-medium">Describe the issue</label>
                    <Textarea id="description" v-model="issueForm.description" placeholder="Describe the issue..."
                        autoResize rows="4" class="w-full mb-1" />
                    <span class="text-danger " v-if="issueForm.errors.description">{{ issueForm.errors.description
                    }}</span>
                </div>

                <div class="col-12">
                    <FrontendSubmitButton :isLoading="issueForm.processing" :value="'Submit'" />
                </div>
            </form>
        </div>
    </Dialog>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import FrontendSubmitButton from '../FrontendSubmitButton.vue';

const emit = defineEmits(['update:visible']);
const props = defineProps({
    visible: Boolean,
    safariId: Number
});

const issueForm = useForm({
    description: '',
    issueType: '',
    safari_id: props.safariId
});

const isVisible = ref(props.visible);

const submitReportIssueForm = () => {
    issueForm.post(route('frontend.report-issue'), {
        onSuccess: () => {
            emit('update:visible', false);
        }
    });
}
</script>

<style>
.p-select-option:hover {
    background-color: #485748 !important;
    color: #fff !important;
}

.p-select-option-selected {
    background-color: #485748 !important;
    color: #fff !important;
}
</style>
