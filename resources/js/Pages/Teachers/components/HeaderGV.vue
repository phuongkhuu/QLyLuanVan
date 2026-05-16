<template>
    <header
        class="flex justify-between items-center bg-gradient-to-r from-indigo-500 to-indigo-700 text-white px-6 py-4 shadow-lg relative z-50"
    >
        <!-- LEFT -->
        <div>
            <h1
                class="text-xl font-bold tracking-wide"
            >
                HỆ THỐNG QUẢN LÝ LUẬN VĂN TỐT NGHIỆP
            </h1>

            <p
                class="text-sm mt-1 text-indigo-100"
            >
                KHOA CÔNG NGHỆ THÔNG TIN
            </p>
        </div>

        <!-- RIGHT -->
        <div
            class="flex items-center gap-4"
        >
            <!-- CHAT -->
            <div class="relative">
                <!-- Chat Button -->
                <button
                    v-if="studentList.length"
                    @click="toggleChat"
                    class="relative w-12 h-12 rounded-2xl bg-white/20 hover:bg-white/30 transition-all duration-200 flex items-center justify-center backdrop-blur shadow-lg"
                >
                    <!-- Icon -->
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
                            d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4-.8L3 20l1.2-3.2A7.64 7.64 0 013 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                        />
                    </svg>
                    <div
                        class="absolute -top-1 -right-1 w-5 h-5 rounded-full bg-red-500 text-white text-[10px] font-bold flex items-center justify-center border-2 border-indigo-600"
                    >
                        {{ studentList.length }}
                    </div>

                    <!-- Badge -->
                    <div
                        class="
                            absolute
                            -top-1
                            -right-1
                            w-5 h-5
                            rounded-full
                            bg-red-500
                            text-white
                            text-[10px]
                            font-bold
                            flex items-center justify-center
                            border-2 border-indigo-600
                        "
                    >
                        4
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
                        class="absolute top-16 right-0 w-[380px] h-[560px] bg-white rounded-3xl shadow-2xl overflow-hidden border border-slate-200 flex flex-col"
                    >
                        <!-- STUDENT LIST -->
                        <template v-if="!selectedStudent">
                            <div class="px-5 py-4 border-b bg-white">
                                <h2 class="text-xl font-bold text-slate-800">Tin nhắn</h2>
                                <p class="text-sm text-slate-500 mt-1">Trò chuyện với sinh viên</p>
                            </div>
                            <div class="p-4 border-b bg-white">
                                <input
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Tìm kiếm sinh viên..."
                                    class="w-full h-11 rounded-2xl border border-slate-200 px-4 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 text-black"
                                />
                            </div>
                            <div class="flex-1 overflow-y-auto bg-slate-50">
                                <div
                                    v-for="sv in filteredStudents"
                                    :key="studentId(sv)"
                                    @click="selectStudent(sv)"
                                    class="flex items-center gap-3 p-4 hover:bg-slate-100 cursor-pointer transition border-b"
                                >
                                    <div class="relative shrink-0">
                                        <div
                                            class="w-12 h-12 rounded-full bg-indigo-500 text-white font-bold flex items-center justify-center"
                                        >
                                            {{ studentName(sv).charAt(0)?.toUpperCase() }}
                                        </div>
                                        <div
                                            class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"
                                        ></div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-semibold text-slate-800 truncate">{{ studentName(sv) }}</h3>
                                            <!-- last message time placeholder -->
                                        </div>
                                        <p class="text-sm text-slate-500 truncate mt-1">
                                            {{ sv.mssv }}
                                        </p>
                                    </div>
                                </div>
                                <div v-if="!filteredStudents.length" class="p-4 text-center text-slate-400">
                                    Không tìm thấy sinh viên
                                </div>
                            </div>
                        </template>

                        <!-- CONVERSATION -->
                        <template v-else>
                            <div class="px-4 py-4 border-b flex items-center gap-3 bg-white">
                                <button
                                    @click="selectedStudent = null"
                                    class="w-10 h-10 rounded-xl hover:bg-slate-100 flex items-center justify-center text-xl transition text-black"
                                >
                                    ←
                                </button>
                                <div class="relative">
                                    <div
                                        class="w-11 h-11 rounded-full bg-indigo-500 text-white font-bold flex items-center justify-center"
                                    >
                                        {{ studentName(selectedStudent).charAt(0)?.toUpperCase() }}
                                    </div>
                                    <div
                                        class="absolute bottom-0 right-0 w-3 h-3 rounded-full bg-emerald-500 border-2 border-white"
                                    ></div>
                                </div>
                                <div>
                                    <h3 class="font-bold text-slate-800">{{ studentName(selectedStudent) }}</h3>
                                    <p class="text-xs text-emerald-500">Đang hoạt động</p>
                                </div>
                            </div>

                            <!-- MESSAGES -->
                            <div
                                class="flex-1 overflow-y-auto p-4 bg-slate-50 space-y-4"
                                ref="messageContainer"
                            >
                                <div
                                    v-for="msg in messages"
                                    :key="msg.id"
                                    :class="[
                                        'flex',
                                        msg.sender_id === myId && msg.sender_type === myType
                                            ? 'justify-end'
                                            : 'justify-start'
                                    ]"
                                >
                                    <div
                                        :class="[
                                            'max-w-[75%] px-4 py-3 rounded-2xl shadow-sm',
                                            msg.sender_id === myId && msg.sender_type === myType
                                                ? 'bg-indigo-600 text-white rounded-br-md'
                                                : 'bg-white text-slate-700 rounded-bl-md'
                                        ]"
                                    >
                                        {{ msg.body }}
                                    </div>
                                </div>
                                <div v-if="loadingMessages" class="text-center text-slate-400">Đang tải...</div>
                            </div>

                            <!-- INPUT -->
                            <div class="p-4 border-t bg-white flex items-center gap-3">
                                <input
                                    v-model="newMessage"
                                    @keyup.enter="sendMessage"
                                    type="text"
                                    placeholder="Nhập tin nhắn..."
                                    class="flex-1 h-11 rounded-2xl border border-slate-200 px-4 outline-none focus:border-indigo-400 focus:ring-4 focus:ring-indigo-100 text-black"
                                />
                                <button
                                    @click="sendMessage"
                                    class="w-11 h-11 rounded-2xl bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center shadow transition"
                                >
                                    ➤
                                </button>
                            </div>
                        </template>
                    </div>
                </transition>
            </div>

            <!-- USER -->
            <div class="relative">

                <!-- User Button -->
                <div
                    @click="toggleMenu"

                    class="
                        flex items-center space-x-3
                        cursor-pointer
                    "
                >
                    <!-- Avatar -->
                    <div
                        class="
                            w-10 h-10
                            bg-white
                            text-indigo-600
                            font-bold
                            rounded-full
                            flex items-center justify-center
                        "
                    >
                        {{
                            user.name &&
                            user.name.length

                                ? user.name
                                    .charAt(0)
                                    .toUpperCase()

                                : "U"
                        }}
                    </div>

                    <!-- Info -->
                    <div class="text-right">

                        <div
                            class="
                                font-semibold
                                text-sm
                                text-white
                            "
                        >
                            {{ user.name }}
                        </div>

                        <div
                            class="
                                text-xs
                                opacity-80
                            "
                        >
                            Giảng viên
                        </div>
                    </div>
                </div>

                <!-- DROPDOWN -->
                <div
                    v-if="showMenu"

                    class="
                        absolute
                        right-0
                        top-full
                        mt-2
                        w-44
                        bg-white
                        text-black
                        rounded-2xl
                        shadow-xl
                        overflow-hidden
                        z-50
                    "
                >
                    <button
                        @click="goProfile"

                        class="
                            w-full
                            text-left
                            px-4 py-3
                            hover:bg-gray-100
                            transition
                        "
                    >
                        👤 Trang cá nhân
                    </button>

                    <button
                        @click="logout"

                        class="
                            w-full
                            text-left
                            px-4 py-3
                            hover:bg-red-50
                            text-red-600
                            transition
                        "
                    >
                        🚪 Đăng xuất
                    </button>
                </div>
            </div>
        </div>
    </header>
</template>

<script setup>
import { ref, computed, nextTick } from "vue"
import { router } from "@inertiajs/vue3"
import axios from "axios"
import { watch, onBeforeUnmount } from "vue"

const props = defineProps({
    user: Object,
})

// ----- UI state -----
const showMenu = ref(false)
const showChat = ref(false)

// ----- Chat state -----
const searchQuery = ref("")
const selectedStudent = ref(null)      // SinhVien object
const messages = ref([])
const newMessage = ref("")
const loadingMessages = ref(false)
const messageContainer = ref(null)

// ----- Teacher identity -----
const myType = "GiangVien"
const myId = computed(() => props.user?.giang_vien?.MaGV)

// ----- Helpers -----
function studentName(sv) {
    return sv.name || 'Sinh viên'
}
function studentId(sv) {
    return sv.MSSV || sv.mssv || sv.id || ''
}

// ----- Student list -----
const studentList = computed(() => props.user?.giang_vien?.sinh_viens ?? [])
const filteredStudents = computed(() => {
    if (!searchQuery.value) return studentList.value
    const q = searchQuery.value.toLowerCase()
    return studentList.value.filter(sv => {
        const name = studentName(sv).toLowerCase()
        const id = studentId(sv).toLowerCase()
        return name.includes(q) || id.includes(q)
    })
})

// ----- Real‑time channel -----
const channelName = computed(() => {
    if (!myId.value || !selectedStudent.value) return null
    const parts = [`GiangVien.${myId.value}`, `SinhVien.${studentId(selectedStudent.value)}`]
    parts.sort()
    return `chat.${parts.join(".")}`
})

// ----- Methods -----
function toggleMenu() {
    showMenu.value = !showMenu.value
}

function toggleChat() {
    showChat.value = !showChat.value
    if (showChat.value) {
        // Reset view – no manual unsubs needed; watch handles it
        selectedStudent.value = null
        messages.value = []
    }
    // When closing, nothing extra needed
}

function selectStudent(sv) {
    selectedStudent.value = sv
    // The watch will load messages & subscribe automatically
}

async function loadMessages() {
    if (!selectedStudent.value) return
    loadingMessages.value = true
    try {
        const { data } = await axios.get(`api/chat/SinhVien/${studentId(selectedStudent.value)}`)
        messages.value = data
    } catch (e) {
        console.error("Lỗi tải tin nhắn:", e)
    } finally {
        loadingMessages.value = false
        await nextTick()
        scrollToBottom()
    }
}

function scrollToBottom() {
    if (messageContainer.value) {
        messageContainer.value.scrollTop = messageContainer.value.scrollHeight
    }
}

// ----- WebSocket subscription (managed by watch, called only once per student) -----
let echoChannel = null

function subscribeToChannel() {
    if (!channelName.value) return

    // Always leave any previous channel first
    if (echoChannel) {
        window.Echo.leave(channelName.value)
        echoChannel = null
    }

    // Wait for Echo to be connected
    if (window.Echo.connector.pusher.connection.state !== 'connected') {
        setTimeout(subscribeToChannel, 300)
        return
    }

    echoChannel = window.Echo.private(channelName.value)
        .subscribed(() => {
            console.log('✅ Subscribed to', channelName.value)
        })
        .listen('.MessageSent', (data) => {
            // Prevent duplicates by checking if the message already exists
            if (!messages.value.some(m => m.id === data.id)) {
                messages.value.push(data)
                nextTick(() => scrollToBottom())
            }
        })
        .error((e) => {
            console.error('❌ Subscription error:', e)
        })
}

function unsubscribeFromChannel() {
    if (echoChannel) {
        window.Echo.leave(channelName.value)
        echoChannel = null
    }
}

// ----- Watch student selection to handle (un)subscription and message loading -----
watch(selectedStudent, (newVal, oldVal) => {
    // Clean up old channel if student changed
    if (oldVal) {
        unsubscribeFromChannel()
    }
    // For new student: clear messages, load history, then subscribe
    if (newVal) {
        messages.value = []
        loadMessages()
        subscribeToChannel()
    }
})

// Cleanup on component unmount
onBeforeUnmount(() => {
    unsubscribeFromChannel()
})

// ----- Send message -----
async function sendMessage() {
    if (!newMessage.value.trim() || !selectedStudent.value) return
    try {
        const { data } = await axios.post("api/chat/send", {
            receiver_type: "SinhVien",
            receiver_id: studentId(selectedStudent.value),
            body: newMessage.value,
        })
        // Add own message to UI (it won't come back via Echo because of ->toOthers())
        messages.value.push(data)
        newMessage.value = ""
        await nextTick()
        scrollToBottom()
    } catch (e) {
        console.error("Gửi tin nhắn thất bại:", e)
    }
}

// ----- Profile / Logout -----
function goProfile() {
    router.visit("/profile")
}

function logout() {
    router.post("/logout")
}
</script>