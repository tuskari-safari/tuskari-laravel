<template>
    <div class="share-btn shareSocial" ref="shareBtnRef">
        <a href="javascript:void(0)" class="share-icon" :class="{ 'openShare': isShare }" @click.prevent="shareLink">
            <Icon icon="material-symbols:share" />
        </a>

        <div class="socialLink" v-if="isShare">
            <ul>
                <li v-for="(link, key) in links" :key="key">
                    <a :href="link" target="_blank" rel="noopener">
                        <span class="socialIcon" v-if="key === 'facebook'"><i class="fab fa-facebook-f"></i></span>
                        <span class="socialIcon" v-else-if="key === 'twitter'"><i class="fab fa-x-twitter"></i></span>
                        <span class="socialIcon" v-else-if="key === 'whatsapp'"><i class="fab fa-whatsapp"></i></span>
                        <span class="socialIcon" v-else-if="key === 'linkedin'"><i
                                class="fab fa-linkedin-in"></i></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import axios from 'axios';

const links = ref({});
const isShare = ref(false);
const shareBtnRef = ref(null);

const props = defineProps({
    share: Object
});

const shareLink = async () => {

    try {
        const { data } = await axios.get(route("frontend.generate-social-link"), {
            params: {
                url: props.share.safariLink,
                title: props.share.safariTitle
            }
        });

        links.value = data
        isShare.value = !isShare.value
    } catch (error) {
        console.error("Error generating social links:", error)
    }
}
const handleClickOutside = (event) => {
    if (shareBtnRef.value && !shareBtnRef.value.contains(event.target)) {
        isShare.value = false;
    }
};

onMounted(() => {
    document.addEventListener("click", handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener("click", handleClickOutside);
});
</script>

<style>
.shareSocial {
    position: relative;
    display: inline-block;
}

.share-icon {
    display: flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    text-decoration: none;
}

.toggle-icon {
    font-size: 18px;
    color: #333;
}

.socialLink {
    background: #fff;
    padding: 8px 12px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    display: flex;
    justify-content: center;
    z-index: 1;
    position: absolute;
    top: 100%;
    left: 50%;
    transition: all 0.3s ease-in-out;
    transform: translate(-50%, 6%);
    z-index: 10;
}

.brd-nd-share li {
    padding-right: 0 !important;
    position: relative;
}

.socialLink ul {
    padding: 0;
    margin: 0;
    list-style: none;
}

.socialLink ul {
    list-style: none;
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 0;
    margin: 0;
}

.socialLink li a {
    text-decoration: none;
    display: flex;
    align-items: center;
    justify-content: center;
}

.socialIcon {
    font-size: 18px;
    color: #333;
    transition: transform 0.2s ease, color 0.3s;
}

.socialLink li a.facebook .socialIcon {
    color: #1877f2;
}

.socialLink li a.twitter .socialIcon {
    color: #1da1f2;
}

.socialLink li a.whatsapp .socialIcon {
    color: #25d366;
}

.socialLink li a.linkedin .socialIcon {
    color: #0077b5;
}

.socialIcon:hover {
    transform: scale(1.2);
}
</style>
