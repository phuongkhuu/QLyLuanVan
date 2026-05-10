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
                    @click="toggleChat"

                    class="
                        relative
                        w-12 h-12
                        rounded-2xl
                        bg-white/20
                        hover:bg-white/30
                        transition-all duration-200
                        flex items-center justify-center
                        backdrop-blur
                        shadow-lg
                    "
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

                        class="
                            absolute
                            top-16
                            right-0
                            w-[380px]
                            h-[560px]
                            bg-white
                            rounded-3xl
                            shadow-2xl
                            overflow-hidden
                            border border-slate-200
                            flex flex-col
                        "
                    >
                        <!-- LIST CHAT -->
                        <template
                            v-if="!selectedChat"
                        >
                            <!-- TOP -->
                            <div
                                class="
                                    px-5 py-4
                                    border-b
                                    bg-white
                                "
                            >
                                <h2
                                    class="
                                        text-xl
                                        font-bold
                                        text-slate-800
                                    "
                                >
                                    Tin nhắn
                                </h2>

                                <p
                                    class="
                                        text-sm
                                        text-slate-500
                                        mt-1
                                    "
                                >
                                    Trò chuyện với sinh viên
                                </p>
                            </div>

                            <!-- SEARCH -->
                            <div
                                class="
                                    p-4
                                    border-b
                                    bg-white
                                "
                            >
                                <input
                                    type="text"
                                    placeholder="Tìm kiếm sinh viên..."

                                    class="
                                        w-full
                                        h-11
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        px-4
                                        outline-none
                                        focus:border-indigo-400
                                        focus:ring-4
                                        focus:ring-indigo-100
                                        text-black
                                    "
                                />
                            </div>

                            <!-- CHAT LIST -->
                            <div
                                class="
                                    flex-1
                                    overflow-y-auto
                                    bg-slate-50
                                "
                            >
                                <div
                                    v-for="(chat, index) in chats"
                                    :key="index"

                                    @click="
                                        selectedChat = chat
                                    "

                                    class="
                                        flex items-center gap-3
                                        p-4
                                        hover:bg-slate-100
                                        cursor-pointer
                                        transition
                                        border-b
                                    "
                                >
                                    <!-- Avatar -->
                                    <div
                                        class="
                                            relative
                                            shrink-0
                                        "
                                    >
                                        <div
                                            class="
                                                w-12 h-12
                                                rounded-full
                                                bg-indigo-500
                                                text-white
                                                font-bold
                                                flex items-center justify-center
                                            "
                                        >
                                            {{
                                                chat.name
                                                    ?.charAt(0)
                                                    ?.toUpperCase()
                                            }}
                                        </div>

                                        <!-- Online -->
                                        <div
                                            class="
                                                absolute
                                                bottom-0
                                                right-0
                                                w-3 h-3
                                                rounded-full
                                                bg-emerald-500
                                                border-2 border-white
                                            "
                                        ></div>
                                    </div>

                                    <!-- Info -->
                                    <div
                                        class="
                                            flex-1
                                            min-w-0
                                        "
                                    >
                                        <div
                                            class="
                                                flex items-center
                                                justify-between
                                            "
                                        >
                                            <h3
                                                class="
                                                    font-semibold
                                                    text-slate-800
                                                    truncate
                                                "
                                            >
                                                {{ chat.name }}
                                            </h3>

                                            <span
                                                class="
                                                    text-xs
                                                    text-slate-400
                                                "
                                            >
                                                2m
                                            </span>
                                        </div>

                                        <p
                                            class="
                                                text-sm
                                                text-slate-500
                                                truncate
                                                mt-1
                                            "
                                        >
                                            {{
                                                chat.messages.at(-1)?.text
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- CONVERSATION -->
                        <template
                            v-else
                        >
                            <!-- TOP -->
                            <div
                                class="
                                    px-4 py-4
                                    border-b
                                    flex items-center gap-3
                                    bg-white
                                "
                            >
                                <!-- BACK -->
                                <button
                                    @click="
                                        selectedChat = null
                                    "

                                    class="
                                        w-10 h-10
                                        rounded-xl
                                        hover:bg-slate-100
                                        flex items-center justify-center
                                        text-xl
                                        transition
                                        text-black
                                    "
                                >
                                    ←
                                </button>

                                <!-- Avatar -->
                                <div
                                    class="
                                        relative
                                    "
                                >
                                    <div
                                        class="
                                            w-11 h-11
                                            rounded-full
                                            bg-indigo-500
                                            text-white
                                            font-bold
                                            flex items-center justify-center
                                        "
                                    >
                                        {{
                                            selectedChat.name
                                                ?.charAt(0)
                                                ?.toUpperCase()
                                        }}
                                    </div>

                                    <!-- Online -->
                                    <div
                                        class="
                                            absolute
                                            bottom-0
                                            right-0
                                            w-3 h-3
                                            rounded-full
                                            bg-emerald-500
                                            border-2 border-white
                                        "
                                    ></div>
                                </div>

                                <!-- Info -->
                                <div>
                                    <h3
                                        class="
                                            font-bold
                                            text-slate-800
                                        "
                                    >
                                        {{ selectedChat.name }}
                                    </h3>

                                    <p
                                        class="
                                            text-xs
                                            text-emerald-500
                                        "
                                    >
                                        Đang hoạt động
                                    </p>
                                </div>
                            </div>

                            <!-- MESSAGES -->
                            <div
                                class="
                                    flex-1
                                    overflow-y-auto
                                    p-4
                                    bg-slate-50
                                    space-y-4
                                "
                            >
                                <div
                                    v-for="
                                        (
                                            msg,
                                            i
                                        ) in selectedChat.messages
                                    "

                                    :key="i"

                                    :class="
                                        msg.sender === 'teacher'
                                            ? 'justify-end'
                                            : 'justify-start'
                                    "

                                    class="flex"
                                >
                                    <div
                                        :class="
                                            msg.sender === 'teacher'

                                                ? 'bg-indigo-600 text-white rounded-br-md'

                                                : 'bg-white text-slate-700 rounded-bl-md'
                                        "

                                        class="
                                            max-w-[75%]
                                            px-4 py-3
                                            rounded-2xl
                                            shadow-sm
                                        "
                                    >
                                        {{ msg.text }}
                                    </div>
                                </div>
                            </div>

                            <!-- INPUT -->
                            <div
                                class="
                                    p-4
                                    border-t
                                    bg-white
                                    flex items-center gap-3
                                "
                            >
                                <input
                                    type="text"
                                    placeholder="Nhập tin nhắn..."

                                    class="
                                        flex-1
                                        h-11
                                        rounded-2xl
                                        border
                                        border-slate-200
                                        px-4
                                        outline-none
                                        focus:border-indigo-400
                                        focus:ring-4
                                        focus:ring-indigo-100
                                        text-black
                                    "
                                />

                                <button
                                    class="
                                        w-11 h-11
                                        rounded-2xl
                                        bg-indigo-600
                                        hover:bg-indigo-700
                                        text-white
                                        flex items-center justify-center
                                        shadow
                                        transition
                                    "
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
import { ref } from "vue"
import { router } from "@inertiajs/vue3"

const { user } = defineProps({
    user: Object,
})

const showMenu = ref(false)
const showChat = ref(false)

function toggleMenu() {
    showMenu.value = !showMenu.value
}

function toggleChat() {
    showChat.value = !showChat.value
}

function goProfile() {
    router.visit("/profile")
}

function logout() {
    router.post("/logout")
}

const chats = ref([
    {
        name: "Nguyễn Văn A",

        messages: [
            {
                sender: "student",
                text: "Thầy ơi em gửi báo cáo rồi ạ",
            },

            {
                sender: "teacher",
                text: "Ừ để thầy xem nhé",
            },
        ],
    },

    {
        name: "Trần Văn B",

        messages: [
            {
                sender: "student",
                text: "Mai em gặp thầy được không ạ?",
            },

            {
                sender: "teacher",
                text: "Được em nhé",
            },
        ],
    },

    {
        name: "Lê Thị C",

        messages: [
            {
                sender: "student",
                text: "Em chưa hiểu phần database",
            },

            {
                sender: "teacher",
                text: "Tối thầy call hướng dẫn",
            },
        ],
    },
])

const selectedChat = ref(null)
</script>