<template>
    <div
        class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    Hội đồng bảo vệ
                </h1>

                <p class="text-slate-500 mt-2">
                    Theo dõi thông tin hội đồng, lịch bảo vệ và thành viên tham gia chấm luận văn
                </p>
            </div>

            <!-- Status -->
            <div
                :class="statusClass(defense.status)"
                class="px-5 py-3 rounded-2xl font-semibold shadow-sm"
            >
                {{ defense.status }}
            </div>
        </div>

        <DefenseOverview :defense="defense" />

        <CommitteeMembers :members="members" />

        <DefenseRequirements :requirements="requirements" />
    </div>
</template>

<script setup>
import CommitteeMembers from '../components/DefenseCommittee/CommitteeMembers.vue';
import DefenseOverview from '../components/DefenseCommittee/DefenseOverview.vue';
import DefenseRequirements from '../components/DefenseCommittee/DefenseRequirements.vue';

const defense = {
    topic: "Xây dựng hệ thống quản lý luận văn tốt nghiệp",
    committeeCode: "HD-01",
    room: "B2-305",
    date: "30/05/2026",
    day: "Thứ Bảy",
    time: "08:00 - 09:30",
    status: "Sắp diễn ra",
};

const members = [
    {
        position: "Chủ tịch hội đồng",
        name: "Nguyễn Văn An",
        email: "nguyenvanan@university.edu.vn",
        department: "Công nghệ thông tin",
        avatarClass: "bg-gradient-to-r from-blue-500 to-indigo-600",
    },
    {
        position: "Thư ký",
        name: "Trần Thị Bình",
        email: "tranthibinh@university.edu.vn",
        department: "Công nghệ thông tin",
        avatarClass: "bg-gradient-to-r from-emerald-500 to-green-600",
    },
    {
        position: "Ủy viên",
        name: "Lê Minh Quân",
        email: "leminhquan@university.edu.vn",
        department: "Công nghệ thông tin",
        avatarClass: "bg-gradient-to-r from-violet-500 to-purple-600",
    },
];

const requirements = [
    "Có mặt trước giờ bảo vệ ít nhất 15 phút.",
    "Chuẩn bị slide trình bày, báo cáo luận văn và tài liệu minh chứng.",
    "Kiểm tra lại thiết bị trình chiếu hoặc kết nối trực tuyến trước buổi bảo vệ.",
];

function statusClass(status) {
    if (status === "Sắp diễn ra") {
        return "bg-blue-100 text-blue-700";
    }

    if (status === "Đã diễn ra") {
        return "bg-emerald-100 text-emerald-700";
    }

    return "bg-red-100 text-red-700";
}
</script>
