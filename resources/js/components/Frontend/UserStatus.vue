<template>
    <span v-if="isOnline" class="cht_stus onlne_stus">Online</span>
    <span v-else-if="props?.fastestReplyTime" class="cht_stus hibrntn_stus">{{ computedReplyTime }}</span>
    <span v-else class="cht_stus offlne_stus">
        Offline · <span class="italic">Active {{ lastSeenText }}</span>
    </span>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import { route } from 'ziggy-js';
import { useOnlineUsersStore } from '@/Stores/onlineUsers';
import updateLocale from 'dayjs/plugin/updateLocale';

dayjs.extend(relativeTime);
dayjs.extend(updateLocale);

dayjs.updateLocale('en', {
    relativeTime: {
        future: 'in %s',
        past: '%s ago',
        s: 'few seconds',  // for durations < 45s
        m: '1m',
        mm: '%dm',
        h: '1h',
        hh: '%dh',
        d: '1d',
        dd: '%dd',
        M: '1mo',
        MM: '%dmo',
        y: '1y',
        yy: '%dy'
    }
});


const props = defineProps({
    userId: Number,
    fastestReplyTime: Number,
});

const store = useOnlineUsersStore();
const isOnline = computed(() => store.isUserOnline(props.userId));

const lastSeen = ref(null);
const lastSeenText = computed(() => {
    if (!lastSeen.value) return 'unknown';
    return dayjs(lastSeen.value).fromNow();
});

const fetchLastSeen = async () => {
    try {
        const res = await axios.get(route('frontend.get-users', { id: props.userId }));
        lastSeen.value = res.data.last_seen_at;
    } catch (e) {
        console.error('Failed to fetch last seen:', e);
    }
};

onMounted(() => {
    fetchLastSeen();
});

watch(() => props.userId, () => {
    fetchLastSeen();
});

const computedReplyTime = computed(() => {
    if (!props.fastestReplyTime) return null;

    const minResponseTime = parseInt(props.fastestReplyTime);
    if (isNaN(minResponseTime)) return null;

    const minutes = Math.floor(minResponseTime / 60);

    if (minutes >= 60) {
        const hours = Math.round(minutes / 60);
        return `Usually replies within ${hours}h`;
    } else if (minutes > 0) {
        return `Usually replies within ${minutes}m`;
    } else {
        const secs = Math.max(1, Math.round(minResponseTime)); // at least 1 second
        return `Usually replies within ${secs}s`;
    }
});


</script>
