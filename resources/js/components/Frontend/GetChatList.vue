<template>
    <div class="col-md-3 mssg_lft_pnlCol">
        <div class="mssg_pnl target_height">
            <div class="mssg_top_pnl stcky_part">
                <h2>Messages</h2>
                <div class="hder_srch_box mssg_srch_box">
                    <input type="text" placeholder="Search">
                </div>
            </div>
            <div class="mssg_bttm_pnl">
                <div class="mssg_bttm_pnl_inner scroll_area" v-if="chat_list.length > 0">
                    <div class="mssg_invdl_box" v-for="chat, index in chat_list" :key="index">

                        <a class="mssg_invdl_lnk" href="javascript:void(0);"
                            @click="openChat(chat.id, chat.is_group, chat.chat_members, chat.is_group == 1 ? chat.chat_name : chat.chat_members[0].user.full_name)">
                            <div class="mssg_usr_img_box">
                                <img :src="chat.chat_members[0].user.profile_photo_url ?? '/frontend_assets/images/profileimg1.jpg'"
                                    alt="Profile img">

                            </div>
                            <div class="mssg_usr_rghtPart">
                                <div class="mssg_usr_top_prt">
                                    <p>{{ chat.chat_members[0].user.full_name }}</p>
                                    <em></em>
                                </div>
                                <span>{{ chat?.last_message }}</span>
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
</template>

<script setup>
</script>