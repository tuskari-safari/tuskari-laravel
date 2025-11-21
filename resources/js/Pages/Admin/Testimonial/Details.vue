<template>
    <div class="testimonial-details-container">
        <div class="testimonial-content">
            <!-- Header Actions -->
            <div class="header-actions">
                <Link :href="route('admin.testimonial.list')" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </Link>
            </div>

            <!-- Main Details Card -->
            <div class="details-card">
                <div class="card-header">
                    <div class="header-content">
                        <div class="title-section">
                            <h1 class="testimonial-title">
                                <i class="fas fa-quote-left section-icon"></i>
                                {{ testimonial?.title ?? "Testimonial Details" }}
                            </h1>
                            <div class="status-badge" :class="testimonial?.status == 1 ? 'active' : 'inactive'">
                                {{ testimonial?.status == 1 ? "Active" : "Inactive" }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-content">
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-icon" :class="{'has-image': testimonial?.testimonial_user_image_path}">
                                <img v-if="testimonial?.testimonial_user_image_path" :src="testimonial.testimonial_user_image_path" alt="User Image" class="user-image" />
                                <i v-else class="fas fa-user"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Full Name</h4>
                                <div class="user-info">
                                    <p>{{ testimonial?.full_name ?? 'N/A' }}</p>
                                   
                                </div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Rating</h4>
                                <p>{{ testimonial?.rating ? testimonial.rating + ' Stars' : 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-icon">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="detail-content">
                                <h4>Created Date</h4>
                                <p>{{ ListHelper.dateFormat(testimonial?.created_at, "MMM DD, YYYY") ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="description-section">
                        <div class="section-header">
                            <h3><i class="fas fa-comment-alt"></i>Testimonial Content</h3>
                        </div>
                        <div class="description-content">
                            <p>{{ testimonial?.description ?? 'No content available' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { router } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import { ref, watch, reactive, onMounted, onUnmounted } from "vue";
import { Bootstrap4Pagination } from "laravel-vue-pagination";
import service from "../../../helpers/service";
import ListHelper from "../../../helpers/ListHelper";
import perPageDropdown from "@/components/PerpageDropdown.vue";

const props = defineProps({
    testimonial: Object,
});


onMounted(() => {
  if (props.testimonial) {
    emit.emit("pageName", "Content Management", [
      {
        title: "Testimonial List",
        routeName: "admin.testimonial.list",
      },
      {
        title: "View Details",
        routeName: "",
      },
    ]);
  }
});
</script>

<style scoped>
.testimonial-details-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
    padding: 2rem;
}

.testimonial-content {
    max-width: 1200px;
    margin: 0 auto;
}

/* Header Actions */
.header-actions {
    margin-bottom: 2rem;
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
    padding: 0.6rem 1.2rem;
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
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

/* Main Card */
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

.card-header {
    padding: 2rem;
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.title-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    flex: 1;
}

.testimonial-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.section-icon {
    color: #2f3a2f;
    font-size: 1.5rem;
}

.status-badge {
    padding: 0.5rem 1.25rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-badge.active {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(40, 167, 69, 0.3);
}

.status-badge.inactive {
    background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
    color: white;
    box-shadow: 0 2px 6px rgba(220, 53, 69, 0.3);
}

.card-content {
    padding: 2rem;
}

/* Details Grid */
.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-item {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
    border-left: 4px solid #2f3a2f;
}

.detail-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.detail-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #2f3a2f 0%, #4a5a4a 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(47, 58, 47, 0.3);
    overflow: hidden;
}

.detail-icon.has-image {
    background: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.detail-icon .user-image {
    width: 100%;
    height: 100%;
    border: none;
    box-shadow: none;
}

.detail-content {
    flex: 1;
}

.detail-content h4 {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
}

.detail-content p {
    margin: 0;
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.5;
}

/* Description Section */
.description-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    border-left: 4px solid #2f3a2f;
}

.section-header {
    margin-bottom: 1.5rem;
}

.section-header h3 {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.description-content {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
}

.description-content p {
    margin: 0;
    color: #6c757d;
    line-height: 1.7;
    font-size: 1.1rem;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.user-image {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #2f3a2f;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Responsive */
@media (max-width: 768px) {
    .testimonial-details-container {
        padding: 1rem;
    }
    
    .testimonial-title {
        font-size: 1.5rem;
    }
    
    .header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .title-section {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .detail-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
}
</style>