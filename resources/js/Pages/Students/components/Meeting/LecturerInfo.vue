<template>
    <div
        v-if="lecturer && lecturer.Ho_va_Ten"
        class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8"
    >
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <!-- Avatar -->
            <div
                class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex items-center justify-center text-4xl font-bold shadow-lg"
            >
                {{ lecturer.Ho_va_Ten.charAt(0).toUpperCase() }}
            </div>

            <!-- Info -->
            <div class="flex-1">
                <p class="text-sm text-slate-500">Giảng viên hướng dẫn</p>
                <h2 class="text-2xl font-bold text-slate-800 mt-1">
                    {{ lecturer.Ho_va_Ten }}
                </h2>
                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
                        📧 {{ lecturer.email || 'Chưa có email' }}
                    </div>
                    <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
                        📞 {{ lecturer.phone || 'Chưa có số điện thoại' }}
                    </div>
                    <div class="px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 text-sm font-semibold">
                        💻 Công nghệ thông tin
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-else class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8 text-center text-gray-500">
        Chưa có thông tin giảng viên hướng dẫn.
    </div>
</template>

<script setup>
defineProps({
    lecturer: {
        type: Object,
        default: () => null
    }
});
</script>