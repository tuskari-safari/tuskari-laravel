<template>
    <Head>
        <title>{{ cms?.meta_title ? cms?.meta_title : $page.props.appName }}</title>
        <meta name="title" :content="cms?.meta_title ? cms?.meta_title : $page.props.appName">
        <meta name="description" :content="cms?.meta_description ? cms?.meta_description : $page.props.appName">
        <meta property="og:locale" content="en_US" />
        <meta property="og:site_name" :content="$page.props.appName" />
        <meta property="og:type" :content="cms?.og_type ? cms?.og_type : 'website'" />
        <meta property="og:title" :content="cms?.og_title ? cms?.og_title : (cms?.meta_title ? cms?.meta_title : '')" />
        <meta property="og:description"
            :content="cms?.og_description ? cms?.og_description : (cms?.meta_description ? cms?.meta_description : '')" />
        <meta property="og:url" :content="cms?.og_url ? cms?.og_url : ($page.props.currentUrl)" />
        <meta property="og:image" :content="cms?.og_image_url ? cms?.og_image_url : ''" />
        <meta name="robots"
            :content="cms?.index_follow == 1 && $page.props.appEnv !== 'local' ? 'index, follow' : 'noindex,nofollow'" />
        <link rel="canonical" :href="cms?.canonical_url ? cms?.canonical_url : ($page.props.appUrl + $page.url)" />
        <meta property="og:image:secure_url" :content="cms?.og_image_url ? cms?.og_image_url : ''" />
        <meta property="og:image:width" :content="cms?.og_image_width ? cms?.og_image_width : ''" />
        <meta property="og:image:height" :content="cms?.og_image_height ? cms?.og_image_height : ''" />
        <meta name="twitter:card" content="summary_large_image" />
        <meta name="twitter:title" :content="cms?.meta_title ? cms?.meta_title : ''" />
        <meta name="twitter:description" :content="cms?.meta_description ? cms?.meta_description : ''" />
        <meta name="twitter:image" :content="cms?.og_image_url ? cms?.og_image_url : ''" />
        <meta name="twitter:data1" :content="$page.props.appName" />
    </Head>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
const props = defineProps({
    cms: Object,
});
</script>