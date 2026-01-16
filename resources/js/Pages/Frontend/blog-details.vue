<template>
    <Meta :cms="props.meta" />
    <Banner pageName="Blog Details" />
    <div class="blog-listing-sec blg-details-pg">
        <div class="container">
            <div class="row bl-li-row">
                <div class="bl-le-col col-lg-7">
                    <div class="blog-list-wrapper dtl-page">
                        <div class="blog-list">
                            <div class="blog-card">
                                <div class="bl-card-img">
                                    <a href="#url">
                                        <img :src="blog?.full_photo_url" alt="blog image" />
                                    </a>
                                </div>
                                <div class="bl-card-content">
                                    <div class="blog-tags">
                                        <span v-for="(tag, index) in cleanTags(blog.tags)" :key="index">
                                            {{ tag }}
                                        </span>
                                    </div>
                                    <h2>{{ blog?.title }}</h2>

                                    <div class="post-details">
                                        <p>Posted by <span>{{ blog?.posted_by }}</span></p>
                                        <p>Posted on <span>{{ formatDate(blog?.created_at) }}</span></p>
                                    </div>
                                    <div class="blog-details-sec blog_cntnt_sec" v-html="blog?.description">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bl-ri-col col-lg-5">
                    <div class="bl-ri-wrapper stcky_wrppr">
                        <!-- Category Menu -->
                        <BlogCategories :blogCategories="blogCategories" />
                        <!-- Featured Blogs -->
                        <FeatureBlog :featureBlogs="featureBlogs" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from 'moment'
import BlogCategories from '@/components/Frontend/BlogCategory.vue';
import FeatureBlog from '@/components/Frontend/FeatureBlog.vue';
import Banner from '@/components/Frontend/Banner.vue';
import { onMounted } from 'vue'
import { homeJs } from "@/custom.js";
import Meta from '../../components/Frontend/Meta.vue';


const props = defineProps({
    blog: Object,
    blogCategories: Array,
    featureBlogs: Array,
    meta: Object
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