<template>
    <header
        class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 text-white shadow-lg border-b border-white/10"
    >
        <div class="flex items-center justify-between px-8 py-4">
            <!-- LEFT -->
            <div class="flex items-center gap-4">
                <!-- Logo -->
                <div
                    class="w-14 h-14 rounded-2xl bg-white/10 backdrop-blur flex items-center justify-center border border-white/20 shadow"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 14l9-5-9-5-9 5 9 5zm0 0v6"
                        />
                    </svg>
                </div>

                <!-- Title -->
                <div>
                    <h1 class="text-2xl font-bold tracking-wide">
                        HỆ THỐNG QUẢN LÝ LUẬN VĂN
                    </h1>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                        <p class="text-sm text-blue-100 tracking-wide">
                            KHOA CÔNG NGHỆ THÔNG TIN
                        </p>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="flex items-center gap-4">
                <!-- CHAT BUTTON (only if student has a guide teacher) -->
                <button
                    v-if="canChat"
                    @click="toggleChat"
                    class="relative w-12 h-12 rounded-2xl bg-white/10 hover:bg-white/20 border border-white/10 backdrop-blur flex items-center justify-center transition-all duration-200 shadow"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-white"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"
                        />
                    </svg>
                    <!-- Badge (optional, static for now) -->
                    <div
                        class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center border border-slate-900"
                    >
                        3
                    </div>
                </button>

                <!-- CHAT POPUP -->
                <transition
                    enter-active-class="transition duration-200 ease-out"
                    enter-from-class="opacity-0 scale-95"
                    enter-to-class="opacity-100 scale-100"
                    leave-active-class="transition duration-150 ease-in"
                    leave-from-class="opacity-100 scale-100"
                    leave-to-class="opacity-0 scale-95"
                >
                    <div
                        v-if="showChat"
                        class="absolute top-20 right-28 w-[360px] h-[500px] bg-white rounded-3xl shadow-2xl overflow-hidden z-50 border border-slate-200 flex flex-col"
                    >
                        <!-- TOP BAR -->
                        <div
                            class="px-5 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white flex items-center justify-between"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-full bg-white text-blue-700 font-bold flex items-center justify-center"
                                >
                                    {{ contactInitial }}
                                </div>
                                <div>
                                    <h3 class="font-bold">{{ contactName }}</h3>
                                    <p class="text-xs text-blue-100">{{ contactStatus }}</p>
                                </div>
                            </div>
                            <button
                                @click="toggleChat"
                                class="w-8 h-8 rounded-xl hover:bg-white/10 flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- MESSAGES AREA -->
                        <div
                            class="flex-1 overflow-y-auto p-5 space-y-4 bg-slate-50"
                            ref="messageContainer"
                        >
                            <div
                                v-for="msg in messages"
                                :key="msg.id"
                                :class="[
                                    'flex',
                                    msg.sender_id === myId && msg.sender_type === myType
                                        ? 'justify-end'
                                        : ''
                                ]"
                            >
                                <div
                                    :class="[
                                        'max-w-[75%] px-4 py-3 rounded-2xl shadow-sm',
                                        msg.sender_id === myId && msg.sender_type === myType
                                            ? 'bg-blue-600 text-white'
                                            : 'bg-white text-slate-700'
                                    ]"
                                >
                                    {{ msg.body }}
                                </div>
                            </div>
                            <div v-if="loading" class="text-center text-slate-400">
                                Đang tải...
                            </div>
                        </div>

                        <!-- INPUT AREA -->
                        <div class="p-4 border-t bg-white flex items-center gap-3">
                            <input
                                v-model="newMessage"
                                @keyup.enter="sendMessage"
                                type="text"
                                placeholder="Nhập tin nhắn..."
                                class="text-gray-700 flex-1 h-12 rounded-2xl border border-slate-200 px-4 outline-none focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
                            />
                            <button
                                @click="sendMessage"
                                class="w-12 h-12 rounded-2xl bg-blue-600 hover:bg-blue-700 text-white flex items-center justify-center shadow transition"
                            >
                                ➤
                            </button>
                        </div>
                    </div>
                </transition>

                <!-- USER DROPDOWN (unchanged) -->
                <div class="relative">
                    <div
                        @click="toggleMenu"
                        class="flex items-center gap-3 px-3 py-2 rounded-2xl hover:bg-white/10 transition cursor-pointer border border-white/10"
                    >
                        <div
                            class="w-11 h-11 rounded-full bg-white text-blue-700 font-bold text-lg flex items-center justify-center shadow-md"
                        >
                            {{ user?.name?.charAt(0)?.toUpperCase() || "A" }}
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-sm">{{ user?.name || "Admin" }}</p>
                            <p class="text-xs text-blue-100">Sinh viên</p>
                        </div>
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="w-4 h-4 text-blue-100"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 9l-7 7-7-7"
                            />
                        </svg>
                    </div>
                    <transition
                        enter-active-class="transition duration-200 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-150 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div
                            v-if="showMenu"
                            class="absolute right-0 mt-3 w-52 bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-100 z-50"
                        >
                            <button
                                @click="Profile"
                                class="w-full flex items-center gap-3 px-5 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                            >
                                👤 Trang cá nhân
                            </button>
                            <div class="border-t"></div>
                            <button
                                @click="Logout"
                                class="w-full flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition"
                            >
                                🚪 Đăng xuất
                            </button>
                        </div>
                    </transition>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, nextTick } from "vue"
import { router } from "@inertiajs/vue3"
import axios from "axios"

// --- Props ---
const { user } = defineProps({
    user: Object,
})

// --- UI state ---
const showMenu = ref(false)
const showChat = ref(false)

// --- Chat state ---
const messages = ref([])
const newMessage = ref("")
const loading = ref(false)
const messageContainer = ref(null)

// --- Student identity ---
const myType = "SinhVien"
const myId = computed(() => user?.sinh_vien?.MSSV)

// --- Guide teacher info ---
const contactType = "GiangVien"
const contactId = computed(() => user?.sinh_vien?.Giang_vien_huong_dan)
const contactName = computed(
    () => user?.sinh_vien?.giang_vien_huong_dan?.Ho_va_Ten ?? "Giảng viên"
)
const contactInitial = computed(() => contactName.value?.charAt(0) ?? "G")
const contactStatus = "Đang hoạt động"

// --- Chat eligibility  !! là ép kiểu boolean ---
const canChat = computed(() => !!contactId.value)


// --- Channel name ---
// Tạo ra 1 kênh chat cho mỗi cuộc trò chuyện 
const channelName = computed(() => {
    if (!myId.value || !contactId.value) return null
    const parts = [`SinhVien.${myId.value}`, `GiangVien.${contactId.value}`]
    parts.sort()
    return `chat.${parts.join(".")}`
})

// --- Methods ---
function toggleMenu() {
    showMenu.value = !showMenu.value
}

function toggleChat() {
    showChat.value = !showChat.value
    if (showChat.value) {
        messages.value = []
        loadMessages()
        subscribeToChannel() // truy cập vào chat của mình
    } else {
        unsubscribeFromChannel()
    }
}

async function loadMessages() {
    if (!contactType || !contactId.value) return
    loading.value = true
    try {
        const { data } = await axios.get(`/api/chat/${contactType}/${contactId.value}`)
        messages.value = data
    } catch (error) {
        console.error("Lỗi tải tin nhắn:", error)
    } finally {
        loading.value = false
        await nextTick()
        scrollToBottom()
    }
}

function scrollToBottom() {
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight
    }
}

// ----- WebSocket (with safeguards) -----
let echoChannel = null

function subscribeToChannel() {
    if (!channelName.value) return

    // Leave any previous channel (should already be null, but just in case)
    if (echoChannel) {
        window.Echo.leave(channelName.value)
        echoChannel = null
    }

    // Wait for Echo connection before subscribing
    if (window.Echo.connector.pusher.connection.state !== 'connected') {
        setTimeout(subscribeToChannel, 300)
        return
    }

    echoChannel = window.Echo.private(channelName.value)
        .subscribed(() => {
            console.log('✅ Student subscribed to', channelName.value)
        })
        .listen('.MessageSent', (data) => {
            // Prevent duplicate insertion (if somehow multiple listeners are active)
            if (!messages.value.some(m => m.id === data.id)) {
                messages.value.push(data)
                nextTick(() => scrollToBottom())
            }
        })
        .error((e) => {
            console.error('❌ Student subscription error:', e)
        })
}

function unsubscribeFromChannel() {
    if (echoChannel) {
        window.Echo.leave(channelName.value)
        echoChannel = null
    }
}

async function sendMessage() {
    if (!newMessage.value.trim() || !contactId.value) return
    try {
        const { data } = await axios.post("api/chat/send", {
            receiver_type: contactType,
            receiver_id: contactId.value,
            body: newMessage.value,
        })
        // Show own message immediately (the server uses ->toOthers(), so you won't receive this via Echo)
        messages.value.push(data)
        newMessage.value = ""
        await nextTick()
        scrollToBottom()
    } catch (error) {
        console.error("Gửi tin nhắn thất bại:", error)
    }
}

function Profile() {
    router.visit("/profile")
}

function Logout() {
    router.post("/logout")
}
</script>