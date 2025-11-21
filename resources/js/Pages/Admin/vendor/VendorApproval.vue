<template>
    <div class="vendor-page">

        <Head title="Vendor Details & Approval" />

        <!-- Header -->
        <div class="page-header">
            <div class="header-content">
                <div class="vendor-info">
                    <h2 class="vendor-name">{{ vendor?.full_name || 'Vendor Details' }}</h2>
                    <p class="vendor-subtitle">Complete vendor information and approval management</p>
                </div>
                <div class="status-badges">
                    <span class="status-badge" :class="getStatusClass(vendor?.active)">
                        <i class="fas fa-circle"></i> {{ vendor?.active == 1 ? 'Active' : 'Inactive' }}
                    </span>
                    <span class="approval-badge" :class="getApprovalClass(vendor?.is_approved)">
                        <i class="fas fa-check-circle"></i> {{ getApprovalText(vendor?.is_approved) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <!-- Vendor Details Card -->
            <div class="details-card">
                <div class="card-header">
                    <h3><i class="fas fa-user"></i> Personal Information</h3>
                </div>
                <div class="card-body">
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="label">Full Name</span>
                            <span class="value">{{ vendor?.full_name || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Email</span>
                            <span class="value">{{ vendor?.email || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Phone</span>
                            <span class="value">{{ vendor?.country_code ? '+' + vendor?.country_code + ' ' : '' }} {{
                                vendor?.phone || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Date of Birth</span>
                            <span class="value">{{ formatDate(vendor?.dob) || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Instagram Link</span>
                            <span class="value">{{ vendor?.instagram_link || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Website Link</span>
                            <span class="value">{{ vendor?.website_link || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Business Name</span>
                            <span class="value">{{ vendor?.business_name || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Business type</span>
                            <span class="value">{{ vendor?.business_type || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Country of Operation</span>
                            <span class="value">{{ vendor?.country?.name || 'N/A' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="label">Address</span>
                            <span class="value">{{ vendor?.address || 'N/A' }}</span>
                        </div>
                        <div class="info-item full-width">
                            <span class="label">Main Destination</span>
                            <span class="value">{{ vendor?.main_destination ? vendor?.main_destination.join(', ') :
                                'N/A' }}</span>
                        </div>

                        <div class="info-item full-width">
                            <span class="label">About Operation</span>
                            <span class="value">{{ vendor?.about_operation || 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Documents Card -->
            <div class="documents-card">
                <div class="card-header">
                    <h3><i class="fas fa-folder-open"></i> Required Documents</h3>
                </div>
                <div class="card-body">
                    <div class="documents-grid">
                        <!-- Company Registration -->
                        <div class="document-item">
                            <div class="document-header">
                                <div class="document-icon company">
                                    <i class="fas fa-building"></i>
                                </div>
                                <div class="document-info">
                                    <h4>Company Registration</h4>
                                    <p>Certificate of incorporation</p>
                                </div>
                            </div>
                            <div class="document-content">
                                <div v-if="vendor?.registration_pdf_url" class="document-available">
                                    <a :href="vendor?.registration_pdf_url" target="_blank" class="btn-view-doc">
                                        <i class="fas fa-eye"></i> View Document
                                    </a>
                                </div>
                                <div v-else class="document-missing">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Not uploaded</span>
                                </div>
                            </div>
                        </div>

                        <!-- Tourism License -->
                        <div class="document-item">
                            <div class="document-header">
                                <div class="document-icon license">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="document-info">
                                    <h4>Tourism License</h4>
                                    <p>Operating license certificate</p>
                                </div>
                            </div>
                            <div class="document-content">
                                <div v-if="vendor?.license_pdf_url" class="document-available">
                                    <a :href="vendor?.license_pdf_url" target="_blank" class="btn-view-doc">
                                        <i class="fas fa-eye"></i> View Document
                                    </a>
                                </div>
                                <div v-else class="document-missing">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Not uploaded</span>
                                </div>
                            </div>
                        </div>

                        <!-- Insurance -->
                        <div class="document-item">
                            <div class="document-header">
                                <div class="document-icon insurance">
                                    <i class="fas fa-shield-alt"></i>
                                </div>
                                <div class="document-info">
                                    <h4>Insurance Policy</h4>
                                    <p>Liability insurance certificate</p>
                                </div>
                            </div>
                            <div class="document-content">
                                <div v-if="vendor?.insurance_pdf_url" class="document-available">
                                    <a :href="vendor?.insurance_pdf_url" target="_blank" class="btn-view-doc">
                                        <i class="fas fa-eye"></i> View Document
                                    </a>
                                </div>
                                <div v-else class="document-missing">
                                    <i class="fas fa-times-circle"></i>
                                    <span>Not uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Approval Card -->
            <div class="approval-card">
                <div class="card-header">
                    <h3><i class="fas fa-gavel"></i> Approval Management</h3>
                </div>
                <div class="card-body">
                    <form @submit.prevent="submit" class="approval-form">
                        <div class="form-group">
                            <label for="approval-status">Approval Status</label>
                            <select v-model="form.is_approved" id="approval-status" class="form-select">
                                <option value="">Select Status</option>
                                <option value="0" :disabled="vendor?.is_approved == 0">Pending Review</option>
                                <option value="1" :disabled="vendor?.is_approved == 1">Approved</option>
                                <option value="2" :disabled="vendor?.is_approved == 2">Rejected</option>
                            </select>
                            <div v-if="form.errors.is_approved" class="error-message">
                                {{ form.errors.is_approved }}
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn-primary" :disabled="form.processing">
                                <i class="fas fa-save"></i>
                                <span v-if="form.processing">Updating...</span>
                                <span v-else>Update Status</span>
                            </button>
                            <Link :href="route('admin.vendors')" class="btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                            </Link>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import ListHelper from "../../../helpers/ListHelper";

const props = defineProps({
    errors: Object,
    vendor: Object,
});

const form = useForm({
    id: props.vendor?.id,
    is_approved: props.vendor?.is_approved || 0,
});

const formatDate = (date) => {
    return date ? ListHelper.dateFormat(date, "MMM DD, YYYY") : null;
};

const getStatusClass = (status) => {
    return status == 1 ? 'active' : 'inactive';
};

const getApprovalClass = (status) => {
    switch (status) {
        case 1: return 'approved';
        case 2: return 'rejected';
        default: return 'pending';
    }
};

const getApprovalText = (status) => {
    switch (status) {
        case 1: return 'Approved';
        case 2: return 'Rejected';
        default: return 'Pending';
    }
};

const submit = () => {
    form.post(route("admin.vendorApproval", props.vendor?.id), {
        preserveScroll: true,
    });
};

onMounted(() => {
    emit.emit("pageName", "User Management", [
        {
            title: "Vendor List",
            routeName: "admin.vendors",
        },
        {
            title: "Vendor Details & Approval",
            routeName: "",
        },
    ]);
});
</script>

<style scoped>
.vendor-page {
    padding: 2rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
}

.page-header {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    border-radius: 15px;
    padding: 35px;
    margin-bottom: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.1);
    transition: transform 0.3s ease;
}

.page-header:hover {
    transform: translateY(-2px);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 20px;
}

.vendor-name {
    color: #495057;
    font-size: 2.2rem;
    font-weight: 700;
    margin: 0;
    letter-spacing: -0.5px;
}

.vendor-subtitle {
    color: #6c757d;
    margin: 8px 0 0 0;
    font-size: 1.15rem;
    font-weight: 400;
}

.status-badges {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.status-badge,
.approval-badge {
    padding: 8px 16px;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.status-badge.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.status-badge.inactive {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(245, 87, 108, 0.3);
}

.approval-badge.approved {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.approval-badge.rejected {
    background: linear-gradient(135deg, #dc3545 0%, #e74c3c 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
}

.approval-badge.pending {
    background: linear-gradient(135deg, #ffc107 0%, #f39c12 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(255, 193, 7, 0.3);
}

.content-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 25px;
    grid-template-areas:
        "details documents"
        "details approval";
}

.details-card {
    grid-area: details;
}

.documents-card {
    grid-area: documents;
}

.approval-card {
    grid-area: approval;
}

.details-card,
.documents-card,
.approval-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.documents-card {
    min-height: fit-content;
}

.details-card:hover,
.documents-card:hover,
.approval-card:hover {
    transform: translateY(-2px);
}

.card-header {
    background: linear-gradient(135deg, #e2e4e6 0%, #ccd0d4 100%);
    color: #495057;
    padding: 20px 25px;
    border: none;
    transition: all 0.3s ease;
}

.card-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.card-header h3 {
    margin: 0;
    font-size: 1.3rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
}

.card-body {
    padding: 25px;
}

.info-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.info-item {
    display: flex;
    flex-direction: column;
    gap: 8px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #2f3a2f;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.info-item.full-width {
    grid-column: 1 / -1;
}

.label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #7f8c8d;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 500;
    word-break: break-word;
}

.documents-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 20px;
    padding-bottom: 10px;
}

.document-item {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 25px;
    border-left: 4px solid #2f3a2f;
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 130px;
    margin-bottom: 5px;
}

.document-item:hover {
    background: #e9ecef;
    transform: translateX(5px);
}

.document-header {
    display: flex;
    align-items: center;
    gap: 15px;
    margin-bottom: 12px;
    flex: 1;
}

.document-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.2rem;
}

.document-icon.company {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.document-icon.license {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.document-icon.insurance {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.document-info h4 {
    margin: 0 0 5px 0;
    color: #2c3e50;
    font-size: 1.1rem;
    font-weight: 600;
}

.document-info p {
    margin: 0;
    color: #7f8c8d;
    font-size: 0.9rem;
}

.document-content {
    display: flex;
    justify-content: flex-end;
    align-items: center;
    margin-top: auto;
}

.btn-view-doc {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

.btn-view-doc:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(47, 58, 47, 0.4);
    color: white !important;
}

.document-missing {
    display: flex;
    align-items: center;
    gap: 8px;
    color: #e74c3c;
    font-weight: 500;
    font-size: 0.9rem;
}

.document-missing i {
    font-size: 1rem;
}

.approval-form {
    max-width: 500px;
    padding: 10px 0;
}

.form-group {
    margin-bottom: 25px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 1rem;
}

.form-select {
    width: 65%;
    padding: 12px 15px;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-select:focus {
    outline: none;
}

.error-message {
    color: #e74c3c;
    font-size: 1rem;
    margin-top: 5px;
    font-weight: 600;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
    align-items: center;
}

.btn-primary,
.btn-secondary,
.btn-tertiary {
    padding: 12px 25px;
    border-radius: 25px;
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
    min-width: 140px;
    text-align: center;
}

.btn-primary {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white !;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

.btn-primary:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(47, 58, 47, 0.4);
}

.btn-primary:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-secondary {
    box-shadow: 0 2px 6px rgba(108, 117, 125, 0.3);
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(108, 117, 125, 0.4);
    color: white;
}

.btn-tertiary {
    background: linear-gradient(135deg, #27ae60 0%, #2ecc71 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);
}

.btn-tertiary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(39, 174, 96, 0.4);
    color: white;
}

@media (max-width: 768px) {
    .vendor-page {
        padding: 15px;
    }

    .header-content {
        flex-direction: column;
        text-align: center;
    }

    .vendor-name {
        font-size: 1.5rem;
    }

    .content-grid {
        grid-template-columns: 1fr;
        grid-template-areas:
            "details"
            "documents"
            "approval";
    }

    .info-grid {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: row;
        gap: 10px;
        justify-content: space-between;
    }

    .btn-primary,
    .btn-secondary,
    .btn-tertiary {
        flex: 1;
        min-width: 120px;
        padding: 12px 16px;
        font-size: 0.9rem;
    }

    .form-select {
        width: 100%;
    }

    .approval-form {
        max-width: 100%;
    }
}

@media (max-width: 480px) {
    .form-actions {
        flex-direction: column;
        gap: 12px;
    }

    .btn-primary,
    .btn-secondary,
    .btn-tertiary {
        width: 100%;
        flex: none;
        padding: 14px 20px;
        font-size: 1rem;
    }

    .vendor-name {
        font-size: 1.3rem;
    }

    .card-body {
        padding: 20px;
    }
}
</style>