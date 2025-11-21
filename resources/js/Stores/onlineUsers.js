import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useOnlineUsersStore = defineStore('onlineUsers', () => {
    const onlineUsers = ref([]);

    const setOnlineUsers = (users) => {
        onlineUsers.value = users;
    };

    const addUser = (user) => {
        if (!onlineUsers.value.some(u => u.id === user.id)) {
            onlineUsers.value.push(user);
        }
    };

    const removeUser = (user) => {
        onlineUsers.value = onlineUsers.value.filter(u => u.id !== user.id);
    };

    const isUserOnline = (userId) => {
        return onlineUsers.value.some(u => u.id === userId);
    };

    return {
        onlineUsers,
        setOnlineUsers,
        addUser,
        removeUser,
        isUserOnline,
    };
});
