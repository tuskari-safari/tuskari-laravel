<template>
    <div>
        <!-- Scroll to Top Button -->
        <transition name="fade">
            <span v-if="isScrollTopVisible" class="scroll-button scroll-top" @click="scrollToTop">
                <i class="fas fa-angle-up"></i>
            </span>
        </transition>

        <!-- Scroll to Bottom Button -->
        <transition name="fade">
            <span v-if="isScrollBottomVisible" class="scroll-button scroll-bottom" @click="scrollToBottom">
                <i class="fas fa-angle-down"></i>
            </span>
        </transition>
    </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue';

const isScrollTopVisible = ref(false);
const isScrollBottomVisible = ref(true);

const scrollToTop = () => {
    window.scrollTo({
        top: 0,
        behavior: 'smooth',
    });
};

const scrollToBottom = () => {
    window.scrollTo({
        top: document.documentElement.scrollHeight,
        behavior: 'smooth',
    });
};

const handleScroll = () => {
    const scrollTop = window.scrollY || window.pageYOffset;
    const windowHeight = window.innerHeight;
    const documentHeight = document.documentElement.scrollHeight;

    isScrollTopVisible.value = scrollTop > 200;
    isScrollBottomVisible.value = scrollTop + windowHeight < documentHeight - 200;
};

onMounted(() => {
    window.addEventListener('scroll', handleScroll);
    handleScroll();
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});
</script>

<style scoped>
/* Fade transition */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.4s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Shared scroll button styles */
.scroll-button {
    position: fixed;
    right: 20px;
    width: 44px;
    height: 44px;
    background: #485648;
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 1000;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
    transition: transform 0.2s, background 0.3s, box-shadow 0.3s;
}

/* Positioning */
.scroll-top {
    bottom: 80px;
}

.scroll-bottom {
    bottom: 20px;
}

/* Hover effects */
.scroll-button:hover {
    background: #C49A6C;
    transform: scale(1.1);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
}
</style>
