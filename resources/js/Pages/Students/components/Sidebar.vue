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
                {{ collapsed ? ">" : "<" }}
            </span>
        </button>

        <!-- Menu -->
        <nav class="p-4 space-y-2">
            <button
                v-for="item in menuItems"
                :key="item.view"
                @click="$emit('changeView', item.view)"
                class="group w-full flex items-center gap-4 px-4 py-3 rounded-2xl transition-all duration-200"
                :class="menuClass(item.view)"
            >
                <div
                    class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0"
                    :class="iconClass(item.view)"
                >
                    {{ item.icon }}
                </div>

                <transition name="fade">
                    <div v-if="!collapsed" class="text-left">
                        <p class="font-medium">{{ item.title }}</p>

                        <p class="text-xs text-blue-200">
                            {{ item.description }}
                        </p>
                    </div>
                </transition>
            </button>
        </nav>
    </aside>
</template>

<script setup>
import { ref } from "vue";

const props = defineProps({
    currentView: String,
});

defineEmits(["changeView"]);

const collapsed = ref(false);

const menuItems = [
    {
        view: "dashboard",
        icon: "📋",
        title: "Bảng phân công",
        description: "Xem đề tài được phân công",
    },
    {
        view: "meetingSchedule",
        icon: "📅",
        title: "Lịch gặp GVHD",
        description: "Theo dõi lịch hướng dẫn",
    },
    {
        view: "score50",
        icon: "📊",
        title: "Xem điểm 50%",
        description: "Theo dõi tiến độ luận văn",
    },
    {
        view: "reviewLecturer",
        icon: "👨‍🏫",
        title: "Giảng viên phản biện",
        description: "Thông tin phản biện",
    },
    {
        view: "reviewSchedule",
        icon: "🗓️",
        title: "Lịch phản biện",
        description: "Theo dõi lịch phản biện",
    },
    {
        view: "defenseCommittee",
        icon: "🔔",
        title: "Hội đồng bảo vệ",
        description: "Thông báo & lịch bảo vệ",
    },
    {
        view: "test",
        icon: "🧪",
        title: "Test View",
        description: "Dùng để test giao diện",
    }
];

function toggleSidebar() {
    collapsed.value = !collapsed.value;
}

function menuClass(view) {
    return [
        collapsed.value ? "justify-center" : "",
        props.currentView === view
            ? "bg-blue-600 shadow-lg shadow-blue-950/30"
            : "hover:bg-white/10",
    ];
}

function iconClass(view) {
    return props.currentView === view ? "bg-white/20" : "bg-white/10";
}
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
