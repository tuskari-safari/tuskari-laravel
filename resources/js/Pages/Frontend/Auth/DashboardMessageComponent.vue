<template>
    <div class="col-lg-3 pnl_rghtCntnt_rghtPart">
        <div class="mssg_pnl target_height">
            <div class="mssg_top_pnl stcky_part">
                <h2>Recent Messages</h2>
                <div class="hder_srch_box mssg_srch_box">
                    <input type="text" placeholder="Search" v-model="searchChatUser">
                </div>
            </div>
            <div class="mssg_bttm_pnl">
                <div class="mssg_bttm_pnl_inner scroll_area" v-if="chatLists.length > 0">
                    <div class="mssg_invdl_box" v-for="chat, index in chatLists" :key="index">
                        <a href="javascript:void(0);" class="mssg_invdl_lnk"
                            @click.prevent="chatWithOperator(chat.id, chat.is_group, chat?.chat_members, chat?.is_group == 1 ? chat?.chat_name : chat.chat_members[0]?.user?.full_name)">
                            <div class="mssg_usr_img_box">
                                <img :src="chat.chat_members[0]?.user.profile_photo_url ?? '/frontend_assets/images/profileimg1.jpg'"
                                    alt="Profile img">
                                <UserStatus :userId="chat?.chat_members[0]?.user?.id" />

                            </div>
                            <div class="mssg_usr_rghtPart">
                                <div class="mssg_usr_top_prt">
                                    <p>
                                        {{ chat?.chat_members[0]?.user?.first_name }}
                                        {{ chat?.chat_members[0]?.user?.business_name ? '@ ' +
                                            chat?.chat_members[0]?.user?.business_name : '' }}
                                    </p>
                                    <div class="time-delete-wrp-left">
                                        <div class="data-wrp" v-if="ListHelper.fancyDateFormatShort(chat?.last_message?.created_at)!=='Today'">
                                            {{ ListHelper.fancyDateFormatShort(chat?.last_message?.created_at) }}
                                        </div>
                                        <em v-if="ListHelper.fancyDateFormatShort(chat?.last_message?.created_at)=='Today'">{{
                                            ListHelper.dateFormat(chat?.last_message?.created_at, "hh:mm A")}}</em>
                                    </div>
                                </div>

                                <span v-if="chat?.last_message?.message != null">{{
                                    chat?.last_message?.message }}</span>
                                <span v-else class="msgwrap-pin"><img :src="'frontend_assets/images/pin.svg'"
                                        alt="image" height="15" width="25"></span>
                            </div>
                        </a>
                    </div>
                </div>
                <div>
                    <h3 v-if="!chatLists?.length" class="text-center mt-5">No messages found.</h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'
import ListHelper from "@/helpers/ListHelper";
import UserStatus from '@/components/Frontend/UserStatus.vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const chatLists = ref([]);
const chats = ref([]);
const searchChatUser = ref('');

onMounted(() => {
    getChat();
    chats.value = chatLists.value;
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
});

watch(searchChatUser, async (newVal) => {
    try {
        const response = await axios.get(route('frontend.get-chat-for-dashboard'), {
            params: { searchChatUser: newVal }
        });
        chatLists.value = response.data.chat_list;
    } catch (error) {
        console.error('Error fetching chat list:', error);
    }
});

const chatWithOperator = (id, isGroup, chatMembers, chatName) => {
    localStorage.removeItem('chatRoomStored');
    const chatDetails = {
        chatRoom: id,
        chatRoomUser: chatMembers[0].user,
        chatName: chatName,
    };
    localStorage.setItem('chatRoomStored', JSON.stringify(chatDetails));
    router.get(route('frontend.messages'));
};

const getChat = async () => {
    try {
        const response = await axios.get(route('frontend.get-chat-for-dashboard'));
        chatLists.value = response.data.chat_list;
    } catch (error) {
        console.log(error);
    }
}
</script>