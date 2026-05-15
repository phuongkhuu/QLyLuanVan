<template>
    <div
        class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8"
    >
        <div class="flex flex-col md:flex-row md:items-center gap-6">
            <div
                class="w-20 h-20 rounded-full bg-gradient-to-r from-emerald-500 to-green-600 text-white flex items-center justify-center text-3xl font-bold shadow-lg"
            >
                {{ reviewer.Ho_va_Ten?.charAt(0)?.toUpperCase() }}
            </div>

            <div class="flex-1">
                <p class="text-sm text-slate-500">Giảng viên phản biện</p>

                <h2 class="text-2xl font-bold text-slate-800 mt-1">
                    {{ reviewer.Ho_va_Ten }}
                </h2>

                <div class="flex flex-wrap items-center gap-3 mt-4">
                    <div
                        class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium"
                    >
                        {{ reviewer.email }}
                    </div>

                    <div
                        class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium"
                    >
                        {{ reviewer.phone ? reviewer.phone : 'Chưa cập nhật số điện thoại' }}
                    </div>

                    <div
                        class="px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-semibold"
                    >
                        {{ reviewer.department ? reviewer.department.TenKhoa : 'Công nghệ thông tin' }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    reviewer: {
        type: Object,
        default: () => ({}),
    },
});
</script>
