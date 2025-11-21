<template>
    <div class="mssg_bottom_part tuskri-chtrd-pnl-outr">
        <div class="mssg_body_outr">
            <div class="chat_body" v-if="messages.length" @scroll="handleScroll" ref="scrollableDiv">
                <div class="chatmsg-wrprs" v-for="message, index in messages" :key="index">
                    <div class="top_day_status"
                        v-if="dateSeparate(message.created_at, index != 0 ? messages[index - 1].created_at : '')">
                        <span class="chtdaybx">
                            {{ ListHelper.fancyDateFormat(message.created_at) }}
                        </span>
                    </div>
                    <div class="chatmsg-pnl"
                        :class="message.user_id == $page.props.auth.user.id ? 'reciver_box' : 'sender_box'">
                        <div class="chatmsg-pnl-inr">
                            <div class="cmnctn_user_prfle">
                                <img :src="message.user.profile_photo_url" alt="image"
                                    v-if="message.user.profile_photo_path" />
                                <div class="tuskricntct-chatfstltr" v-else>{{ message.user.full_name.slice(0, 1) }}
                                </div>
                            </div>
                            <div class="cmnctn_usr_txt_hlder">
                                <div class="each_chat_txt_wrppr">
                                    <div class="chatmsgtxtbx-wpr">
                                        <div class="each_chat_txt_box">
                                            <p v-if="message.attachment == null"> {{ message.message }}</p>
                                            <div v-else>
                                                <div v-if="imgType.includes(message.attachment_type)"
                                                    class="uploaded_imgFile">
                                                    <a :href="'/' + message.attachment" target="_blank"><img
                                                            :src="'/' + message.attachment" /></a>
                                                </div>
                                                <div v-if="fileType.includes(message.attachment_type)"
                                                    class="fileUploadIcon">
                                                    <a :href="'/' + message.attachment" target="_blank"
                                                        v-if="message.attachment_type == 'pdf'"><img
                                                            :src="'/admin_assets/pdf.svg'" alt="file-icon" /></a>
                                                    <a :href="'/' + message.attachment" target="_blank"
                                                        v-else-if="message.attachment_type == 'xls' || message.attachment_type == 'xlsx'"><img
                                                            :src="'/admin_assets/excel.svg'" alt="file-icon" /></a>
                                                    <a :href="'/' + message.attachment" target="_blank" v-else><img
                                                            :src="'/admin_assets/fileDocImg.svg'" alt="file-icon" /></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="chatmsg-date">{{ ListHelper.dateFormat(message.created_at, "hh:mm A")
                                    }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat_stcky_footer">
            <div class="tuskarichat-cmntarea">
                <form @submit.prevent="submit" class="chat_send_box">
                    <textarea class="autosize" placeholder="Write your message here" v-model="form.sendChat"
                        :disabled="isItFirstMessage" @keydown.enter.exact.prevent="submit">
                    </textarea>
                    <div class="tuskrichat-rgt-butnbx">
                        <div class="inputFileUpload">
                            <input type="file" @change="onFileChange">
                            <a href="javascript:void(0)" class="tuskrichat-upldbutn">
                                <img :src="'/frontend_assets/images/plus.svg'" alt="image" />
                            </a>
                        </div>
                        <button type="submit" class="chat-send-butn" value="Send" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script setup>
import { ref, onMounted, onUnmounted } from "vue";
import { useForm, usePage } from '@inertiajs/vue3';
import ListHelper from "@/helpers/ListHelper";

const page = usePage();
const props = defineProps({
    roomDetails: Object,
    firstMessage: String
});

const chatRoomId = ref({});
const chatRoom = ref({});
const messages = ref([]);
const chatName = ref({});
const isLoading = ref(false);
const currentPage = ref(0);
const lastPage = ref();
const scrollableDiv = ref();
const imgType = ['jpg', 'jpeg', 'png', 'svg'];
const fileType = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
const isItFirstMessage = ref(false);

chatName.value = props.roomDetails.chatName;
chatRoomId.value = props.roomDetails.chatRoom;


const getMessage = () => {
    if (currentPage.value != lastPage.value) {
        isLoading.value = true;
        axios.get("/get-message", {
            params: {
                room_id: chatRoomId.value,
                page: parseInt(currentPage.value) + 1
            }
        })
            .then((response) => {
                if (response.data.code == 404) {
                    form.sendChat = props?.firstMessage;
                    isItFirstMessage.value = true;
                }

                chatRoom.value = response.data.chatRoom;
                messages.value.unshift(...response.data.messages.data.reverse());
                currentPage.value = response.data.messages.current_page;
                lastPage.value = response.data.messages.last_page;
                isLoading.value = false;
            });
    }
}


const scrollToBottom = () => {
    setTimeout(() => {
        scrollableDiv.value.scrollTo({
            top: scrollableDiv.value.scrollHeight,
            behavior: 'smooth'
        });
    }, 1000);
}

onMounted(() => {
    getMessage();
    Echo.private('send-message.' + chatRoomId.value + '-' + page.props.auth.user.id).listen('SendMessageEvent', (e) => {
        messages.value.push(e.message);
        scrollToBottom();
    });
    scrollToBottom();
    onFocus();

    emit.on("deleteConfirm", function (arg1) {
        deleteConfirm(arg1);
    });
});

onUnmounted(() => {
    emit.off("deleteConfirm");
});

const dateSeparate = (cDate, pDate) => {
    if ((pDate == "") || (ListHelper.dateFormat(cDate, "MMM DD, YYYY") != ListHelper.dateFormat(pDate, "MMM DD, YYYY"))) {
        return true;
    }
    else {
        return false;
    }
};

const handleScroll = (event) => {
    const scrollTop = event.target.scrollTop;
    if (scrollTop == 0) {
        getMessage();

        if (currentPage.value != lastPage.value) {
            scrollableDiv.value.scrollTop = 20;
        }
    }
};

const form = useForm({
    sendChat: null,
    chatRoom: null,
    isNewUser: null,
    isMessage: 1,
    attachment: null,
});

function submit() {
    const trimmedMessage = form.sendChat ? form.sendChat.trim() : '';
    if (!trimmedMessage && !form.attachment) {
        toaster.error('Please enter a message or upload a file.');
        return;
    }
    
    form.chatRoom = (chatRoom.value != null) ? chatRoom.value.id : null;
    if (chatRoom.value === null) {
        form.isNewUser = props.roomDetails.chatRoomUser.id;
    }

    if (form.sendChat != null || form.attachment != null) {
        form.isMessage = form.sendChat != null ? 1 : 0;
        axios.post(route('frontend.send-message'), form, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        }).then((res) => {
            if (res.data.code == 200) {
                if (localStorage.getItem('chatDetailsData')) {
                    emit.emit('isClearLocalStorage', true);
                }
                const audio = new Audio('/send_sound.mp3');
                audio.currentTime = 0;
                audio.play().catch(err => console.warn("Audio play blocked:", err));
                form.sendChat = null;
                form.chatRoom = res.data.chatRoom.id;
                chatRoom.value = res.data.chatRoom;
                form.isNewUser = null;
                isItFirstMessage.value = false;
                messages.value.push(res.data.message);
                emit.emit('latestChat', res.data.newChat);
                scrollToBottom();

            }
        }).catch((error) => {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                for (const key in errors) {
                    if (errors.hasOwnProperty(key)) {
                        toaster.error(errors[key][0]);
                    }
                }
            } else {
                console.log(error);
                toaster.error('Something went wrong. Please try again.');
            }
        })
    }
}

const onFocus = () => {
    const text = $('.autosize');
    text.each(function () {
        $(this).attr('rows', 1);
        resize($(this));
    });
    text.on('input', function () {
        resize($(this));
    });
    function resize($text) {
        $text.css('height', 'auto');
        $text.css('height', $text[0].scrollHeight + 'px');
    }
}

function onFileChange(event) {
    form.attachment = event.target.files[0];
    const extension = form.attachment.name.split('.').pop();
    if (imgType.includes(extension) || fileType.includes(extension)) {
        submit();
        form.attachment = null;
    }
    else {
        toaster.error(`Invalid file type: .${extension}. Allowed types are: ${[...imgType, ...fileType].join(', ')}`);
        form.attachment = null;
        event.target.value = '';
    }
}

function deleteMessage(messageId) {
    sw.confirm("deleteConfirm", messageId);
}

const deleteConfirm = (id) => {
    axios.delete(route('frontend.delete-message', { id: id }))
        .then((response) => {
            if (response.data.success) {
                const index = messages.value.findIndex(message => message.id === id);
                if (index !== -1) {
                    messages.value.splice(index, 1);
                }
            }
        })
        .catch((error) => {
            console.log(error);
        });
};

</script>