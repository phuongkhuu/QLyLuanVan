<template>
    <aside
        :class="collapsed ? 'w-24' : 'w-72'"
        class="min-h-screen bg-gradient-to-b from-slate-900 via-blue-950 to-slate-950 text-white shadow-2xl border-r border-white/10 transition-all duration-300 relative"
    >
        <!-- Toggle -->
        <button
            @click="toggleSidebar"
            class="absolute -right-4 top-6 w-9 h-9 rounded-full bg-blue-600 hover:bg-blue-700 border-4 border-white flex items-center justify-center shadow-lg z-50 transition"
        >
            <span class="text-sm font-bold">
                {{ collapsed ? "➜" : "⬅" }}
            </span>
        </button>

        <!-- Menu -->
        <nav class="p-4 space-y-2">
            <!-- Item -->
            <button
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl bg-white/5 hover:bg-blue-600 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center shrink-0"
                >
                    📋
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Bảng phân công</p>

                        <p class="text-xs text-blue-200">
                            Xem đề tài được phân công
                        </p>
                    </div>
                </transition>
            </button>

            <!-- Meeting -->
            <button
                @click="$emit('changeView', 'meetingSchedule')"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0"
                >
                    📅
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Lịch gặp GVHD</p>

                        <p class="text-xs text-blue-200">
                            Theo dõi lịch hướng dẫn
                        </p>
                    </div>
                </transition>
            </button>

            <!-- Score -->
            <button
                @click="$emit('changeView', 'score50')"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0"
                >
                    📊
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Xem điểm 50%</p>

                        <p class="text-xs text-blue-200">
                            Theo dõi tiến độ luận văn
                        </p>
                    </div>
                </transition>
            </button>

            <!-- Reviewer -->
            <button
                @click="$emit('changeView', 'reviewLecturer')"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0"
                >
                    👨‍🏫
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Giảng viên phản biện</p>

                        <p class="text-xs text-blue-200">Thông tin phản biện</p>
                    </div>
                </transition>
            </button>

            <!-- Schedule -->
            <button
                @click="$emit('changeView', 'reviewSchedule')"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0"
                >
                    🗓️
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Lịch phản biện</p>

                        <p class="text-xs text-blue-200">
                            Theo dõi lịch phản biện
                        </p>
                    </div>
                </transition>
            </button>

            <!-- Council -->
            <button
                @click="$emit('changeView', 'defenseCommittee')"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl hover:bg-white/10 transition-all duration-200"
                :class="collapsed ? 'justify-center' : ''"
            >
                <div
                    class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center shrink-0"
                >
                    🔔
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">Hội đồng bảo vệ</p>

                        <p class="text-xs text-blue-200">
                            Thông báo & lịch bảo vệ
                        </p>
                    </div>
                </transition>
            </button>
        </nav>
    </aside>
</template>

<script setup>
import { ref } from "vue";

const collapsed = ref(false);

function toggleSidebar() {
    collapsed.value = !collapsed.value;
}

defineProps({
    currentView: String,
});

defineEmits(["changeView"]);
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: all 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
    transform: translateX(-10px);
}
</style>
