<template>
    <div
        class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8"
    >
        <!-- Header -->
        <div
            class="flex items-center justify-between mb-8"
        >
            <div>
                <h1
                    class="text-3xl font-bold text-slate-800"
                >
                    Giảng viên phản biện
                </h1>

                <p
                    class="text-slate-500 mt-2"
                >
                    Thông tin giảng viên phản biện luận văn tốt nghiệp
                </p>
            </div>

            <!-- Status -->
            <div
                class="px-5 py-3 rounded-2xl bg-blue-100 text-blue-700 font-semibold shadow-sm"
            >
                📢 Đã phân công phản biện
            </div>
        </div>

        <!-- Main Card -->
        <div
            class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
        >
            <!-- Top -->
            <div
                class="px-6 py-5 border-b border-slate-200 bg-slate-50"
            >
                <h2
                    class="text-lg font-bold text-slate-800"
                >
                    Thông tin phản biện luận văn
                </h2>

                <p
                    class="text-sm text-slate-500 mt-1"
                >
                    Sinh viên xem thông tin giảng viên phản biện và lịch phản biện dự kiến
                </p>
            </div>

            <!-- Content -->
            <div class="p-8">

                <!-- Topic -->
               <Topic :review = "review"/>

                <!-- Lecturer Cards -->
                <LecturerCard :review = "review"/>

                <!-- Review Date -->
                <ReviewDate :review = "review"/>

            </div>
        </div>
    </div>
</template>

<script setup>
import LecturerCard from '../components/ReviewLecturer/LecturerCard.vue';
import Topic from '../components/ReviewLecturer/Topic.vue';
import ReviewDate from '../components/ReviewLecturer/ReviewDate.vue';

const review = {
    topic:
        "Xây dựng hệ thống quản lý luận văn tốt nghiệp",

    supervisor:
        "Trần Văn Hùng",

    reviewer:
        "Nguyễn Thị Mai",

    reviewDate:
        "25/05/2026",
}
</script>