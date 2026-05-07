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
                    Kết quả đánh giá 50%
                </h1>

                <p
                    class="text-slate-500 mt-2"
                >
                    Theo dõi kết quả đánh giá tiến độ luận văn
                </p>
            </div>

            <!-- Status -->
            <div
                :class="
                    evaluation.result === 'Không đình chỉ'
                        ? 'bg-emerald-100 text-emerald-700'
                        : 'bg-red-100 text-red-700'
                "

                class="px-5 py-3 rounded-2xl font-semibold shadow-sm"
            >
                {{
                    evaluation.result ===
                    'Không đình chỉ'

                        ? '✅ Không đình chỉ'

                        : '❌ Đình chỉ'
                }}
            </div>
        </div>

        <!-- Student Info -->
        <StudentInfo  />

        <!-- Topic & Score -->
        <TopicScore />

        <!-- Lecturer Comment -->
        <LecturerComment />
    </div>
</template>

<script setup>
import LecturerComment from '../components/Score50/LecturerComment.vue';
import StudentInfo from '../components/Score50/StudentInfo.vue';
import TopicScore from '../components/Score50/TopicScore.vue';
const evaluation = {
    topic: "Xây dựng hệ thống quản lý luận văn tốt nghiệp",

    lecturer: "Trần Văn Hùng",

    date: "15/05/2026",

    score: 78,

    result: "Không đình chỉ",

    comment:
        "Sinh viên có tiến độ thực hiện khá tốt, hoàn thành đầy đủ các yêu cầu được giao. Nội dung báo cáo rõ ràng, có nghiên cứu tài liệu và triển khai chức năng đúng hướng. Tuy nhiên cần cải thiện thêm phần giao diện và tối ưu hệ thống trước khi bảo vệ.",
};
</script>