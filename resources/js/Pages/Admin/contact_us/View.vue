<template>
    <div class="contact-details-container">
        <div class="contact-content">
            <!-- Header Actions -->
            <div class="header-actions">
                <Link :href="route('admin.contact-us')" class="back-button">
                    <i class="fas fa-arrow-left"></i>
                    Back
                </Link>
            </div>
            
            <!-- Hero Section -->
            <div class="hero-card">
                <div class="hero-header">
                    <div class="hero-title-section">
                        <div class="title-content">
                            <h1 class="contact-title">Contact Us Details</h1>
                            <div class="contact-meta">
                                <span class="status-badge" :class="form.status == 1 ? 'active' : 'inactive'">
                                    <i :class="form.status == 1 ? 'fas fa-check-circle' : 'fas fa-clock'"></i>
                                    {{ form.status == 1 ? "Resolved" : "Pending" }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Section -->
            <div class="contact-info">
                <div class="info-row">
                    <div class="info-item">
                        <i class="fas fa-user info-icon-simple"></i>
                        <div class="info-details">
                            <label>Full Name</label>
                            <span>{{ form.name || 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-envelope info-icon-simple"></i>
                        <div class="info-details">
                            <label>Email Address</label>
                            <span>{{ form.email || 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <i class="fas fa-flag info-icon-simple"></i>
                        <div class="info-details">
                            <label>Status</label>
                            <span class="status-simple" :class="form.status == 1 ? 'resolved' : 'pending'">
                                {{ form.status == 1 ? 'Resolved' : 'Pending' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="message-section">
                    <div class="message-header">
                        <i class="fas fa-comment-alt info-icon-simple"></i>
                        <label>Message</label>
                    </div>
                    <div class="message-text">
                        {{ form.message || 'No message provided' }}
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
    contactus: Object
});

const form = useForm({
    name: props.contactus?.full_name || null,
    email: props.contactus?.email || null,
    message: props.contactus?.message || null,
    status: props.contactus?.status || "",
});

onMounted(() => {
    if (props.contactus) {
        emit.emit('pageName', 'Customer Support Management', [
            { title: "Contact Us List", routeName: "admin.contact-us" },
            { title: "View Contact Us", routeName: "" }
        ]);
    }
});
</script>

<style scoped>
.contact-details-container {
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    min-height: 100vh;
    padding: 2rem;
}

.contact-content {
    max-width: 1400px;
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
    padding: 0.5rem 1.2rem;
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

/* Hero Section */
.hero-card {
    background: linear-gradient(135deg, #c8cacc 0%, #e9ecef 100%);
    border-radius: 20px;
    margin-bottom: 2rem;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.hero-card:hover {
    transform: translateY(-2px);
}

.hero-header {
    padding: 2rem;
}

.hero-title-section {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex: 1;
}

.contact-title {
    font-size: 2rem;
    font-weight: 700;
    margin: 0;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.contact-meta {
    display: flex;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-badge {
    padding: 0.5rem 1.25rem;
    border-radius: 25px;
    font-weight: 600;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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

/* Contact Info */
.contact-info {
    background: white;
    border-radius: 15px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.contact-info:hover {
    transform: translateY(-2px);
}

.info-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
    margin-bottom: 2.5rem;
}

.info-item {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 15px;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.3s ease;
    border-left: 4px solid #2f3a2f;
}

.info-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.info-icon-simple {
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
}

.info-details {
    flex: 1;
}

.info-details label {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #495057;
    display: block;
}

.info-details span {
    margin: 0;
    color: #6c757d;
    font-size: 1rem;
    line-height: 1.5;
}

.status-simple {
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-simple.resolved {
    background: #f0f9f0;
    color: #1e7e34;
    border: 1px solid #c3e6cb;
}

.status-simple.pending {
    background: #fff8e1;
    color: #b8860b;
}

.message-section {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 2rem;
    border-left: 4px solid #2f3a2f;
    margin-top: 2rem;
}

.message-header {
    margin-bottom: 1.5rem;
}

.message-header label {
    margin: 0;
    font-size: 1.4rem;
    font-weight: 600;
    color: #495057;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.message-text {
    background: white;
    padding: 1.5rem;
    border-radius: 10px;
    border: 1px solid #dee2e6;
    margin: 0;
    color: #6c757d;
    line-height: 1.7;
    font-size: 1.1rem;
    white-space: pre-wrap;
}



/* Responsive */
@media (max-width: 768px) {
    .contact-details-container {
        padding: 1rem;
    }
    
    .contact-title {
        font-size: 2rem;
    }
    
    .info-grid {
        grid-template-columns: 1fr;
    }
    
    .contact-meta {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .contact-info {
        padding: 1.5rem;
    }
    
    .info-row {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .hero-header {
        padding: 1.5rem;
    }
}
</style>