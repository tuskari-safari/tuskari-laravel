<template>
    <div class="category-menu" v-if="blogCategories?.length">
        <h2 class="cat-menu-head">Category Search</h2>
        <div class="cat-menu">
            <ul>
                <li>
                    <Link :href="route('frontend.blogs')" :preserve-scroll="true">
                    <h3 class="mnu-titl">All Blogs</h3>
                    <span class="mnu-icon"><img :src="'/frontend_assets/images/cate-ri-img.png'"
                            alt="Trip Icon" /></span>
                    </Link>
                </li>

                <li v-for="blogCategory in blogCategories" :key="blogCategory.id">
                    <Link :href="route('frontend.blogs.category', { category: blogCategory?.title })" :preserve-scroll="true">
                    <h3 class="mnu-titl">{{ blogCategory?.title }}</h3>
                    <span class="mnu-icon"><img :src="'/frontend_assets/images/cate-ri-img.png'"
                            alt="Trip Icon" /></span>
                    </Link>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    blogCategories: Array,
})

</script>