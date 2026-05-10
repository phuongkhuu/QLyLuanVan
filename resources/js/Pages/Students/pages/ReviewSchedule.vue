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
                Sắp diễn ra
            </div>
        </div>

        <ReviewerInfo :reviewer="reviewer" />

        <ReviewScheduleTable :review-schedules="reviewSchedules" />
    </div>
</template>

<script setup>
import ReviewerInfo from '../components/ReviewSchedule/ReviewerInfo.vue';
import ReviewScheduleTable from '../components/ReviewSchedule/ReviewScheduleTable.vue';

const reviewer = {
    name: "Nguyễn Thị Mai",
    email: "nguyenthimai@university.edu.vn",
    phone: "0987654321",
    department: "Công nghệ thông tin",
};

const reviewSchedules = [
    {
        room: "B2-305",
        date: "25/05/2026",
        day: "Thứ Hai",
        time: "08:00 - 09:00",
        note: "Chuẩn bị slide báo cáo, tài liệu minh chứng và bản in luận văn.",
        status: "Sắp diễn ra",
    },
    {
        room: "B1-201",
        date: "20/05/2026",
        day: "Thứ Tư",
        time: "14:00 - 15:00",
        note: "Lịch cũ đã bị huỷ do thay đổi phòng phản biện.",
        status: "Đã huỷ",
    },
    {
        room: "Google Meet",
        date: "04/05/2026",
        day: "Thứ Hai",
        time: "19:30 - 20:00",
        note: "Kiểm tra kết nối và gửi trước file trình bày cho giảng viên phản biện.",
        status: "Đã diễn ra",
    },
];
</script>
