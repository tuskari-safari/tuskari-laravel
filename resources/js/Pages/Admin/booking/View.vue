<template>
    <div class="booking-view-page">
        <!-- Back Button Header -->
        <div class="page-header">
            <div class="page-header-content">
                <h3 class="page-title">
                    <i class="la la-calendar-check-o page-icon"></i>
                    Booking Details
                </h3>
            </div>
            <div class="page-header-toolbar">
                <Link :href="route('admin.booking.index')" class="back-btn">
                    <i class="la la-arrow-left"></i> Back
                </Link>
            </div>
        </div>

        <!-- Header Section -->
        <div class="booking-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="booking-title mb-2">
                        {{ booking?.safari?.title ?? 'No title available' }}
                    </h2>
                    <p class="booking-subtitle mb-2">{{ booking?.safari?.description ?? 'No description available' }}</p>
                    <div class="operator-info">
                        <div class="operator-avatar">
                            <img v-if="booking?.operator?.profile_photo_url" 
                                 :src="booking?.operator?.profile_photo_url" 
                                 :alt="booking?.operator?.full_name" />
                            <i v-else class="la la-user-tie"></i>
                        </div>
                        <div class="operator-text">
                            <span class="operator-label">Safari by</span>
                            <span class="operator-name">{{ booking?.operator?.full_name ?? 'Not assigned' }}</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-right">
                    <div class="booking-price">
                        <span class="price-label">Total Amount</span>
                        <span class="price-value">${{ Number(booking?.total_price).toLocaleString('en-GB') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Safari Image -->
            <div class="col-md-5">
                <div class="safari-image-card">
                    <img class="safari-image" :src="booking?.safari?.full_thumbnail_url" alt="Safari Image" />
                    <div class="image-overlay">
                        <div class="rating-badge">
                            <i class="la la-star"></i>
                            {{ Number(booking?.safari?.safari_reviews_avg_rating).toFixed(1) ?? '0.0' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Information Cards -->
            <div class="col-md-7">
                <div class="row">
                    <!-- Payment Details Card -->
                    <div class="col-md-12 mb-4">
                        <div class="payment-details-card">
                            <div class="payment-header">
                                <div class="payment-icon-large">
                                    <i class="la la-credit-card"></i>
                                </div>
                                <div class="payment-info">
                                    <h5 class="payment-title">Payment Details</h5>
                                    <div class="payment-status" :class="getPaymentStatusClass(booking?.payment_status)">
                                        {{ booking?.payment_status === 'confirmed' ? 'Payment Confirmed' : 'Payment Pending' }}
                                    </div>
                                </div>
                            </div>
                            <div class="payment-breakdown">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="breakdown-item">
                                            <div class="breakdown-label">
                                                <i class="la la-users"></i> Adults ({{ booking?.no_of_adults ?? 0 }})
                                            </div>
                                            <div class="breakdown-value">
                                                ${{ Number(booking?.adult_price).toLocaleString('en-GB') }}
                                            </div>
                                        </div>
                                        <div class="breakdown-item" v-if="booking?.no_of_children > 0">
                                            <div class="breakdown-label">
                                                <i class="la la-child"></i> Children ({{ booking?.no_of_children }})
                                            </div>
                                            <div class="breakdown-value">
                                                ${{ Number(booking?.children_price).toLocaleString('en-GB') }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="breakdown-item">
                                            <div class="breakdown-label">
                                                <i class="la la-calendar"></i> Check-in Date
                                            </div>
                                            <div class="breakdown-value">
                                                {{ formatDate(booking?.check_in_date) }}
                                            </div>
                                        </div>
                                        <div class="breakdown-item">
                                            <div class="breakdown-label">
                                                <i class="la la-calendar-times-o"></i> Check-out Date
                                            </div>
                                            <div class="breakdown-value">
                                                {{ formatDate(booking?.check_out_date) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="total-section">
                                    <div class="total-label">Total Amount</div>
                                    <div class="total-value">${{ Number(booking?.total_price).toLocaleString('en-GB') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Trip Details Card - Full Width -->
        <div class="row">
            <div class="col-md-12">
                <div class="details-card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="la la-map-o mr-2"></i>
                            Trip Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-calendar-check-o"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Booking Status</span>
                                            <span class="detail-value" :class="getBookingStatusClass(booking?.status)">
                                                {{ getBookingStatusText(booking?.status) }}
                                                <span v-if="booking?.booking_id" class="booking-id">(#{{ booking?.booking_id }})</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-map-marker"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Location</span>
                                            <span class="detail-value">{{ booking?.safari?.location ?? 'NA' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-globe"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Country</span>
                                            <span class="detail-value">{{ booking?.safari?.country?.name ?? 'NA' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-clock-o"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Duration</span>
                                            <span class="detail-value">{{ booking?.duration ?? 'NA' }} nights</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-user"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Traveler</span>
                                            <span class="detail-value">{{ booking?.traveler?.full_name ?? 'NA' }}</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="detail-row">
                                    <div class="detail-item">
                                        <div class="detail-icon">
                                            <i class="la la-calendar"></i>
                                        </div>
                                        <div class="detail-content">
                                            <span class="detail-label">Booking Date</span>
                                            <span class="detail-value">{{ formatDate(booking?.created_at) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from "vue";

const props = defineProps({
    booking: Object
})

const getPaymentStatusClass = (status) => {
    return status === 'confirmed' ? 'payment-paid' : 'payment-unpaid';
}

const getBookingStatusClass = (status) => {
    switch(status) {
        case 'ACTIVE': return 'booking-active';
        case 'COMPLETED': return 'booking-completed';
        case 'CANCEL': return 'booking-cancelled';
        default: return 'booking-pending';
    }
}

const getBookingStatusText = (status) => {
    switch(status) {
        case 'ACTIVE': return 'Active';
        case 'COMPLETED': return 'Completed';
        case 'CANCELLED': return 'Cancelled';
        default: return 'Pending';
    }
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A';
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
}

onMounted(() => {
    emit.emit("pageName", "Booking Management", [
        {
            title: "Booking List",
            routeName: "admin.booking.index",
        },
        {
            title: "View Booking",
            routeName: "",
        },
    ]);
});
</script>

<style scoped>
.booking-view-page {
    padding: 2rem;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
}

/* Page Header */
.page-header {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 30px;
    margin-bottom: 20px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.page-header:hover {
    transform: translateY(-2px);
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.page-title {
    color: #495057;
    font-weight: 600;
    margin: 0;
    font-size: 18px;
}

.page-icon {
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
    text-decoration: none;
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(47, 58, 47, 0.4);
}

/* Header Section */
.booking-header {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    color: #495057;
    padding: 30px;
    border-radius: 15px;
    margin-bottom: 30px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.booking-header:hover {
    transform: translateY(-2px);
}

.booking-title {
    font-size: 20px;
    font-weight: 700;
    margin: 0;
}

.booking-subtitle {
    font-size: 14px;
    opacity: 0.9;
    line-height: 1.5;
}

.booking-price {
    text-align: right;
}

.price-label {
    display: block;
    font-size: 14px;
    opacity: 0.8;
    margin-bottom: 5px;
}

.price-value {
    font-size: 24px;
    font-weight: 700;
    display: block;
}

/* Safari Image Card */
.safari-image-card {
    position: relative;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.safari-image-card:hover {
    transform: translateY(-2px);
}

.safari-image {
    width: 100%;
    height: 350px;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.safari-image:hover {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    top: 15px;
    right: 15px;
}

.rating-badge {
    background: rgba(255, 255, 255, 0.95);
    padding: 8px 15px;
    border-radius: 25px;
    font-weight: 600;
    color: #2f3a2f;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.rating-badge i {
    color: #ffc107;
    margin-right: 5px;
}

/* Payment Details Card */
.payment-details-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 20px;
    transition: transform 0.3s ease;
}

.payment-details-card:hover {
    transform: translateY(-2px);
}

.payment-header {
    background: linear-gradient(135deg, #e2e4e6 0%, #ccd0d4 100%);
    color: #495057;
    padding: 25px;
    display: flex;
    align-items: center;
    transition: all 0.3s ease;
}

.payment-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.payment-icon-large {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 20px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.payment-icon-large i {
    color: white;
    font-size: 28px;
}

.payment-title {
    font-size: 16px;
    margin: 0 0 8px 0;
    font-weight: 600;
}

.payment-status {
    font-size: 14px;
    font-weight: 500;
    opacity: 0.9;
}

.payment-breakdown {
    padding: 35px 25px;
}

.breakdown-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 10px 0;
    border-bottom: 1px solid #f1f3f4;
}

.breakdown-item:last-child {
    border-bottom: none;
}

.breakdown-label {
    font-size: 14px;
    color: #6c757d;
    font-weight: 500;
    display: flex;
    align-items: center;
}

.breakdown-label i {
    margin-right: 8px;
    color: #2f3a2f;
    font-size: 16px;
}

.breakdown-value {
    font-size: 14px;
    font-weight: 600;
    color: #2f3a2f;
}

.total-section {
    margin-top: 30px;
    padding-top: 25px;
    border-top: 2px solid #2f3a2f;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.total-label {
    font-size: 16px;
    font-weight: 700;
    color: #2f3a2f;
}

.total-value {
    font-size: 18px;
    font-weight: 700;
    color: #2f3a2f;
}

/* Info Cards */
.info-card {
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    height: 100%;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-2px);
}

.card-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 15px;
}

.payment-icon {
    background: linear-gradient(135deg, #28a745, #20c997);
}

.booking-icon {
    background: linear-gradient(135deg, #007bff, #0056b3);
}

.card-icon i {
    color: white;
    font-size: 24px;
}

.card-title {
    font-size: 14px;
    color: #6c757d;
    margin: 0 0 8px 0;
    font-weight: 500;
}

.card-value {
    font-size: 16px;
    font-weight: 700;
    margin-bottom: 5px;
}

.card-value.payment-paid {
    color: #28a745;
}

.card-value.payment-unpaid {
    color: #dc3545;
}

.card-value.booking-active {
    color: #007bff;
}

.card-value.booking-completed {
    color: #28a745;
}

.card-value.booking-cancelled {
    color: #dc3545;
}

.card-value.booking-pending {
    color: #ffc107;
}

.booking-id {
    font-size: 12px;
    opacity: 0.8;
    margin-left: 5px;
}

.card-meta {
    font-size: 12px;
    color: #6c757d;
    font-weight: 500;
}

/* Operator Info in Header */
.operator-info {
    display: flex;
    align-items: center;
    margin-top: 15px;
}

.operator-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 12px;
    overflow: hidden;
}

.operator-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.operator-avatar i {
    color: white;
    font-size: 18px;
}

.operator-text {
    display: flex;
    flex-direction: column;
}

.operator-label {
    font-size: 12px;
    opacity: 0.8;
    margin-bottom: 2px;
}

.operator-text .operator-name {
    font-size: 14px;
    font-weight: 600;
    margin: 0;
}

/* Details Card */
.details-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.details-card:hover {
    transform: translateY(-2px);
}

.details-card .card-header {
    background: linear-gradient(135deg, #e2e4e6 0%, #ccd0d4 100%);
    color: #495057;
    padding: 20px;
    border: none;
    transition: all 0.3s ease;
}

.details-card .card-header:hover {
    background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
}

.details-card .card-body {
    padding: 25px;
}

.detail-row {
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #f1f3f4;
}

.detail-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.detail-item {
    display: flex;
    align-items: flex-start;
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
}

.detail-icon i {
    color: white;
    font-size: 18px;
}

.detail-content {
    flex: 1;
}

.detail-label {
    display: block;
    font-size: 14px;
    color: #6c757d;
    margin-bottom: 5px;
    font-weight: 500;
}

.detail-value {
    font-size: 14px;
    color: #2f3a2f;
    font-weight: 600;
    line-height: 1.4;
}



/* Responsive Design */
@media (max-width: 992px) {
    .booking-view-page {
        padding: 15px;
    }
    
    .page-header {
        padding: 15px 20px;
        flex-direction: column;
        gap: 15px;
    }
    
    .page-header-toolbar {
        width: 100%;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .booking-view-page {
        padding: 10px;
    }
    
    .page-header {
        padding: 15px;
        margin-bottom: 15px;
    }
    
    .booking-header {
        padding: 20px;
        margin-bottom: 20px;
    }
    
    .booking-header .row {
        flex-direction: column;
        text-align: center;
    }
    
    .booking-header .col-md-4 {
        margin-top: 15px;
    }
    
    .booking-price {
        text-align: center;
    }
    
    .booking-title {
        font-size: 18px;
    }
    
    .price-value {
        font-size: 20px;
    }
    
    .safari-image {
        height: 250px;
    }
    
    .safari-image-card {
        margin-bottom: 15px;
    }
    
    .operator-info {
        justify-content: center;
        margin-top: 15px;
    }
    
    .payment-header {
        padding: 20px;
        flex-direction: column;
        text-align: center;
    }
    
    .payment-icon-large {
        margin-right: 0;
        margin-bottom: 15px;
    }
    
    .payment-breakdown {
        padding: 25px 20px;
    }
    
    .breakdown-item {
        flex-direction: column;
        align-items: flex-start;
        padding: 15px 0;
        gap: 8px;
    }
    
    .breakdown-label {
        font-size: 13px;
    }
    
    .breakdown-value {
        font-size: 15px;
        font-weight: 700;
    }
    
    .total-section {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
    
    .details-card .card-body {
        padding: 20px 15px;
    }
    
    .detail-row {
        margin-bottom: 15px;
        padding-bottom: 15px;
    }
    
    .detail-icon {
        width: 35px;
        height: 35px;
        margin-right: 12px;
    }
    
    .detail-icon i {
        font-size: 16px;
    }
    
    .detail-label {
        font-size: 13px;
    }
    
    .detail-value {
        font-size: 13px;
    }
}

@media (max-width: 576px) {
    .booking-view-page {
        padding: 8px;
    }
    
    .page-header {
        padding: 12px;
        border-radius: 8px;
    }
    
    .page-title {
        font-size: 16px;
    }
    
    .back-btn {
        padding: 8px 16px;
        font-size: 14px;
    }
    
    .booking-header {
        padding: 15px;
        border-radius: 10px;
        margin-bottom: 15px;
    }
    
    .booking-title {
        font-size: 16px;
    }
    
    .booking-subtitle {
        font-size: 13px;
    }
    
    .price-value {
        font-size: 18px;
    }
    
    .price-label {
        font-size: 13px;
    }
    
    .safari-image {
        height: 200px;
    }
    
    .safari-image-card {
        border-radius: 10px;
    }
    
    .operator-avatar {
        width: 35px;
        height: 35px;
    }
    
    .operator-avatar i {
        font-size: 16px;
    }
    
    .operator-label {
        font-size: 11px;
    }
    
    .operator-text .operator-name {
        font-size: 13px;
    }
    
    .payment-details-card,
    .details-card {
        border-radius: 10px;
    }
    
    .payment-header {
        padding: 15px;
    }
    
    .payment-icon-large {
        width: 50px;
        height: 50px;
    }
    
    .payment-icon-large i {
        font-size: 24px;
    }
    
    .payment-title {
        font-size: 15px;
    }
    
    .payment-status {
        font-size: 13px;
    }
    
    .payment-breakdown {
        padding: 20px 15px;
    }
    
    .breakdown-label {
        font-size: 12px;
    }
    
    .breakdown-value {
        font-size: 14px;
    }
    
    .total-label {
        font-size: 15px;
    }
    
    .total-value {
        font-size: 16px;
    }
    
    .details-card .card-header {
        padding: 15px;
    }
    
    .details-card .card-header h5 {
        font-size: 15px;
    }
    
    .details-card .card-body {
        padding: 15px;
    }
    
    .detail-icon {
        width: 30px;
        height: 30px;
        margin-right: 10px;
    }
    
    .detail-icon i {
        font-size: 14px;
    }
    
    .detail-label {
        font-size: 12px;
    }
    
    .detail-value {
        font-size: 12px;
    }
    
    .booking-id {
        font-size: 11px;
    }
}
</style>
