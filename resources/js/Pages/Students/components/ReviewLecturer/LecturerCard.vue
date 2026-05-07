<template>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Supervisor -->
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl font-bold"
                >
                    {{ review.supervisor?.charAt(0)?.toUpperCase() }}
                </div>

                <!-- Info -->
                <div>
                    <p class="text-sm text-slate-500">Giảng viên hướng dẫn</p>

                    <h3 class="text-xl font-bold text-slate-800 mt-1">
                        {{ review.supervisor }}
                    </h3>
                </div>
            </div>
        </div>

        <!-- Reviewer -->
        <div class="rounded-3xl border border-slate-200 bg-slate-50 p-6">
            <div class="flex items-center gap-4">
                <!-- Avatar -->
                <div
                    class="w-16 h-16 rounded-full bg-gradient-to-r from-emerald-500 to-green-600 text-white flex items-center justify-center text-2xl font-bold"
                >
                    {{ review.reviewer?.charAt(0)?.toUpperCase() }}
                </div>

                <!-- Info -->
                <div>
                    <p class="text-sm text-slate-500">Giảng viên phản biện</p>

                    <h3 class="text-xl font-bold text-slate-800 mt-1">
                        {{ review.reviewer }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
const { review } = defineProps({
    review: Object,
});
</script>
