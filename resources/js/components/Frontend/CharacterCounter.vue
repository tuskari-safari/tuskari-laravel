<template>
    <div class="text-end small mt-1">
        <span :class="isLimitReached ? 'text-danger fw-semibold' : 'text-secondary'">
            {{ currentLength }} / {{ maxlength }}
        </span>
        <div v-if="isLimitReached" class="text-danger small mt-1">
            Character limit exceeded. Please shorten your text.
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    text: {
        type: [String, Number],
        default: ''
    },
    maxlength: {
        type: Number,
        required: true
    }
})

const currentLength = computed(() => props.text?.length || 0)
const isLimitReached = computed(() => currentLength.value > props.maxlength)
</script>
