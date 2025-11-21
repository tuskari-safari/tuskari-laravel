<template>
    <div>
        <Head title="Something Didn't Work - View" />
        <div class="issue-container">
            <div class="issue-header">
                <div class="kt-portlet__head-label">
                    <h3 class="issue-title">
                        <i class="la la-bug issue-icon"></i>
                        Issue Report Details
                    </h3>
                </div>
                <div class="kt-portlet__head-toolbar">
                    <Link :href="route('admin.something-did-not-work.index')" class="back-btn">
                        <i class="la la-arrow-left"></i> Back
                    </Link>
                </div>
            </div>
            <div class="issue-body">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="info-card">
                            <h4 class="card-title"><i class="la la-user"></i> User Information</h4>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Name</label>
                                        <p class="field-value">{{issue.user?.full_name ?? 'N/A'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Email</label>
                                        <p class="field-value">{{issue.user?.email ?? 'N/A'}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Issue Type</label>
                                        <p class="field-value">{{issue.issue_type ?? 'General'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Reported On</label>
                                        <p class="field-value">{{ formatDate(issue.created_at) }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Page URL</label>
                                        <p class="field-value url-break">{{issue.page_url ?? 'N/A'}}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="field-group">
                                        <label class="field-label">Device Info</label>
                                        <p class="field-value">{{issue.device_info ?? 'N/A'}}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="field-label">Issue Description</label>
                                <div class="description-box">
                                    <p class="description-text">{{issue.issue_description ?? 'N/A'}}</p>
                                </div>
                            </div>
                            <div v-if="issue.screenshot" class="screenshot-section">
                                <label class="field-label">Screenshot</label>
                                <div class="screenshot-container">
                                    <img :src="issue.screenshot_full_url" alt="Screenshot" class="screenshot-img" style="width: 300px;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="status-card">
                            <h4 class="card-title"><i class="la la-cog"></i> Issue Status</h4>
                            <div class="status-badge-container">
                                <div class="status-badge"
                                    :class="issue.resolve == 1 ? 'status-resolved' : 'status-pending'">
                                    <i :class="issue.resolve == 1 ? 'la la-check-circle' : 'la la-clock-o'" class="status-icon"></i>
                                    {{ issue.resolve == 1 ? 'RESOLVED' : 'PENDING' }}
                                </div>
                            </div>
                            <button @click="changeStatus(issue.id)" 
                                class="action-btn"
                                :class="issue.resolve == 1 ? 'btn-pending' : 'btn-resolve'">
                                <i :class="issue.resolve == 1 ? 'la la-undo' : 'la la-check'" class="btn-icon"></i>
                                {{ issue.resolve == 1 ? 'Mark as Pending' : 'Mark as Resolved' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import { onMounted, onUnmounted } from 'vue'

const props = defineProps({
    issue: Object
})

onMounted(() => {
    emit.emit('pageName', 'Customer Support Management', 
    [
        { title: 'Something Didn\'t Work Reports', routeName: 'admin.something-did-not-work.index' },
        { title: 'View Report', routeName: null }
    ])

    emit.on('changeStatusConfirm', function (arg1) {
        changeStatusConfirm(arg1)
    })
})

onUnmounted(() => {
    emit.off('changeStatusConfirm')
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}

const changeStatus = id => {
    sw.confirm('changeStatusConfirm', id, 'Are you sure?', 'You want to change the status!', 'Yes, Change it!')
}

const changeStatusConfirm = (id) => {
    let data = {
        id: id,
    };
    router.post(route("admin.something-did-not-work.status"), data);
};
</script>

<style scoped>
.issue-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    color: #495057;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    min-height: 100vh;
    padding: 2rem;
}

.issue-header {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    border-radius: 15px;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.issue-header:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.issue-title {
    color: #495057;
    font-weight: 600;
    margin: 0;
    font-size: 1.5rem;
}

.issue-icon {
    color: #2f3a2f;
    margin-right: 10px;
}

.back-btn {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border-radius: 8px;
    padding: 10px 20px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}
.back-btn:hover {
    background: linear-gradient(135deg, #4a5a4a 0%, #2f3a2f 100%) !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(47, 58, 47, 0.4);
}

.issue-body {
    padding: 30px;
}

.info-card, .status-card {
    background: #ffffff;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.info-card:hover, .status-card:hover {
    transform: translateY(-2px);
}

.info-card {
    margin-bottom: 20px;
}

.status-card {
    text-align: center;
}

.card-title {
    color: #2f3a2f;
    margin-bottom: 20px;
    font-weight: 600;
    font-size: 1.3rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

.field-group {
    margin-bottom: 15px;
}

.field-label {
    color: #6c757d;
    font-size: 12px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.field-value {
    color: #2f3a2f;
    font-size: 16px;
    margin: 5px 0;
    font-weight: 500;
}

.url-break {
    word-break: break-all;
}

.description-box {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 15px;
    margin-top: 8px;
    border-left: 4px solid #2f3a2f;
}

.description-text {
    color: #495057;
    margin: 0;
    line-height: 1.6;
}

.screenshot-section {
    margin-top: 20px;
}

.screenshot-container {
    margin-top: 10px;
}

.screenshot-img {
    max-width: 100%;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.screenshot-img:hover {
    transform: scale(1.05);
}

.status-badge-container {
    margin-bottom: 25px;
}

.status-badge {
    display: inline-block;
    padding: 12px 20px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 14px;
    color: white;
}

.status-resolved {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.status-pending {
    background: linear-gradient(135deg, #ffc107 0%, #f39c12 100%);
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
}

.status-icon {
    margin-right: 8px;
}

.action-btn {
    width: 100%;
    border-radius: 25px;
    padding: 12px 30px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    color: white;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none !important;
    outline: none !important;
}

.action-btn:hover,
.action-btn:focus {
    border: none;
    outline: none;
}

.btn-resolve {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.btn-resolve:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(40, 167, 69, 0.4);
}

.btn-pending {
    background: linear-gradient(135deg, #ffc107 0%, #f39c12 100%);
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
}

.btn-pending:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255, 193, 7, 0.4);
}

.btn-icon {
    margin-right: 8px;
}
.kt-content button:hover{
    box-shadow: none !important;
}
</style>