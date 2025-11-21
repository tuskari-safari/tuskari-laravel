<template>
    <div>
        <Teleport to="head">
            <Head title="Test file"></Head>
        </Teleport>
        This is test file
            <input v-model="text" type="text" name="" id="">
            <button type="button" @click="sendEvent">Send</button>
    </div>
</template>
 
<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3'
 
    const text = ref(null);
    const sendEvent = () => {
        router.post('send-typing', {
            data: text.value
        })
    }
</script>
 
<style scoped>
 
</style>