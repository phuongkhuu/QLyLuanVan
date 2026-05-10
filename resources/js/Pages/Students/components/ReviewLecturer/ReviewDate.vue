<template>
    <div class="rounded-3xl border border-blue-200 bg-blue-50 p-6">
        <div class="flex items-center gap-4">
            <!-- Icon -->
            <div
                class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-3xl"
            >
                📅
            </div>

            <!-- Info -->
            <div>
                <p class="text-sm text-blue-600 font-medium">
                    Ngày dự kiến phản biện
                </p>

                <h2 class="text-2xl font-bold text-slate-800 mt-1">
                    {{ review.reviewDate }}
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    Sinh viên vui lòng chuẩn bị đầy đủ nội dung báo cáo
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
const { review } = defineProps({
    review: Object,
});
</script>
