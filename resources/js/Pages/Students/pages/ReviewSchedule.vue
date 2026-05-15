<template>
    <div
        class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Lịch phản biện
                </h1>

                <p class="text-slate-500 mt-2">
                    Theo dõi thời gian, địa điểm và trạng thái lịch phản biện luận văn
                </p>
            </div>

            <!-- Status -->
            <div
                class="px-5 py-3 rounded-2xl bg-blue-100 text-blue-700 font-semibold shadow-sm"
            >
                {{ reviewSchedules.status === 'Đã xác nhận' ? 'Đã diễn ra' : 'Sắp diễn ra' }}
            </div>
        </div>

        <ReviewerInfo :reviewer="reviewer" />

        <ReviewScheduleTable :review-schedules="reviewSchedules" />
    </div>
</template>

<script setup>
import ReviewerInfo from '../components/ReviewSchedule/ReviewerInfo.vue';
import ReviewScheduleTable from '../components/ReviewSchedule/ReviewScheduleTable.vue';

const props = defineProps({
    reviewer: {
        type: Object,
        required: true
    },
    reviewSchedules: {
        type: Array,
        required: true
    }
});
</script>
