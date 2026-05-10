<template>
    <div
        class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8"
    >
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-bold text-slate-800">
                Thông tin buổi bảo vệ
            </h2>

            <p class="text-sm text-slate-500 mt-1">
                Sinh viên kiểm tra kỹ thời gian, phòng bảo vệ và chuẩn bị tài liệu đúng yêu cầu
            </p>
        </div>

        <div class="p-8">
            <div class="mb-8">
                <p class="text-sm text-slate-500">Tên đề tài</p>

                <h2 class="text-3xl font-bold text-slate-800 mt-2 leading-relaxed">
                    {{ defense.topic }}
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5">
                <div
                    class="rounded-3xl border border-slate-200 bg-slate-50 p-5"
                >
                    <p class="text-sm text-slate-500">Mã hội đồng</p>

                    <h3 class="text-xl font-bold text-slate-800 mt-2">
                        {{ defense.committeeCode }}
                    </h3>
                </div>

                <div
                    class="rounded-3xl border border-slate-200 bg-slate-50 p-5"
                >
                    <p class="text-sm text-slate-500">Phòng bảo vệ</p>

                    <h3 class="text-xl font-bold text-slate-800 mt-2">
                        {{ defense.room }}
                    </h3>
                </div>

                <div
                    class="rounded-3xl border border-blue-200 bg-blue-50 p-5"
                >
                    <p class="text-sm text-blue-600 font-medium">
                        Ngày bảo vệ
                    </p>

                    <h3 class="text-xl font-bold text-slate-800 mt-2">
                        {{ defense.date }}
                    </h3>

                    <p class="text-xs text-slate-500 mt-1">
                        {{ defense.day }}
                    </p>
                </div>

                <div
                    class="rounded-3xl border border-blue-200 bg-blue-50 p-5"
                >
                    <p class="text-sm text-blue-600 font-medium">
                        Thời gian
                    </p>

                    <h3 class="text-xl font-bold text-slate-800 mt-2">
                        {{ defense.time }}
                    </h3>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    defense: {
        type: Object,
        default: () => ({}),
    },
});
</script>
