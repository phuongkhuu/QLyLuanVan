<template>
    <aside
        :class="[
            collapsed ? 'w-24' : 'w-72',
            'bg-slate-50 border-r border-slate-200 h-screen sticky top-0 transition-all duration-300 flex flex-col shadow-sm'
        ]"
    >
        <!-- TOP -->
        <div
            class="h-20 border-b border-slate-200 flex items-center justify-between px-5"
        >
            <!-- Logo -->
            <transition name="fade">
                <div
                    v-if="!collapsed"
                    class="flex flex-col"
                >
                    <h2
                        class="text-xs font-bold text-indigo-600 tracking-wide"
                    >
                        {{user.name}}
                    </h2>

                    <p
                        class="text-xs text-slate-400 mt-1"
                    >
                        {{user.role}}
                    </p>
                </div>
            </transition>

            <!-- Toggle -->
            <button
                @click="collapsed = !collapsed"
                class="w-11 h-11 rounded-2xl bg-white border border-slate-200 hover:bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm transition-all"
            >
                ☰
            </button>
        </div>

        <!-- MENU -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
            <button
                v-for="item in menus"
                :key="item.view"
                @click="setCurrentView(item.view)"
                :class="[
                    'w-full flex items-center rounded-2xl transition-all duration-200 group',

                    collapsed
                        ? 'justify-center px-0 py-4'
                        : 'gap-4 px-4 py-4',

                    currentView === item.view
                        ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg'
                        : 'text-slate-600 hover:bg-white hover:shadow-sm'
                ]"
            >
                <!-- Icon -->
                <div
                    :class="[
                        'w-11 h-11 rounded-xl flex items-center justify-center text-lg transition-all',

                        currentView === item.view
                            ? 'bg-white/20 text-white'
                            : 'bg-white border border-slate-200 text-indigo-600 group-hover:bg-indigo-50'
                    ]"
                >
                    {{ item.icon }}
                </div>

                <!-- Label -->
                <transition name="fade">
                    <div
                        v-if="!collapsed"
                        class="flex flex-col items-start"
                    >
                        <span
                            class="font-semibold text-sm"
                        >
                            {{ item.label }}
                        </span>

                        <span
                            class="text-xs opacity-70"
                            v-if="currentView === item.view"
                        >
                            Đang hoạt động
                        </span>
                    </div>
                </transition>
            </button>
        </nav>

    </aside>
</template>

<script setup>
import { ref } from "vue";

defineProps({
    currentView: String,
    user: Object,
});

const emit = defineEmits([
    "changeView",
]);

const collapsed = ref(false);

/*
|--------------------------------------------------------------------------
| Menus
|--------------------------------------------------------------------------
*/
const menus = [
    {
        label: "Trang chủ",
        view: "dashboard",
        icon: "🏠",
    },

    {
        label: "Danh sách sinh viên",
        view: "students",
        icon: "👨‍🎓",
    },

    {
        label: "Phân công đề tài",
        view: "assignTopic",
        icon: "📚",
    },

    {
        label: "Đánh giá 50%",
        view: "evaluation50",
        icon: "📝",
    },

    {
        label: "Điểm phản biện",
        view: "reviewScore",
        icon: "🎯",
    },

    {
        label: "Điểm hướng dẫn",
        view: "guideScore",
        icon: "📖",
    },

    {
        label: "Lịch gặp sinh viên",
        view: "AppointmentView",
        icon: "📅",
    },
];

/*
|--------------------------------------------------------------------------
| Change View
|--------------------------------------------------------------------------
*/
function setCurrentView(view) {
    emit("changeView", view);
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>