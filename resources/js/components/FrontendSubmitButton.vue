<template>
<div>
   <button v-bind="$attrs" type="submit" class="cmn-butn" :disabled="isLoading">
            <template v-if="isLoading">
                <div style="display:flex;">
                   <semipolar-spinner  :animation-duration="2000" :size="20" color="#fff" /> <span class="ml-3"><slot> {{ value }}</slot></span>
                </div>
            </template>
        <template v-else>
            <slot>{{ value }}</slot>
        </template>
</button>
</div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { AtomSpinner,SemipolarSpinner } from 'epic-spinners'

defineProps({
    isLoading: {
        type: Boolean,
        default: false
    },
    value: {
        type: String,
        default: 'Submit'
    }
})

</script>

<style>

</style>