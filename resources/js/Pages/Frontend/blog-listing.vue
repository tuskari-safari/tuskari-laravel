<template>
    <Banner pageName="Blog Listing" />
    <div class="blog-listing-sec">
        <div class="container">
            <div class="row bl-li-row">
                <!-- Blog List -->
                <div class="col-lg-7 bl-le-col">
                    <div class="blog-list-wrapper">
                        <div class="blog-list" v-for="blog in blogs.data" :key="blog.id">
                            <div class="blog-card">
                                <div class="bl-card-img">
                                    <Link :href="route('frontend.blog-details', { title: blog.title })">
                                        <img :src="blog?.full_photo_url" alt="blog image" />
                                    </Link>
                                </div>
                                <div class="bl-card-content">
                                    <Link :href="route('frontend.blog-details', { title: blog.title })">
                                        <div class="h3-title">{{ blog?.title }}</div>
                                    </Link>

                                    <div class="blog-tags">
                                        <span v-for="(tag, index) in cleanTags(blog.tags)" :key="index">
                                            {{ tag }}
                                        </span>
                                    </div>

                                    <div class="post-details">
                                        <p>Posted by <span>{{ blog?.posted_by }}</span></p>
                                        <p>Posted on <span>{{ formatDate(blog?.created_at) }}</span></p>
                                    </div>

                                    <p class="short-cont">
                                        {{ blog.description ? blog.description.replace(/<[^>]*>/g,'').replace(/&nbsp;/gi,' ').replace(/&[^;\s]+;/g, '').trimStart().slice(0, 150) + '...' : '' }}</p>

                                    <Link :href="route('frontend.blog-details', { title: blog.title })" class="read-more">
                                    Read more
                                    </Link>
                                </div>
                            </div>
                        </div>

                        <div v-if="!blogs?.data?.length">
                            <div class="h2-title text-center">No Blogs Found</div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <Pagination :pagination="blogs" :route-name="paginationRouteName"
                        :extraParams="paginationParams" />
                </div>

                <!-- Right Column -->
                <div class="col-lg-5 bl-ri-col">
                    <div class="bl-ri-wrapper stcky_wrppr">
                        <!-- Category Menu -->
                        <BlogCategories :blogCategories="blogCategories" />
                        <!-- Featured Blogs -->
                        <FeatureBlog :featureBlogs="featureBlogs" />
                    </div>
                </div>
                <!-- End Right Column -->
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from 'moment'
import { onMounted, ref, computed } from 'vue'
import Pagination from '@/components/customPaginate.vue'
import { homeJs } from "@/custom.js";
import BlogCategories from '@/components/Frontend/BlogCategory.vue';
import FeatureBlog from '@/components/Frontend/FeatureBlog.vue';
import Banner from '@/components/Frontend/Banner.vue';
import { usePage } from '@inertiajs/vue3'

const page = usePage();
const params = () => new URLSearchParams(window.location.search);
const selectedCategory = ref(params().get('category'));

const props = defineProps({
    blogs: Object,
    blogCategories: Array,
    featureBlogs: Array,
});

// Determine pagination route and parameters based on current URL
const paginationRouteName = computed(() => {
    const currentRoute = page.component;
    return currentRoute === 'Frontend/blog-listing' && page.url.includes('/category/') 
        ? 'frontend.blogs.category' 
        : 'frontend.blogs';
});

const paginationParams = computed(() => {
    if (page.url.includes('/category/')) {
        const categoryMatch = page.url.match(/\/category\/([^?]+)/);
        return categoryMatch ? { category: decodeURIComponent(categoryMatch[1]) } : {};
    }
    return {};
});

onMounted(() => {
    homeJs();
});

const cleanTags = (tags) => {
    return tags
        ?.split(',')
        .map(tag => tag.trim())
        .filter(tag => tag !== '');
};

const formatDate = (date) => {
    return moment(date).format('Do MMMM YYYY')
}

</script>
