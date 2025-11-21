<template>
    <div class="quill-wrapper">
        <div ref="editorContainer" class="quill-editor" />
    </div>
</template>

<script setup>
import { onMounted, ref, watch, onBeforeUnmount } from 'vue'
import Quill from 'quill'
import 'quill/dist/quill.snow.css'

const props = defineProps({
    modelValue: {
        type: String,
        default: ''
    },
    placeholder: {
        type: String,
        default: 'Write your content here...'
    }
})

const emit = defineEmits(['update:modelValue'])

const editorContainer = ref(null)
let quillInstance = null

onMounted(() => {
    quillInstance = new Quill(editorContainer.value, {
        theme: 'snow',
        placeholder: props.placeholder,
        modules: {
            toolbar: [
                ['bold', 'italic', 'underline', 'strike'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                [{ indent: '-1' }, { indent: '+1' }],
                [{ header: [1, 2, 3, 4, 5, 6, false] }],
                [{ color: [] }, { background: [] }],
                [{ align: [] }],
                ['link', 'image'],
                ['clean']
            ]
        }
    })

    quillInstance.root.innerHTML = props.modelValue || ''

    quillInstance.on('text-change', () => {
        emit('update:modelValue', quillInstance.root.innerHTML)
    })
})

watch(() => props.modelValue, (newVal) => {
    if (quillInstance && newVal !== quillInstance.root.innerHTML) {
        quillInstance.root.innerHTML = newVal || ''
    }
})

onBeforeUnmount(() => {
    quillInstance = null
})
</script>

<style scoped>
.quill-wrapper {
    border: 1px solid #d1d5db86;
    /* tailwind gray-300 */
    border-radius: 5px;
    overflow: hidden;
    background-color: #ffffff;
    transition: box-shadow 0.2s ease;
}
.quill-editor {
    min-height: 200px;
    padding: 12px;
}

/* Optional: Tweak toolbar appearance */
.ql-toolbar {
    border-bottom: 1px solid #d1d5db;
    border-radius: 8px 8px 0 0;
}

.ql-container {
    border-radius: 0 0 8px 8px;
}
</style>
