<template>
  <VueDatePicker :format="format" :model-value="date" @update:model-value="handleDate" @cleared="clearData"
    :enable-time-picker="false" :min-date="props.minDate" :max-date="props.maxDate" :start-date="props.startDate"
    :disabled-dates="props.disabledDates" auto-apply :disabled="isDisabled" :month-change-on-scroll="false"
    :alt-position="customPosition">
    <template #month-year="{ month, year, updateMonthYear, months, years }">
      <div class="custom-calender">
        <span class="month-year-label">
          {{ new Date(year, month).toLocaleString('en-US', { month: 'long', year: 'numeric' }) }}
        </span>

        <div class="clnder-arw-butnbox">
          <button type="button" class="nav-btn" @click="updateMonthYear(month - 1, year)">
            <img :src="'/frontend_assets/images/ftslick-arwlft.svg'" alt="ftslick-arwlft">
          </button>
          <button type="button" class="nav-btn" @click="updateMonthYear(month + 1, year)">
            <img :src="'/frontend_assets/images/ftslick-arwrgt.svg'" alt="ftslick-arwrgt">
          </button>
        </div>
      </div>
    </template>
  </VueDatePicker>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';
import { usePage } from '@inertiajs/vue3';

// Props
const props = defineProps({
  modelValue: {
    type: [String, Object],
    default: null
  },

  minDate: Date,
  maxDate: Date,
  startDate: Date,
  disabledDates: { type: Array, default: () => [] },
});

const emit = defineEmits(['update:modelValue']);
const isDisabled = ref(false);
// Date model
const date = ref(props.modelValue);

// Format
const adminFormat = computed(() => usePage().props.dateFormat);
const format = ref('dd MMM yyyy');


onMounted(() => {
  if (adminFormat.value === 'DD/MM/YYYY') format.value = 'dd/MM/yyyy';
  else if (adminFormat.value === 'MM/DD/YYYY') format.value = 'MM/dd/yyyy';
  else if (adminFormat.value === 'YYYY/MM/DD') format.value = 'yyyy/MM/dd';
});

// Handlers
const handleDate = (modelData) => {
  if (modelData) {
    date.value = moment(modelData).format('YYYY-MM-DD');
    emit('update:modelValue', date.value);
  }
};

const clearData = () => {
  emit('update:modelValue', '');
};

defineExpose({ isDisabled });
const customPosition = (el) => ({ top: 0, left: 0 });
</script>


<style scoped>
:deep(.dp__action_row) {
  display: none !important;
}

:deep(.dp__arrow_top) {
  margin-top: 15px;
}

:deep(.dp__menu) {
  margin-top: 15px;
}
</style>
