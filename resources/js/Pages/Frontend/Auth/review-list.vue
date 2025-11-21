<template>
    <div class="panel_rght_cntnt_area">
        <!-- Header Section -->
        <div class="reviews-header">
            <div class="header-content">
                <h1 class="page-title">Safari Operator Reviews</h1>
                <p class="page-subtitle">Discover what travelers say about their safari experiences</p>
            </div>
            <div class="stats-card">
                <div class="stat-item">
                    <span class="stat-number">{{ operatorsWithReviews?.total || 0 }}</span>
                    <span class="stat-label">Operators</span>
                </div>
            </div>
        </div>

        <!-- Operators Grid -->
        <div class="operators-grid" v-if="operatorsWithReviews?.data?.length > 0">
            <div v-for="user in operatorsWithReviews.data" :key="user.id" class="operator-card">
                <div class="card-header">
                    <div class="operator-avatar">
                        <img :src="user?.profile_photo_url || '/frontend_assets/images/default-user.png'" 
                             :alt="user?.business_name" />
                        <div class="verified-badge">
                            <i class="fas fa-check"></i>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <h3 class="business-name">{{ user?.business_name }}</h3>
                    <p class="operator-name">
                        <i class="fas fa-user"></i>
                        {{ user?.full_name }}
                    </p>
                    <p class="operator-email">
                        <i class="fas fa-envelope"></i>
                        {{ user?.email }}
                    </p>
                </div>
                
                <div class="card-footer">
                    <a :href="route('frontend.view-review', user?.id)" class="view-reviews-btn">
                        <i class="fas fa-star"></i>
                        View Reviews
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="empty-state">
            <div class="empty-icon">
                <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>No Reviews Available</h3>
            <p>There are currently no safari operators with reviews to display.</p>
        </div>

        <!-- Pagination -->
        <div class="pagination-wrapper" v-if="operatorsWithReviews?.data?.length > 0">
            <Pagination :pagination="operatorsWithReviews" route-name="frontend.all-reviews" />
        </div>
    </div>
</template>

<script setup>
import { onMounted } from 'vue';
import { homeJs } from "@/custom.js";
import { route } from 'ziggy-js';
import Pagination from '@/components/customPaginate.vue'

const props = defineProps({
    operatorsWithReviews: Object
});

onMounted(() => {
    homeJs();
emit.emit("pageName", "Operators reviews");
    emit.emit("pageSubTitle", "Reviews given by you to operators");
});

</script>

<style scoped>
/* Header Section */
.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 40px;
    padding: 30px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--grencolor-one) 100%);
    border-radius: 20px;
    color: var(--white-color);
}

.header-content .page-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 8px 0;
    color: var(--white-color);
}

.header-content .page-subtitle {
    font-size: 16px;
    opacity: 0.9;
    margin: 0;
}

.stats-card {
    background: rgba(255, 255, 255, 0.15);
    padding: 20px;
    border-radius: 15px;
    text-align: center;
    backdrop-filter: blur(10px);
}

.stat-number {
    display: block;
    font-size: 24px;
    font-weight: 700;
    color: var(--white-color);
}

.stat-label {
    font-size: 14px;
    opacity: 0.9;
}

/* Operators Grid */
.operators-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 25px;
    margin-bottom: 40px;
}

.operator-card {
    background: var(--white-color);
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
    border: 1px solid #f0f0f0;
}

.operator-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 30px rgba(72, 86, 72, 0.15);
}

.card-header {
    padding: 25px 25px 0;
    text-align: center;
    position: relative;
}

.operator-avatar {
    position: relative;
    display: inline-block;
    margin-bottom: 15px;
}

.operator-avatar img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid var(--white-color);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.verified-badge {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 24px;
    height: 24px;
    background: var(--scendory-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--white-color);
    font-size: 12px;
    border: 2px solid var(--white-color);
}

.card-body {
    padding: 0 25px 20px;
    text-align: center;
}

.business-name {
    font-size: 20px;
    font-weight: 600;
    color: var(--black-color-one);
    margin: 0 0 15px 0;
    line-height: 1.3;
}

.operator-name,
.operator-email {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    color: var(--body-color);
    margin: 8px 0;
}

.operator-name i,
.operator-email i {
    color: var(--primary-color);
    width: 16px;
}

.card-footer {
    padding: 0;
    border-top: 1px solid #f0f0f0;
}

.view-reviews-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    padding: 18px;
    background: linear-gradient(135deg, var(--primary-color) 0%, var(--grencolor-one) 100%);
    color: var(--white-color);
    text-decoration: none;
    font-weight: 500;
    font-size: 15px;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.view-reviews-btn:hover {
    background: linear-gradient(135deg, var(--grencolor-one) 0%, var(--scendory-color) 100%);
    color: var(--white-color);
    text-decoration: none;
}

.view-reviews-btn:hover .fa-arrow-right {
    transform: translateX(5px);
}

.view-reviews-btn .fa-arrow-right {
    transition: transform 0.3s ease;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: var(--white-color);
    border-radius: 20px;
    border: 1px solid #f0f0f0;
}

.empty-icon {
    font-size: 64px;
    color: var(--body-color);
    margin-bottom: 25px;
    opacity: 0.6;
}

.empty-state h3 {
    font-size: 24px;
    font-weight: 600;
    color: var(--black-color-one);
    margin: 0 0 15px 0;
}

.empty-state p {
    font-size: 16px;
    color: var(--body-color);
    margin: 0;
    max-width: 400px;
    margin: 0 auto;
}

/* Pagination */
.pagination-wrapper {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* Responsive Design */
@media (max-width: 768px) {
    .reviews-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .operators-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .header-content .page-title {
        font-size: 24px;
    }
    
    .stats-card {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .reviews-header {
        padding: 20px;
    }
    
    .operator-card {
        margin: 0 10px;
    }
    
    .header-content .page-title {
        font-size: 22px;
    }
}
</style>