<template>
    <header
        class="bg-gradient-to-r from-slate-900 via-blue-900 to-indigo-900 text-white shadow-lg border-b border-white/10"
    >
        <div
            class="flex items-center justify-between px-8 py-4"
        >
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

                    <h1
                        class="text-2xl font-bold tracking-wide"
                    >
                        HỆ THỐNG QUẢN LÝ LUẬN VĂN
                    </h1>

                    <div
                        class="flex items-center gap-2 mt-1"
                    >
                        <div
                            class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"
                        ></div>

                        <p
                            class="text-sm text-blue-100 tracking-wide"
                        >
                            KHOA CÔNG NGHỆ THÔNG TIN
                        </p>
                    </div>

                </div>
            </div>

            <!-- RIGHT -->
            <div
                class="flex items-center gap-4"
            >
                <!-- CHAT -->
                <button
                    @click="toggleChat"

                    class="
                        relative
                        w-12 h-12
                        rounded-2xl
                        bg-white/10
                        hover:bg-white/20
                        border border-white/10
                        backdrop-blur
                        flex items-center justify-center
                        transition-all duration-200
                        shadow
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
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-4 4v-4z"
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
                            border border-slate-900
                        "
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

                        class="
                            absolute
                            top-20
                            right-28
                            w-[360px]
                            h-[500px]
                            bg-white
                            rounded-3xl
                            shadow-2xl
                            overflow-hidden
                            z-50
                            border border-slate-200
                            flex flex-col
                        "
                    >
                        <!-- TOP -->
                        <div
                            class="
                                px-5 py-4
                                bg-gradient-to-r
                                from-blue-600
                                to-indigo-600
                                text-white
                                flex items-center justify-between
                            "
                        >
                            <div
                                class="flex items-center gap-3"
                            >
                                <div
                                    class="
                                        w-11 h-11
                                        rounded-full
                                        bg-white
                                        text-blue-700
                                        font-bold
                                        flex items-center justify-center
                                    "
                                >
                                    G
                                </div>

                                <div>
                                    <h3
                                        class="font-bold"
                                    >
                                        GVHD Nguyễn Văn A
                                    </h3>

                                    <p
                                        class="text-xs text-blue-100"
                                    >
                                        Đang hoạt động
                                    </p>
                                </div>
                            </div>

                            <!-- Close -->
                            <button
                                @click="toggleChat"

                                class="
                                    w-8 h-8
                                    rounded-xl
                                    hover:bg-white/10
                                    flex items-center justify-center
                                "
                            >
                                ✕
                            </button>
                        </div>

                        <!-- MESSAGES -->
                        <div
                            class="
                                flex-1
                                overflow-y-auto
                                p-5
                                space-y-4
                                bg-slate-50
                            "
                        >
                            <!-- Teacher -->
                            <div
                                class="flex"
                            >
                                <div
                                    class="
                                        max-w-[75%]
                                        bg-white
                                        px-4 py-3
                                        rounded-2xl
                                        shadow-sm
                                        text-slate-700
                                    "
                                >
                                    Em hoàn thành chương 2 chưa?
                                </div>
                            </div>

                            <!-- Student -->
                            <div
                                class="flex justify-end"
                            >
                                <div
                                    class="
                                        max-w-[75%]
                                        bg-blue-600
                                        text-white
                                        px-4 py-3
                                        rounded-2xl
                                        shadow-sm
                                    "
                                >
                                    Dạ em gần xong rồi ạ.
                                </div>
                            </div>

                            <!-- Teacher -->
                            <div
                                class="flex"
                            >
                                <div
                                    class="
                                        max-w-[75%]
                                        bg-white
                                        px-4 py-3
                                        rounded-2xl
                                        shadow-sm
                                        text-slate-700
                                    "
                                >
                                    Tối gửi thầy xem nhé.
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
                                    h-12
                                    rounded-2xl
                                    border
                                    border-slate-200
                                    px-4
                                    outline-none
                                    focus:border-blue-400
                                    focus:ring-4
                                    focus:ring-blue-100
                                "
                            />

                            <button
                                class="
                                    w-12 h-12
                                    rounded-2xl
                                    bg-blue-600
                                    hover:bg-blue-700
                                    text-white
                                    flex items-center justify-center
                                    shadow
                                    transition
                                "
                            >
                                ➤
                            </button>
                        </div>
                    </div>
                </transition>

                <!-- USER -->
                <div class="relative">

                    <!-- User Button -->
                    <div
                        @click="toggleMenu"

                        class="
                            flex items-center gap-3
                            px-3 py-2
                            rounded-2xl
                            hover:bg-white/10
                            transition
                            cursor-pointer
                            border border-white/10
                        "
                    >
                        <!-- Avatar -->
                        <div
                            class="
                                w-11 h-11
                                rounded-full
                                bg-white
                                text-blue-700
                                font-bold text-lg
                                flex items-center justify-center
                                shadow-md
                            "
                        >
                            {{
                                user?.name
                                    ?.charAt(0)
                                    ?.toUpperCase() || "A"
                            }}
                        </div>

                        <!-- Info -->
                        <div class="text-right">

                            <p
                                class="font-semibold text-sm"
                            >
                                {{ user?.name || "Admin" }}
                            </p>

                            <p
                                class="text-xs text-blue-100"
                            >
                                Sinh viên
                            </p>

                        </div>

                        <!-- Arrow -->
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

                    <!-- DROPDOWN -->
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

                            class="
                                absolute
                                right-0
                                mt-3
                                w-52
                                bg-white
                                rounded-2xl
                                shadow-2xl
                                overflow-hidden
                                border border-gray-100
                                z-50
                            "
                        >
                            <!-- Profile -->
                            <button
                                @click="Profile"

                                class="
                                    w-full
                                    flex items-center gap-3
                                    px-5 py-3
                                    text-sm
                                    text-gray-700
                                    hover:bg-gray-50
                                    transition
                                "
                            >
                                👤 Trang cá nhân
                            </button>

                            <!-- Divider -->
                            <div class="border-t"></div>

                            <!-- Logout -->
                            <button
                                @click="Logout"

                                class="
                                    w-full
                                    flex items-center gap-3
                                    px-5 py-3
                                    text-sm
                                    text-red-600
                                    hover:bg-red-50
                                    transition
                                "
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

function Profile() {
    router.visit("/profile")
}

function Logout() {
    router.post("/logout")
}
</script>