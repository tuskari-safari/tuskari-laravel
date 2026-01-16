<template>
    <div class="featured-sec-wrap" v-if="featureBlogs?.length">
        <h2 class="cat-menu-head">Featured Blogs</h2>
        <div class="mini-bl-outer">
            <div class="blog-card" v-for="featureBlog in featureBlogs" :key="featureBlog.id">
                <div class="bl-card-img">
                    <Link :href="route('frontend.blog-details', { title: featureBlog.title })">
                    <img :src="featureBlog?.full_photo_url" alt="blog image" />
                    </Link>
                </div>
                <div class="bl-card-content">
                    <Link :href="route('frontend.blog-details', { title: featureBlog.title })">
                    <h3>{{ featureBlog?.title }}</h3>
                    </Link>
                    <div class="post-details">
                        <p>Posted by <span>{{ featureBlog?.posted_by }}</span></p>
                        <p>Posted on <span>{{ formatDate(featureBlog?.created_at) }}</span></p>
                    </div>
                    <Link :href="route('frontend.blog-details', { title: featureBlog.title })" class="read-more">
                    Read more</Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from 'moment'
import { route } from 'ziggy-js'
const props = defineProps({
    featureBlogs: Array,
})

const formatDate = (date) => {
    return moment(date).format('Do MMMM YYYY')
}

</script>