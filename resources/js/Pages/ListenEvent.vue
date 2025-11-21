<template>
    <div>
        <h2>I listen Here</h2>
        <p>{{ message }}</p>
    </div>
</template>
 
<script setup>
import { onMounted, ref } from 'vue';
 
const message = ref("");
onMounted(() => {
    Echo.private(`test`)
        .listen('TestEvent', (e) => {
            console.log(e);
            message.value = e.message;
        });
})
</script>
 
<style scoped></style>