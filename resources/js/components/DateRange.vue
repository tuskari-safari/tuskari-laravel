<template>
  <VueDatePicker :format="format" placeholder="When are you travelling?" :model-value="date"
    @update:model-value="handleDate" @cleared="clearData" :enable-time-picker="false" range model-auto :week-start="0"
    :disabled-dates="disabledDates" :month-change-on-scroll="false" :alt-position="customPosition"
    :action-row="{ showSelect: true, showCancel: true, showNow: false, showPreview: true }"
    >
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
import { ref, watch } from 'vue'
import VueDatePicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'

// Props and Emits
const props = defineProps({
  modelValue: { type: Array, default: () => [] },
  disabledDates: { type: Array, default: () => [] },
  format: {
    type: Function, default: (date) => {
      if (Array.isArray(date)) {
        const validDates = date.filter(d => d && !isNaN(new Date(d)))
        if (validDates.length === 0) return ''
        const formatted = validDates.map(d => {
          const dt = new Date(d)
          return `${dt.getDate().toString().padStart(2, '0')}/${(dt.getMonth() + 1).toString().padStart(2, '0')}/${dt.getFullYear()}`
        })
        return validDates.length === 1 ? formatted[0] : formatted.join(' - ')
      }
      if (!date || isNaN(new Date(date))) return ''
      const d = new Date(date)
      return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`
    }
  }
})

const emit = defineEmits(['update:modelValue'])

const date = ref(props.modelValue || [])

watch(() => props.modelValue, (val) => {
  date.value = val || []
})

const handleDate = (modelData) => {
  if (Array.isArray(modelData) && modelData.length === 2) {
    const [start, end] = modelData
    if (start && end && new Date(start).toDateString() === new Date(end).toDateString()) {
      alert('Start date and end date cannot be the same. Please select different dates.')
      date.value = [start]
      emit('update:modelValue', [start])
      return
    }
  }
  date.value = modelData
  emit('update:modelValue', date.value)
}

const clearData = () => {
  date.value = null
  emit('update:modelValue', [])
}
const customPosition = (el) => ({ top: 0, left: 0 });
</script>

<style scoped>


:deep(.dp__arrow_top) {
  margin-top: 15px;
}

:deep(.dp__menu) {
  margin-top: 15px;
}
</style>