<template>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Topic -->
        <div
            class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6"
        >
            <p class="text-sm text-slate-500">Tên đề tài</p>

            <h2 class="text-2xl font-bold text-slate-800 mt-2 leading-relaxed">
                {{ evaluation.topic }}
            </h2>

            <div class="mt-5 flex items-center gap-3">
                <div
                    class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium"
                >
                    👨‍🏫 GVHD:
                    {{ evaluation.lecturer }}
                </div>

                <div
                    class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium"
                >
                    📅 Đánh giá:
                    {{ evaluation.date }}
                </div>
            </div>
        </div>

        <!-- Score -->
        <div
            class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center"
        >
            <p class="text-sm text-slate-500">Điểm đánh giá</p>

            <div
                :class="
                    evaluation.score >= 50 ? 'text-emerald-600' : 'text-red-500'
                "
                class="text-7xl font-bold mt-4"
            >
                {{ evaluation.score }}
            </div>

            <p class="text-slate-500 mt-2">/ 100 điểm</p>
        </div>
    </div>
</template>

<script setup>
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
