<template>
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">Upload Images</h3>
            </div>
        </div>
        <form @submit.prevent="submit" enctype="multipart/form-data">
            <div class="kt-portlet__body">
                <div class="form-group">
                    <label>Select Images <span class="text-danger">*</span></label>
                    <div class="upload-area" @click="triggerFileInput" @dragover.prevent @drop.prevent="handleDrop">
                        <input type="file" ref="fileInput" @change="handleFileSelect" multiple accept="image/*" style="display: none" />
                        <div class="upload-placeholder" v-if="selectedFiles.length === 0">
                            <Icon icon="material-symbols:cloud-upload" width="48" height="48" color="#5867dd" />
                            <p class="mt-3">Click to upload or drag and drop</p>
                            <p class="mt-1" style="font-size: 12px;">Upload Single or Multiple Files</p>
                            <small class="text-muted">PNG, JPG, GIF, BMP, WEBP (MAX. 10MB each)</small>
                        </div>
                        <div class="preview-grid" v-else>
                            <div class="preview-item" v-for="(file, index) in selectedFiles" :key="index">
                                <img :src="file.preview" :alt="file.name" />
                                <button type="button" @click.stop="removeFile(index)" class="remove-btn">
                                    <Icon icon="material-symbols:close" width="16" height="16" />
                                </button>
                                <div class="file-name">{{ file.name }}</div>
                            </div>
                        </div>
                    </div>
                    <span class="text-danger" v-if="form.errors.images">{{ form.errors.images }}</span>
                </div>
            </div>
            <div class="kt-portlet__foot">
                <div class="kt-form__actions">
                    <button type="submit" class="btn btn-brand" :disabled="form.processing || selectedFiles.length === 0">
                        <span v-if="!form.processing">Upload Images</span>
                        <span v-else>Uploading...</span>
                    </button>
                    <Link :href="route('admin.uploads.index')" class="btn btn-secondary">Cancel</Link>
                </div>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
import { route } from "ziggy-js";
import { onMounted } from "vue";

const fileInput = ref(null);
const selectedFiles = ref([]);

const form = useForm({
    images: [],
});

onMounted(() => {
    emit.emit("pageName", "Content Management", [
        {
            title: "Uploads",
            routeName: "admin.uploads.index",
        },
        {
            title: "Upload Images",
            routeName: "admin.uploads.create",
        },
    ]);
});

const triggerFileInput = () => {
    fileInput.value.click();
};

const handleFileSelect = (event) => {
    const files = Array.from(event.target.files);
    addFiles(files);
};

const handleDrop = (event) => {
    const files = Array.from(event.dataTransfer.files);
    addFiles(files);
};

const addFiles = (files) => {
    files.forEach((file) => {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                selectedFiles.value.push({
                    file: file,
                    name: file.name,
                    preview: e.target.result,
                });
            };
            reader.readAsDataURL(file);
        }
    });
};

const removeFile = (index) => {
    selectedFiles.value.splice(index, 1);
};

const submit = () => {
    form.images = selectedFiles.value.map(f => f.file);
    form.post(route("admin.uploads.create"), {
        onSuccess: () => {
            selectedFiles.value = [];
            form.reset();
        },
    });
};
</script>

<style scoped>
.upload-area {
    border: 2px dashed #e2e5ec;
    border-radius: 8px;
    padding: 40px;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s;
    background: #f7f8fa;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.upload-area:hover {
    border-color: #5867dd;
    background: #f0f3ff;
}

.upload-placeholder {
    color: #646c9a;
}

.upload-placeholder p {
    margin: 0;
    font-size: 16px;
    font-weight: 500;
    color: #48465b;
}

.upload-placeholder p:first-of-type {
    margin-top: 1rem;
}

.upload-placeholder p:nth-of-type(2) {
    margin-top: 0.5rem;
    font-size: 12px;
    color: #74788d;
    font-weight: 400;
}

.upload-placeholder small {
    display: block;
    margin-top: 0.5rem;
    font-size: 11px;
    color: #a1a5b7;
}

.preview-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    width: 100%;
}

.preview-item {
    position: relative;
    border: 1px solid #e2e5ec;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
}

.preview-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    display: block;
}

.remove-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(255, 0, 0, 0.8);
    color: white;
    border: none;
    border-radius: 50%;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.remove-btn:hover {
    background: rgba(255, 0, 0, 1);
}

.file-name {
    padding: 8px;
    font-size: 12px;
    color: #646c9a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    background: #f7f8fa;
}

.kt-form__actions {
    display: flex;
    gap: 10px;
}
</style>
