<template>
    <div class="panel_rght_cntnt_area">
        <div class="trips_wrppr mt-0 each_equal_cntnr mmsge_area">
            <div class="row mssgchat-row gx-0">
                <div class="col-md-3 mssg_lft_pnlCol">
                    <div class="mssg_pnl target_height">
                        <div class="mssg_top_pnl stcky_part">
                            <h2>Messages </h2>
                            <div class="hder_srch_box mssg_srch_box">
                                <input type="text" placeholder="Search" v-model="form.searchChatUser">
                            </div>
                        </div>
                        <div class="mssg_bttm_pnl">
                            <div class="mssg_bttm_pnl_inner scroll_area" v-if="chat_list.length > 0">
                                <div class="mssg_invdl_box" v-for="chat, index in chat_list" :key="index">
                                    <a class="mssg_invdl_lnk" href="javascript:void(0);"
                                        @click="openChat(chat?.id, chat?.is_group, chat?.chat_members, chat?.is_group == 1 ? chat?.chat_name : chat.chat_members[0]?.user?.full_name)">
                                        <div class="mssg_usr_img_box">
                                            <img :src="chat?.chat_members[0]?.user.profile_photo_url ?? '/frontend_assets/images/profileimg1.jpg'"
                                                alt="Profile img">
                                            <UserStatus :userId="chat?.chat_members[0]?.user?.id" />
                                        </div>
                                        <div class="mssg_usr_rghtPart">
                                            <div class="mssg_usr_top_prt">
                                                <p>
                                                    {{ chat.chat_members[0]?.user?.first_name }}
                                                    {{ chat.chat_members[0]?.user?.business_name ? '@ ' +
                                                        chat.chat_members[0]?.user?.business_name : '' }}
                                                </p>
                                                <div class="time-delete-wrapper">
                                                    <div class="time-delete-wrp-left">
                                                        <div class="data-wrp" v-if="ListHelper.fancyDateFormatShort(chat?.last_message?.created_at)!=='Today'">
                                                            {{ ListHelper.fancyDateFormatShort(chat?.last_message?.created_at) }}
                                                        </div>
                                                    <em v-if="ListHelper.fancyDateFormatShort(chat?.last_message?.created_at)=='Today'">{{ ListHelper.dateFormat(chat?.last_message?.created_at, "hh:mm A")}}</em>
                                                    </div>
                                                    <a href="javascript:void(0)" @click.stop="deleteRecode(chat.id)" class="cmn-butn delect delete-btn-right" style="border: none"><img
                                                        :src="'/frontend_assets/images/trash-2.svg'" alt="Delete" class="edit-img">
                                                    </a>
                                                </div>
                                            </div>
                                            <span v-if="chat?.last_message?.message != null" class="msgwrap-txt">{{
                                                chat?.last_message?.message }}</span>
                                            <span v-else class="msgwrap-pin"><img
                                                    :src="'frontend_assets/images/pin.svg'" alt="image" height="15"
                                                    width="25"></span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div>
                                <h3 v-if="!chat_list.length" class="text-center mt-5">No messages found.</h3>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-9 mssg_rght_pnlCol">
                    <div ref="chatBox" class="mssg_chat_box equal_height" :class="{'on': existingChat}"
                        v-if="chatDetails.chatRoom != 0 || chatDetails?.chatRoomUser.id != null && (windowWidth > 991 || showMobileChat)">
                        <div class="mssg_sticky_top_bar">
                            <a href="javascript:void(0);" class="chatbackarw" @click.prevent="closeChatBoxSideBar"><img
                                    :src="'/frontend_assets/images/backarrow.png'" alt="Arrow"
                                    @click.prevent="showMobileChat = false"></a>
                            <div class="user_chat_lft_part">
                                <span href="javascript:void(0)" class="user_chat_img">
                                    <img :src="chatDetails?.chatRoomUser?.profile_photo_url ?? '/frontend_assets/images/user_chat_prfle.jpg'"
                                        alt="Chat Img">
                                </span>
                                <div class="user_top_chat_info">
                                    <p>{{ chatDetails?.chatRoomUser?.full_name }}</p>
                                    <UserStatus :userId="chatDetails?.chatRoomUser?.id" />
                                </div>
                            </div>
                        </div>
                        <SendMessage :key="renderKey" :roomDetails="chatDetails" :firstMessage="firstMessage" :setting="setting" />
                    </div>
                    <div v-else>
                        <h4 class="text-center mt-5">Please select a chat</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { onMounted, onUnmounted, reactive, ref, watch } from 'vue'
import { homeJs } from "@/custom.js";
import SendMessage from '@/components/Frontend/SendMessage.vue'
import { router, useForm, usePage } from '@inertiajs/vue3';
import ListHelper from "@/helpers/ListHelper";
import UserStatus from '@/components/Frontend/UserStatus.vue';

const props = defineProps({
    chat_list: Object,
    setting: Object,
});

const showMobileChat = ref(false);
const windowWidth = ref(window.innerWidth);
const chats = ref({});
const renderKey = ref(0);
const existingChat = ref(localStorage.getItem('chatDetailsData'));
const dashboardChatRoom = ref(localStorage.getItem('chatRoomStored'));
const chatRoomId = ref(0);
const isNewGroup = ref(null);
const chatNewMembers = ref({});
const chatNewName = ref('');
const firstMessage = ref('');
const chatBox = ref(null);
const page = usePage();

const form = useForm({
    searchChatUser: '',
});

const updateWindowWidth = () => {
    windowWidth.value = window.innerWidth
}

const chatDetails = reactive({
    'chatRoom': 0,
    'chatRoomUser': '',
    'isGroupChat': '',
    'chatName': '',
});

const openChat = (id = 0, isGroup = '', chatMembers, chatName) => {

    chatRoomId.value = id;
    isNewGroup.value = isGroup;
    chatNewMembers.value = chatMembers;
    chatNewName.value = chatName;

    if (!existingChat.value && !dashboardChatRoom.value) {
        chatDetails.chatRoom = id;
        chatDetails.isGroupChat = isGroup;
        chatDetails.chatName = chatName;
        if (isGroup == 0) {
            chatDetails.chatRoomUser = chatMembers[0].user;
        } else {
            chatDetails.chatRoomUser = chatMembers;
        }
        renderKey.value++;
    } else {
        const parsed = JSON.parse(existingChat.value ?? dashboardChatRoom.value);
        chatDetails.chatRoom = parsed.chatRoom;
        chatDetails.chatRoomUser = parsed.chatRoomUser;
        chatDetails.isGroupChat = parsed.isGroupChat;
        chatDetails.chatName = parsed.chatName;
        firstMessage.value = parsed.autoGenerateMessage;
        renderKey.value++;
    }

    if (windowWidth.value <= 991) {
        showMobileChat.value = true
    }
};

const clearLocalStorageOnClick = (event) => {
    if (event.target.closest('a') && (localStorage.getItem('chatDetailsData') || localStorage.getItem('chatRoomStored'))) {
        existingChat.value = null;
        dashboardChatRoom.value = null;
        localStorage.removeItem('chatDetailsData');
        localStorage.removeItem('chatRoomStored');
        openChat(chatRoomId.value, isNewGroup.value, chatNewMembers.value, chatNewName.value);
    }
};

onMounted(() => {
    homeJs();

    if (page.props.auth.user.role == 'TRAVELLER') {
        emit.emit("pageName", "Your Conversations");
        emit.emit("pageSubTitle", "Chat directly with operators and guides");
    } else {
        emit.emit("pageName", "Guest Conversations");
        emit.emit("pageSubTitle", "Respond to enquiries and build connections");
    }


    window.addEventListener('resize', updateWindowWidth)
    window.addEventListener('click', clearLocalStorageOnClick);

    emit.on('isClearLocalStorage', function (arg1) {
        existingChat.value = null;
        dashboardChatRoom.value = null;
        localStorage.removeItem('chatDetailsData');
        localStorage.removeItem('chatRoomStored');
    })

    if (existingChat.value || dashboardChatRoom.value) {
        openChat();
    }

    chats.value = props.chat_list;
    emit.on('latestChat', function (latestChat) {
        const chatIndex = chats.value.map(e => e.id).indexOf(latestChat.id);
        if (chats.value.length > 0) {
            if (chatIndex >= 0) {
                chats.value.splice(chatIndex, 1);
                chats.value.unshift(latestChat);
            }
            else {
                chats.value.unshift(latestChat);
            }
        }
        else {
            chats.value.push(latestChat);
        }
        chatDetails.chatRoom = latestChat.id;
    });

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });
});

onUnmounted(() => {
    window.removeEventListener('resize', updateWindowWidth)
    window.removeEventListener('click', clearLocalStorageOnClick);
})

watch(() => form.searchChatUser, () => {
    form.post(route('frontend.messages'), {
        preserveScroll: true
    });
});

const closeChatBoxSideBar = () => {
    chatBox.value.classList.remove('on')
}
const deleteRecode = (id) => {
    sw.confirm(
        "deleteConfirm",
        id,
        "Are you sure?",
        "You won't be able to revert this chat!",
        "Yes, Delete it!"
    );
};
const deleteConfirm = (id) => {
       router.post(route("frontend.delete-chat"), {
            id: id,
       });
};

</script>

<style scoped>
.mssg_invdl_box {
    position: relative;
}

.time-delete-wrapper {
    display: flex;
    align-items: center;
    gap: 8px;
}

.delete-btn-right {
    padding: 4px !important;
    background: transparent !important;
    margin: 0;
}

.delete-btn-right img {
    width: 16px;
    height: 16px;
}
</style>