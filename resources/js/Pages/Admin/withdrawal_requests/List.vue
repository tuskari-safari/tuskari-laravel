<template>
    <div class="withdrawal-details-container">
        <div class="withdrawal-content">
            <!-- Sticky Header Actions -->
            <div class="header-actions sticky-header">
                <Link :href="route('admin.vendors')" class="back-button">
                <i class="fas fa-arrow-left"></i>
                Back to Vendors
                </Link>
            </div>

            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-text">
                        <h2>Withdrawal Requests Management</h2>
                        <p>Manage vendor withdrawal requests and bank transfers</p>
                    </div>
                </div>
            </div>
            <div class="filter-section">
                <div class="filter-tabs">
                    <button 
                        v-for="status in statusOptions" 
                        :key="status.value"
                        @click="selectedStatus = status.value"
                        :class="['filter-tab', { 'active': selectedStatus === status.value }]"
                    >
                        <i :class="status.icon"></i>
                        {{ status.label }}
                        <span class="count">{{ getStatusCount(status.value) }}</span>
                    </button>
                </div>
            </div>

            <div v-for="request in filteredRequests" :key="request.id" class="section-card">
                <div class="section-header" @click="toggleCard(request.id)">
                    <div class="section-title">
                        <div class="accordion-toggle">
                            <i :class="expandedCards[request.id] ? 'fas fa-minus-circle' : 'fas fa-plus-circle'"
                                class="toggle-icon"></i>
                        </div>
                        <h2>
                            <div class="profile-photo">
                                <img :src="request.operator?.profile_photo_url || '/admin_assets/images/default-avatar.png'"
                                    :alt="request.operator?.full_name" class="profile-image" />
                            </div>
                            {{ request.operator?.full_name || 'N/A' }} - <span class="business-name">{{
                                request.operator?.business_name || 'N/A' }}</span>
                        </h2>
                        <div class="badge-group">
                            <div class="amount-badge">
                                ${{ Number(request.amount).toLocaleString('en-GB', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) }}
                            </div>
                            <div class="section-badge" :class="getStatusClass(request.status)">
                                {{ request.status }}
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="expandedCards[request.id]" class="section-content">
                    <div class="amount-section">
                        <div class="amount">
                            <span class="label">Amount</span>
                            <span class="value">${{ Number(request.amount).toLocaleString('en-GB', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }) }}</span>
                        </div>
                        <div class="date">
                            <span class="label">Requested Date</span>
                            <span class="value">{{ formatDate(request.requested_at) }}</span>
                        </div>
                    </div>
                    <div class="wallet-details">
                        <h4>Wallet Information</h4>
                        <div class="wallet-info">
                            <div class="wallet-item">
                                <span class="label">Available Balance</span>
                                <span class="value">${{ Number(request.wallet?.available_amount || 0).toLocaleString('en-GB', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) }}</span>
                            </div>
                            <div class="wallet-item">
                                <span class="label">Pending Balance</span>
                                <span class="value">${{ Number(request.wallet?.pending_amount || 0).toLocaleString('en-GB', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) }}</span>
                            </div>
                            <div class="wallet-item">
                                <span class="label">Total Earned</span>
                                <span class="value">${{ Number(request.wallet?.total_earned || 0).toLocaleString('en-GB', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) }}</span>
                            </div>
                            <div class="wallet-item">
                                <span class="label">Total Withdrawn</span>
                                <span class="value">${{ Number(request.wallet?.total_withdrawn || 0).toLocaleString('en-GB', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }) }}</span>
                            </div>
                            <div class="wallet-item">
                                <span class="label">Last Transfer Date</span>
                                <span class="value">{{ formatDate(request.wallet?.last_transfer_date) }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bank-details">
                        <h4>Bank Details</h4>
                        <div class="bank-info">
                            <div class="bank-item">
                                <span class="label">Bank Name</span>
                                <span class="value">{{ request.operator_bank?.bank_name || 'N/A' }}</span>
                            </div>
                            <div class="bank-item">
                                <span class="label">Account Number</span>
                                <span class="value">{{ request.operator_bank?.account_number || 'N/A' }}</span>
                            </div>
                            <div class="bank-item">
                                <span class="label">Account Holder</span>
                                <span class="value">{{ request.operator_bank?.account_holder_name || 'N/A' }}</span>
                            </div>
                            <div class="bank-item">
                                <span class="label">IFSC Code</span>
                                <span class="value">{{ request.operator_bank?.ifsc_code || 'N/A' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-section" v-if="forms[request.id]">
                        <form @submit.prevent="updateStatus(request.id)" class="status-form">
                            <div class="form-row">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select v-model="forms[request.id].status" class="form-select" :disabled="forms[request.id].status !=='pending'">
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="processed">Processed</option>
                                        <option value="rejected">Rejected</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Admin Notes</label>
                                    <textarea v-model="forms[request.id].admin_notes" class="form-textarea"
                                        placeholder="Add notes about the transfer..."></textarea>
                                </div>
                            </div>
                            <button type="submit" class="btn-update" :disabled="forms[request.id].processing">
                                <i class="fas fa-save"></i>
                                {{ forms[request.id].processing ? 'Updating...' : 'Update Status' }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div v-if="filteredRequests.length === 0" class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No {{ selectedStatus === 'all' ? '' : selectedStatus }} withdrawal requests found</h3>
                <p>There are no {{ selectedStatus === 'all' ? '' : selectedStatus }} withdrawal requests to display at the moment.</p>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ListHelper from '../../../helpers/ListHelper';

const props = defineProps({
    withdrawalRequests: Object,
    wallet: Object
});

const forms = ref({});
const expandedCards = ref({});


const selectedStatus = ref('pending');

const statusOptions = [
    { value: 'all', label: 'All', icon: 'fas fa-list' },
    { value: 'pending', label: 'Pending', icon: 'fas fa-clock' },
    { value: 'approved', label: 'Approved', icon: 'fas fa-check' },
    { value: 'processed', label: 'Processed', icon: 'fas fa-check-double' },
    { value: 'rejected', label: 'Rejected', icon: 'fas fa-times' }
];

// Computed filtered list
const filteredRequests = computed(() => {
    if (selectedStatus.value === 'all') {
        return props.withdrawalRequests.data;
    }
    return props.withdrawalRequests.data.filter(
        (req) => req.status === selectedStatus.value
    );
});

const getStatusCount = (status) => {
    if (status === 'all') {
        return props.withdrawalRequests.data.length;
    }
    return props.withdrawalRequests.data.filter(req => req.status === status).length;
};


// Initialize forms for each request
onMounted(() => {
    props.withdrawalRequests.data.forEach(request => {
        forms.value[request.id] = useForm({
            status: request.status,
            admin_notes: request.admin_notes || ''
        });
        expandedCards.value[request.id] = false;

    });

    emit.emit("pageName", "Financial Management", [
        {
            title: "Vendor Management",
            routeName: "admin.vendors",
        },
        {
            title: "Withdrawal Requests",
            routeName: "admin.withdrawal-requests.index",
        },
    ]);
});

const toggleCard = (requestId) => {
    expandedCards.value[requestId] = !expandedCards.value[requestId];
};



const formatDate = (date) => {
    return date ? ListHelper.dateFormat(date, "MMM DD, YYYY") : 'N/A';
};

const getStatusClass = (status) => {
    const classes = {
        pending: 'pending',
        approved: 'approved',
        processed: 'processed',
        rejected: 'rejected'
    };
    return classes[status] || 'pending';
};

const updateStatus = (requestId) => {
    forms.value[requestId].post(route('admin.withdrawal-requests.update-status', requestId), {
        preserveScroll: true,
        onSuccess: () => {
            // Form will be reset automatically by Inertia
        }
    });
};
</script>

<style scoped>
.withdrawal-details-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
    padding: 2rem;
}

.withdrawal-content {
    max-width: 1400px;
    margin: 0 auto;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    color: #495057;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    transition: transform 0.3s ease;
}

.page-header:hover {
    transform: translateY(-2px);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}

.header-text {
    text-align: left;
}

.header-text h2 {
    margin: 0 0 10px 0;
    font-size: 2rem;
    font-weight: 700;
}

.header-text p {
    margin: 0;
    opacity: 0.8;
}

/* Filter Section */
.filter-section {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 25px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
}

.filter-tabs {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filter-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    border: 2px solid #e9ecef;
    border-radius: 25px;
    background: white;
    color: #6c757d;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
}

.filter-tab:hover {
    border-color: #2f3a2f;
    color: #2f3a2f;
    box-shadow: 0 4px 8px rgba(47, 58, 47, 0.15);
}

.filter-tab.active {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border-color: #2f3a2f;
    box-shadow: 0 4px 12px rgba(47, 58, 47, 0.3);
}

.filter-tab i {
    font-size: 0.9rem;
}

.filter-tab .count {
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
}

.filter-tab.active .count {
    background: rgba(255, 255, 255, 0.3);
}

.filter-tab:not(.active) .count {
    background: #f8f9fa;
    color: #6c757d;
}

/* Header Actions */
.header-actions {
    margin-bottom: 15px;
    display: flex;
    justify-content: flex-end;
}



.back-button {
    display: inline-flex;
    align-items: center;
    gap: 0.75rem;
    color: #495057;
    text-decoration: none;
    font-weight: 600;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    padding: 0.6rem 1.1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
    cursor: pointer;
    font-size: 1rem;
}

.back-button:hover {
    color: #2f3a2f;
    background: #e9ecef;
    border-color: #2f3a2f;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

/* Section Cards */
.section-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 2rem;
    overflow: hidden;
    transition: transform 0.3s ease;
}

.section-card:hover {
    transform: translateY(-2px);
}

.section-header {
    padding: 1.5rem 2rem;
    background: linear-gradient(135deg, #e2e4e6 0%, #ccd0d4 100%);
    cursor: pointer;
    border-bottom: 1px solid #dee2e6;
    transition: all 0.3s ease;
    position: relative;
}

.section-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.section-title {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    gap: 1rem;
}

.section-title h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex: 1;
}

.business-name {
    font-size: 0.9rem;
    opacity: 0.8;
}

.profile-photo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #dee2e6;
    flex-shrink: 0;
}

.profile-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.section-icon {
    color: #2f3a2f;
    font-size: 1.3rem;
}

.accordion-toggle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.accordion-toggle:hover {
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(47, 58, 47, 0.4);
}

.toggle-icon {
    color: white;
    font-size: 1.1rem;
    transition: all 0.3s ease;
}

.badge-group {
    display: flex;
    align-items: center;
    gap: 10px;
}

.amount-badge {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    padding: 0.5rem 1.2rem;
    border-radius: 25px;
    font-size: 1rem;
    font-weight: 700;
    white-space: nowrap;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    border: 2px solid rgba(255, 255, 255, 0.2);
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.2);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }

    50% {
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.6);
        transform: scale(1.02);
    }

    100% {
        box-shadow: 0 4px 12px rgba(40, 167, 69, 0.4);
    }
}

.section-badge {
    padding: 0.4rem 1rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
    white-space: nowrap;
    text-transform: uppercase;
}

.section-badge.pending {
    background: #f39c12;
    color: white;
    box-shadow: 0 2px 6px rgba(243, 156, 18, 0.3);
}

.section-badge.approved {
    background: #3498db;
    color: white;
    box-shadow: 0 2px 6px rgba(52, 152, 219, 0.3);
}

.section-badge.processed {
    background: #27ae60;
    color: white;
    box-shadow: 0 2px 6px rgba(39, 174, 96, 0.3);
}

.section-badge.rejected {
    background: #e74c3c;
    color: white;
    box-shadow: 0 2px 6px rgba(231, 76, 60, 0.3);
}

.section-content {
    padding: 2rem;
}

.amount-section {
    display: flex;
    gap: 30px;
    margin-bottom: 25px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.amount,
.date {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.amount .value {
    font-size: 1.5rem;
    font-weight: 700;
    color: #27ae60;
}

.wallet-details {
    margin-bottom: 25px;
}

.wallet-details h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 1.2rem;
}

.wallet-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.wallet-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.wallet-item:nth-child(1) .value { color: #19b03d; font-weight: 700; }
.wallet-item:nth-child(2) .value { color: #e86c07; font-weight: 700; }
.wallet-item:nth-child(3) .value { color: #09aec7; font-weight: 700; }
.wallet-item:nth-child(4) .value { color: #c31f30; font-weight: 700; }

.bank-details {
    margin-bottom: 25px;
}

.bank-details h4 {
    margin: 0 0 15px 0;
    color: #2c3e50;
    font-size: 1.2rem;
}

.bank-info {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

.bank-item {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.label {
    font-size: 0.85rem;
    font-weight: 600;
    color: #7f8c8d;
    text-transform: uppercase;
}

.value {
    font-size: 1rem;
    color: #2c3e50;
    font-weight: 500;
}

.admin-section {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 200px 1fr;
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #2c3e50;
    font-size: 0.9rem;
}

.form-select,
.form-textarea {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 0.9rem;
}

.form-textarea {
    resize: vertical;
    min-height: 80px;
}

.btn-update {
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 20px;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s ease;
    box-shadow: 0 2px 6px rgba(47, 58, 47, 0.3);
}

.btn-update:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(47, 58, 47, 0.4);
}

.btn-update:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

/* Responsive */
@media (max-width: 768px) {
    .withdrawal-details-container {
        padding: 1rem;
    }

    .header-content {
        text-align: center;
    }

    .header-text {
        text-align: center;
    }

    .header-actions {
        justify-content: center;
    }

    .filter-tabs {
        justify-content: center;
    }

    .filter-tab {
        padding: 10px 16px;
        font-size: 13px;
    }

    .amount-section {
        flex-direction: column;
        gap: 15px;
    }

    .wallet-info,
    .bank-info {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>