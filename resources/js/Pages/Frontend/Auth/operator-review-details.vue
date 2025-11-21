<template>
    <div class="panel_rght_cntnt_area">
        <div class="full-width-container">
                <!-- Reviews Section -->
                <div class="reviews-section">
                    <!-- Toggle Bar -->
                    <div class="review-toggle-section">
                        <div class="toggle-tabs">
                            <button 
                                @click="activeTab = 'safari'"
                                :class="['toggle-tab', { 'active': activeTab === 'safari' }]"
                            >
                                <i class="fas fa-binoculars"></i>
                                Safari Reviews
                                <span class="count">{{ getTotalSafariReviews() }}</span>
                            </button>
                            <button 
                                @click="activeTab = 'operator'"
                                :class="['toggle-tab', { 'active': activeTab === 'operator' }]"
                            >
                                <i class="fas fa-user-tie"></i>
                                Operator Reviews
                                <span class="count">{{ operatorReviews?.length || 0 }}</span>
                            </button>
                        </div>
                        
                        <!-- Overall Ratings -->
                        <div class="overall-ratings">
                            <div v-if="activeTab === 'safari'" class="rating-display">
                                <div class="stars">
                                    <i v-for="n in 5" :key="n" :class="n <= getSafariOverallRating() ? 'fas fa-star' : 'far fa-star'"></i>
                                </div>
                                <span class="rating-text">⭐ {{ getSafariOverallRating().toFixed(1) }} · {{ getTotalSafariReviews() }} reviews</span>
                            </div>
                            <div v-if="activeTab === 'operator'" class="rating-display">
                                <div class="stars">
                                    <i v-for="n in 5" :key="n" :class="n <= getOperatorOverallRating() ? 'fas fa-star' : 'far fa-star'"></i>
                                </div>
                                <span class="rating-text">⭐ {{ getOperatorOverallRating().toFixed(1) }} · {{ operatorReviews?.length || 0 }} reviews</span>
                            </div>
                        </div>
                    </div>

                    <!-- Safari Reviews Tab -->
                    <div v-if="activeTab === 'safari'" class="tab-content">
                        <h3 class="reviews-section-title">Safari Reviews</h3>
                        <div v-if="Object.keys(reviews).length === 0" class="no-reviews-state">
                            <div class="no-reviews-icon">
                                <i class="far fa-star"></i>
                            </div>
                            <p>No safari reviews available for this operator yet.</p>
                        </div>
                        <div v-else>
                        <div v-for="(safariReviews, safariId) in reviews" :key="safariId" class="safari-review-card">
                            <!-- Safari Header -->
                            <div class="safari-header" @click="toggle(safariId)">
                                <div class="safari-title-section">
                                    <div class="safari-icon">
                                        <img :src="safariReviews[0]?.safari?.full_thumbnail_url" alt="Safari Icon" />
                                    </div>
                                    <div class="safari-info">
                                        <h4 class="safari-name">{{ safariReviews[0]?.safari?.title }}</h4>
                                        <span class="review-count">{{ safariReviews.length }} review{{
                                            safariReviews.length > 1 ? 's' : '' }}</span>
                                    </div>
                                </div>
                                <div class="toggle-btn">
                                    <i
                                        :class="openSafari === safariId ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                                </div>
                            </div>

                            <!-- Reviews List -->

                            <slide-up-down :active="openSafari === safariId" :duration="350">
                                <div v-for="(review, index) in safariReviews" :key="review.id" class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-avatar">
                                                <img :src="review.user?.profile_photo_url || '/admin_assets/demo.png'"
                                                    :alt="review.user?.full_name" />
                                            </div>
                                            <div class="reviewer-details">
                                                <h5 class="reviewer-name">{{ review.user?.full_name??'Unknown User' }}</h5>
                                                <span class="review-date">{{ formatDate(review.created_at) }}</span>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            <div class="stars">
                                                <i v-for="n in 5" :key="n"
                                                    :class="n <= review.rating ? 'fas fa-star' : 'far fa-star'"></i>
                                            </div>
                                            <span class="rating-value">{{ review.rating }}/5</span>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <h6 class="review-title">{{ review.heading }}</h6>
                                        <p class="review-text">{{ review.review_text }}</p>
                                    </div>
                                </div>
                            </slide-up-down>
                        </div>
                        </div>
                    </div>

                    <!-- Operator Reviews Tab -->
                    <div v-if="activeTab === 'operator'" class="tab-content">
                        <h3 class="reviews-section-title">Operator Reviews</h3>
                        <div v-if="!operatorReviews || operatorReviews.length === 0" class="no-reviews-state">
                            <div class="no-reviews-icon">
                                <i class="far fa-star"></i>
                            </div>
                            <p>No operator reviews available yet.</p>
                        </div>
                        <div v-else>
                            <div v-for="review in operatorReviews" :key="review.id" class="safari-review-card">
                                <div class="review-item">
                                    <div class="review-header">
                                        <div class="reviewer-info">
                                            <div class="reviewer-details">
                                                <h5 class="reviewer-name">{{ review.source || 'External Review' }}</h5>
                                                <span class="review-date">{{ formatDate(review.created_at) }}</span>
                                            </div>
                                        </div>
                                        <div class="review-rating">
                                            <div class="stars">
                                                <i v-for="n in 5" :key="n" :class="n <= review.rating ? 'fas fa-star' : 'far fa-star'"></i>
                                            </div>
                                            <span class="rating-value">{{ review.rating }}/5</span>
                                        </div>
                                    </div>
                                    <div class="review-content">
                                        <p class="review-text">{{ review.review_text }}</p>
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
import { onMounted, ref } from 'vue';
import { homeJs } from "@/custom.js";
import SlideUpDown from 'vue-slide-up-down'

const props = defineProps({
    operator: Object,
    reviews: Object,
    operatorReviews: Array
});

const openSafari = ref(null);
const activeTab = ref('safari');
const openOperatorReview = ref(null);

const toggle = (id) => {
    openSafari.value = openSafari.value === id ? null : id;
};



const getTotalSafariReviews = () => {
    return Object.values(props.reviews).reduce((total, safariReviews) => total + safariReviews.length, 0);
};

const getSafariOverallRating = () => {
    const allReviews = Object.values(props.reviews).flat();
    if (allReviews.length === 0) return 0;
    const totalRating = allReviews.reduce((sum, review) => sum + review.rating, 0);
    return totalRating / allReviews.length;
};

const getOperatorOverallRating = () => {
    if (!props.operatorReviews || props.operatorReviews.length === 0) return 0;
    const totalRating = props.operatorReviews.reduce((sum, review) => sum + review.rating, 0);
    return totalRating / props.operatorReviews.length;
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

onMounted(() => {
    homeJs();
    emit.emit("pageName", "Operator Reviews");
    emit.emit("pageSubTitle", "Reviews for this safari operator");
    
    // Open first safari accordion initially
    const safariKeys = Object.keys(props.reviews);
    if (safariKeys.length > 0) {
        openSafari.value = safariKeys[0];
    }
    

});
</script>

<style scoped>
/* Reviews Section Styling */
.reviews-section {
    margin-top: 0;
}

/* Toggle Section */
.review-toggle-section {
    background: white;
    padding: 20px;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 1px solid var(--gray-color-one);
}

.toggle-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}

.toggle-tab {
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
    transition: all 0.3s ease;
}

.toggle-tab:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
    box-shadow: 0 4px 8px rgba(72, 86, 72, 0.15);
}

.toggle-tab.active {
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--grencolor-one) 100%);
    color: white;
    border-color: var(--primary-color);
    box-shadow: 0 4px 12px rgba(72, 86, 72, 0.3);
}

.toggle-tab i {
    font-size: 0.9rem;
}

.toggle-tab .count {
    background: rgba(255, 255, 255, 0.2);
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
}

.toggle-tab.active .count {
    background: rgba(255, 255, 255, 0.3);
}

.toggle-tab:not(.active) .count {
    background: #f8f9fa;
    color: #6c757d;
}

.overall-ratings {
    display: flex;
    justify-content: center;
    padding: 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 12px;
    border: 1px solid #dee2e6;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 12px;
}

.rating-display .stars {
    display: flex;
    gap: 2px;
}

.rating-display .stars i {
    font-size: 16px;
    color: var(--scendory-color);
}

.rating-display .stars .far {
    color: #ddd;
}

.rating-text {
    font-size: 16px;
    font-weight: 600;
    color: var(--primary-color);
}

.tab-content {
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.reviews-section-title {
    font-size: 22px;
    font-weight: 600;
    color: var(--black-color-one);
    margin-bottom: 25px;
    padding-bottom: 10px;
    border-bottom: 2px solid var(--light-green-color-one);
}

/* No Reviews State */
.no-reviews-state {
    text-align: center;
    padding: 60px 20px;
    background-color: #f8f9fa;
    border-radius: 20px;
    border: 1px solid var(--gray-color-one);
}

.no-reviews-icon {
    font-size: 48px;
    color: var(--body-color);
    margin-bottom: 20px;
}

.no-reviews-state p {
    color: var(--body-color);
    font-size: 16px;
    margin: 0;
}

/* Safari Review Card */
.safari-review-card {
    background: var(--white-color);
    border: 1px solid var(--gray-color-one);
    border-radius: 20px;
    margin-bottom: 20px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.safari-review-card:hover {
    box-shadow: 0px 4px 20px rgba(72, 86, 72, 0.1);
}

/* Safari Header */
.safari-header {
    padding: 25px 30px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--grencolor-one) 100%);
    color: var(--white-color);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.3s ease;
}

.safari-header:hover {
    background: linear-gradient(135deg, var(--grencolor-one) 0%, var(--scendory-color) 100%);
}

.safari-title-section {
    display: flex;
    align-items: center;
}

.safari-icon {
    width: 45px;
    height: 45px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    font-size: 18px;
    overflow: hidden;
}

.safari-icon img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.safari-name {
    font-size: 18px;
    font-weight: 600;
    margin: 0 0 5px 0;
    color: var(--white-color);
}

.review-count {
    font-size: 14px;
    opacity: 0.9;
}

.toggle-btn {
    font-size: 16px;
    transition: transform 0.3s ease;
}

/* Reviews List */
.reviews-list {
    padding: 0;
}

.review-item {
    padding: 25px 30px;
    border-bottom: 1px solid #f0f0f0;
    transition: background-color 0.3s ease;
}

.review-item:last-child {
    border-bottom: none;
}

.review-item:hover {
    background-color: #fafafa;
}

/* Operator Review Avatar Icon */
.reviewer-avatar i.fas.fa-user-circle {
    width: 45px;
    height: 45px;
    font-size: 45px;
    color: var(--primary-color);
}

.review-item.featured-review {
    background: linear-gradient(135deg, #fff9eb 0%, #f8f9fa 100%);
    border-left: 4px solid var(--scendory-color);
}

.review-item.featured-review {
    position: relative;
}

/* Review Header */
.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
}

.reviewer-info {
    display: flex;
    align-items: center;
}

.reviewer-avatar {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    overflow: hidden;
    margin-right: 12px;
    border: 2px solid var(--light-green-color-one);
}

.reviewer-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.reviewer-name {
    font-size: 16px;
    font-weight: 600;
    color: var(--black-color-one);
    margin: 0 0 4px 0;
}

.review-date {
    font-size: 12px;
    color: var(--body-color);
}

/* Review Rating */
.review-rating {
    display: flex;
    align-items: center;
    gap: 8px;
}

.stars {
    display: flex;
    gap: 2px;
}

.stars i {
    font-size: 14px;
    color: var(--scendory-color);
}

.stars .far {
    color: #ddd;
}

.rating-value {
    font-size: 14px;
    font-weight: 600;
    color: var(--primary-color);
    background: var(--light-green-color-one);
    padding: 4px 8px;
    border-radius: 12px;
}

/* Review Content */
.review-content {
    padding-left: 57px;
}

/* Operator Review Content - No Avatar */
.tab-content .safari-review-card .review-content {
    padding-left: 0;
}

.review-title {
    font-size: 16px;
    font-weight: 600;
    color: var(--black-color-one);
    margin: 0 0 8px 0;
}

.review-text {
    font-size: 14px;
    color: var(--body-color);
    line-height: 1.6;
    margin: 0;
}

/* Full Width Container */
.full-width-container {
    width: 100%;
    padding: 0 15px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .full-width-container {
        padding: 0 10px;
    }

    .toggle-tabs {
        flex-direction: column;
        gap: 8px;
    }

    .toggle-tab {
        justify-content: center;
        padding: 10px 15px;
    }

    .review-toggle-section {
        padding: 15px;
        margin-bottom: 20px;
    }

    .safari-header {
        padding: 15px 20px;
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .safari-title-section {
        flex-direction: column;
        gap: 10px;
    }

    .review-item {
        padding: 15px;
    }

    .review-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .review-content {
        padding-left: 0;
        margin-top: 15px;
    }

    .safari-name {
        font-size: 16px;
        margin-bottom: 0;
    }

    .safari-icon {
        width: 40px;
        height: 40px;
        font-size: 16px;
        margin-right: 0;
    }

    .reviewer-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .reviewer-avatar {
        margin-right: 0;
    }
}

@media (max-width: 480px) {
    .full-width-container {
        padding: 0 5px;
    }

    .reviews-section-title {
        font-size: 18px;
        text-align: center;
    }

    .safari-header {
        padding: 12px 15px;
    }

    .review-item {
        padding: 12px;
    }

    .toggle-tab {
        font-size: 12px;
        padding: 8px 12px;
    }

    .rating-text {
        font-size: 12px;
    }
}
</style>