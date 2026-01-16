<template>
    <div class="kt-portlet kt-portlet--mobile">
        <div class="kt-portlet__body">
            <div id="kt_table_1_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12 col-md-6">
                        <perPageDropdown />
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div id="kt_table_1_filter" class="dataTables_filter">
                            <Link :href="route('admin.uploads.create')"
                                class="btn btn-brand kt-btn btn-sm kt-btn--icon button-fx">
                                <i class="la la-plus"></i>Upload Images
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="upload-gallery">
                            <div class="upload-item" v-for="upload in uploads.data" :key="upload.id">
                                <div class="upload-image-wrapper">
                                    <img :src="upload.file_full_path" :alt="upload.file_name" />
                                    <div class="upload-overlay">
                                        <a :href="upload.file_full_path" target="_blank" 
                                            class="btn btn-sm btn-success" title="View">
                                            <Icon icon="carbon:view" width="20" height="20" color="#fff" />
                                        </a>
                                        <button @click="copyToClipboard(upload.file_full_path)" 
                                            class="btn btn-sm btn-info" title="Copy URL">
                                            <Icon icon="ci:copy" width="20" height="20" color="#fff" />
                                        </button>
                                        <button @click="deleteRecode(upload.id)" 
                                            class="btn btn-sm btn-danger" title="Delete">
                                            <Icon icon="material-symbols-light:delete-rounded" width="20" height="20" color="#fff" />
                                        </button>
                                    </div>
                                </div>
                                <div class="upload-info">
                                    <small class="text-muted">{{ formatDate(upload.created_at) }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12" v-if="uploads.total == 0">
                        <div class="no_data text-center">
                            <h3>No images uploaded yet</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-sm-12 col-md-5">
                    <div class="dataTables_info" role="status" aria-live="polite">
                        Showing {{ uploads.from }} to {{ uploads.to }} of {{ uploads.total }} entries
                    </div>
                </div>
                <div class="col-sm-12 col-md-7">
                    <div class="float-right">
                        <Bootstrap4Pagination :data="uploads" :limit="2"
                            @pagination-change-page="ListHelper.setPageNum" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import ListHelper from "../../../helpers/ListHelper";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import { router } from "@inertiajs/vue3";
import { onMounted, onUnmounted } from "vue";
import perPageDropdown from "@/components/PerpageDropdown.vue";
import { route } from "ziggy-js";

const props = defineProps({
    uploads: Object,
});

onMounted(() => {
    emit.emit("pageName", "Content Management", [
        {
            title: "Uploads",
            routeName: "admin.uploads.index",
        },
    ]);

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });
});

onUnmounted(() => {
    emit.off("deleteConfirm");
});

const deleteRecode = (id) => {
    sw.confirm("deleteConfirm", id);
};

const deleteConfirm = (id) => {
    router.delete(route("admin.uploads.destroy", { id: id }));
};

const copyToClipboard = (url) => {
    navigator.clipboard.writeText(url).then(() => {
        toaster.success("URL copied to clipboard!");
    });
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};
</script>

<style scoped>
.upload-gallery {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 20px;
    padding: 20px 0;
}

.upload-item {
    border: 1px solid #e2e5ec;
    border-radius: 8px;
    overflow: hidden;
    background: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
}

.upload-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.upload-image-wrapper {
    position: relative;
    width: 100%;
    padding-top: 100%;
    overflow: hidden;
    background: #f7f8fa;
}

.upload-image-wrapper img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.upload-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    opacity: 0;
    transition: opacity 0.3s;
}

.upload-image-wrapper:hover .upload-overlay {
    opacity: 1;
}

.upload-info {
    padding: 10px;
    text-align: center;
}

.upload-overlay .btn {
    padding: 8px 12px;
}
</style>
