<template>
    <div class="mssg_bottom_part tuskri-chtrd-pnl-outr">
        <!-- Enquiry Context Panel -->
        <div v-if="enquiry" class="enquiry-context-panel">
            <div class="enquiry-header">
                <div class="enquiry-safari-info">
                    <strong>{{ enquiry.safari?.title }}</strong>
                    <div class="enquiry-details">
                        <span>{{ formatDate(enquiry.check_in_date) }} - {{ formatDate(enquiry.check_out_date) }}</span>
                        <span class="separator">|</span>
                        <span>{{ enquiry.no_of_adults }} adult(s)<span v-if="enquiry.no_of_children > 0">, {{ enquiry.no_of_children }} child(ren)</span></span>
                    </div>
                </div>
                <div class="enquiry-status">
                    <span :class="'status-badge status-' + enquiry.enquiry_status">{{ capitalizeFirst(enquiry.enquiry_status) }}</span>
                </div>
            </div>

            <!-- Operator: Quote Form (pending status) -->
            <div v-if="isOperator && enquiry.enquiry_status === 'pending'" class="enquiry-actions">
                <div class="quote-form">
                    <label>Total Price (includes 13% platform fee)</label>
                    <div class="quote-input-row">
                        <span class="currency-symbol">$</span>
                        <input type="number" v-model="quoteAmount" min="1" step="0.01" placeholder="Enter total price" class="quote-input" />
                        <button @click="submitQuote" :disabled="isSubmittingQuote || !quoteAmount" class="cmn-butn quote-btn">
                            {{ isSubmittingQuote ? 'Sending...' : 'Send Quote' }}
                        </button>
                    </div>
                </div>
            </div>

            <!-- Operator: Quote sent (quoted status) -->
            <div v-if="isOperator && enquiry.enquiry_status === 'quoted'" class="enquiry-actions">
                <div class="quoted-info">
                    <p>Quote sent: <strong>${{ formatPrice(enquiry.quoted_total_price) }}</strong></p>
                    <p class="text-muted">Waiting for traveler to confirm...</p>
                </div>
            </div>

            <!-- Traveler: View quote & confirm/decline (quoted status) -->
            <div v-if="!isOperator && enquiry.enquiry_status === 'quoted'" class="enquiry-actions">
                <div class="quote-received">
                    <p class="quote-price">Quoted Price: <strong>${{ formatPrice(enquiry.quoted_total_price) }}</strong></p>
                    <div class="action-buttons">
                        <button @click="confirmEnquiry" :disabled="isProcessing" class="cmn-butn confirm-btn">
                            {{ isProcessing ? 'Processing...' : 'Confirm & Pay' }}
                        </button>
                        <button @click="showDeclineModal = true" :disabled="isProcessing" class="cmn-butn decline-btn">
                            Decline
                        </button>
                    </div>
                </div>
            </div>

            <!-- Traveler: Pending status -->
            <div v-if="!isOperator && enquiry.enquiry_status === 'pending'" class="enquiry-actions">
                <p class="text-muted">Waiting for operator to send a quote...</p>
                <button @click="showDeclineModal = true" class="cmn-butn decline-btn-small">Cancel Enquiry</button>
            </div>

            <!-- Both: Decline modal -->
            <div v-if="showDeclineModal" class="decline-modal-overlay" @click.self="showDeclineModal = false">
                <div class="decline-modal">
                    <h4>Decline Enquiry</h4>
                    <textarea v-model="declineReason" placeholder="Reason (optional)" rows="3"></textarea>
                    <div class="modal-actions">
                        <button @click="showDeclineModal = false" class="cmn-butn cancel-btn">Cancel</button>
                        <button @click="declineEnquiry" :disabled="isDeclining" class="cmn-butn decline-btn">
                            {{ isDeclining ? 'Declining...' : 'Confirm Decline' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

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
                                            <p v-if="message.attachment == null" class="message-text"> {{ message.message }}</p>
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
import { useForm, usePage, router } from '@inertiajs/vue3';
import ListHelper from "@/helpers/ListHelper";
import { route } from 'ziggy-js';

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

// Enquiry-related state
const enquiry = ref(null);
const isOperator = ref(false);
const quoteAmount = ref('');
const isSubmittingQuote = ref(false);
const isProcessing = ref(false);
const showDeclineModal = ref(false);
const declineReason = ref('');
const isDeclining = ref(false);

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
    fetchEnquiry();
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

// Enquiry-related methods
const fetchEnquiry = () => {
    if (!chatRoomId.value) return;

    axios.get(route('frontend.get-enquiry'), {
        params: { chat_room_id: chatRoomId.value }
    })
    .then((response) => {
        if (response.data.enquiry) {
            enquiry.value = response.data.enquiry;
            isOperator.value = response.data.is_operator;
        }
    })
    .catch((error) => {
        console.log('Error fetching enquiry:', error);
    });
};

const formatDate = (dateStr) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
};

const formatPrice = (price) => {
    return Number(price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
};

const capitalizeFirst = (str) => {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
};

const submitQuote = () => {
    if (!quoteAmount.value || isSubmittingQuote.value) return;

    isSubmittingQuote.value = true;
    axios.post(route('frontend.quote-enquiry'), {
        enquiry_id: enquiry.value.id,
        quoted_total_price: quoteAmount.value
    })
    .then((response) => {
        toaster.success('Quote sent successfully!');
        enquiry.value = response.data.enquiry;
        quoteAmount.value = '';
        // Refresh messages to show the quote message
        currentPage.value = 0;
        lastPage.value = null;
        messages.value = [];
        getMessage();
    })
    .catch((error) => {
        if (error.response?.data?.error) {
            toaster.error(error.response.data.error);
        } else {
            toaster.error('Failed to send quote. Please try again.');
        }
    })
    .finally(() => {
        isSubmittingQuote.value = false;
    });
};

const confirmEnquiry = () => {
    if (isProcessing.value) return;

    isProcessing.value = true;
    axios.post(route('frontend.confirm-enquiry'), {
        enquiry_id: enquiry.value.id
    })
    .then((response) => {
        toaster.success('Enquiry confirmed! Redirecting to checkout...');
        if (response.data.redirect_url) {
            router.visit(response.data.redirect_url);
        }
    })
    .catch((error) => {
        if (error.response?.data?.error) {
            toaster.error(error.response.data.error);
        } else {
            toaster.error('Failed to confirm enquiry. Please try again.');
        }
    })
    .finally(() => {
        isProcessing.value = false;
    });
};

const declineEnquiry = () => {
    if (isDeclining.value) return;

    isDeclining.value = true;
    axios.post(route('frontend.decline-enquiry'), {
        enquiry_id: enquiry.value.id,
        reason: declineReason.value
    })
    .then(() => {
        toaster.success('Enquiry declined.');
        enquiry.value = null;
        showDeclineModal.value = false;
        declineReason.value = '';
        // Refresh messages
        currentPage.value = 0;
        lastPage.value = null;
        messages.value = [];
        getMessage();
    })
    .catch((error) => {
        if (error.response?.data?.error) {
            toaster.error(error.response.data.error);
        } else {
            toaster.error('Failed to decline enquiry. Please try again.');
        }
    })
    .finally(() => {
        isDeclining.value = false;
    });
};

</script>

<style scoped>
.enquiry-context-panel {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 16px;
    margin-bottom: 0;
}

.enquiry-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    flex-wrap: wrap;
}

.enquiry-safari-info strong {
    font-size: 16px;
    color: #333;
    display: block;
    margin-bottom: 4px;
}

.enquiry-details {
    font-size: 13px;
    color: #666;
}

.enquiry-details .separator {
    margin: 0 8px;
    color: #ccc;
}

.status-badge {
    display: inline-block;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-quoted {
    background: #cce5ff;
    color: #004085;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-declined {
    background: #f8d7da;
    color: #721c24;
}

.enquiry-actions {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #dee2e6;
}

.quote-form label {
    display: block;
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
}

.quote-input-row {
    display: flex;
    align-items: center;
    gap: 8px;
}

.currency-symbol {
    font-size: 16px;
    color: #333;
    font-weight: 600;
}

.quote-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    font-size: 14px;
    max-width: 150px;
}

.quote-btn {
    padding: 8px 16px !important;
    font-size: 13px !important;
}

.quoted-info p {
    margin: 0;
}

.quote-received .quote-price {
    font-size: 18px;
    margin-bottom: 12px;
}

.action-buttons {
    display: flex;
    gap: 12px;
}

.confirm-btn {
    background: #28a745 !important;
    color: white !important;
}

.confirm-btn:hover {
    background: #218838 !important;
}

.decline-btn, .decline-btn-small {
    background: #dc3545 !important;
    color: white !important;
}

.decline-btn:hover, .decline-btn-small:hover {
    background: #c82333 !important;
}

.decline-btn-small {
    padding: 6px 12px !important;
    font-size: 12px !important;
}

.cancel-btn {
    background: #6c757d !important;
    color: white !important;
}

.text-muted {
    color: #6c757d;
    font-size: 13px;
}

.decline-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.decline-modal {
    background: white;
    padding: 24px;
    border-radius: 12px;
    width: 100%;
    max-width: 400px;
    margin: 16px;
}

.decline-modal h4 {
    margin: 0 0 16px;
    font-size: 18px;
}

.decline-modal textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ced4da;
    border-radius: 6px;
    resize: vertical;
    font-family: inherit;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    margin-top: 16px;
}

.message-text {
    white-space: pre-line;
    margin: 0;
}

/* Fix chat layout when enquiry panel is present */
.mssg_bottom_part.tuskri-chtrd-pnl-outr {
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.mssg_bottom_part.tuskri-chtrd-pnl-outr .mssg_body_outr {
    flex: 1;
    min-height: 0;
    height: auto;
    overflow: hidden;
}

.mssg_bottom_part.tuskri-chtrd-pnl-outr .chat_body {
    height: 100%;
    overflow-y: auto;
}

.mssg_bottom_part.tuskri-chtrd-pnl-outr .chat_stcky_footer {
    flex-shrink: 0;
}
</style>