<template>
    <div
        class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
    >
        <!-- Top -->
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4"
            >
                <div>
                    <h2 class="text-lg font-bold text-slate-800">
                        Danh sách lịch phản biện
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Thông tin phòng, thời gian và yêu cầu chuẩn bị cho buổi phản biện
                    </p>
                </div>

                <div
                    class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 text-sm font-semibold"
                >
                    {{ reviewSchedules.length }} lịch
                </div>
            </div>
        </div>

        <!-- Table -->
      <div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-slate-100">
            <tr>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">STT</th>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Phòng</th>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Ngày phản biện</th>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Thời gian</th>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Ghi chú</th>
                <th class="px-6 py-4 text-center text-sm font-bold text-slate-700">Trạng thái</th>
            </tr>
        </thead>

        <tbody>
            <tr
                v-for="(item, index) in reviewSchedules"
                :key="index"
                class="border-t border-slate-100 hover:bg-blue-50/40 transition-all duration-200"
            >
                <!-- STT -->
                <td class="px-6 py-5 text-center">
                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-semibold text-slate-700 mx-auto">
                        {{ index + 1 }}
                    </div>
                </td>

                <!-- Phòng -->
                <td class="px-6 py-5 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-medium">
                        🏫 {{ item.location }}
                    </div>
                </td>

                <!-- Ngày phản biện (date + day) -->
                <td class="px-6 py-5 text-center">
                    <div>
                        <p class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold">
                            📅 {{ item.date }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">{{ item.day }}</p>
                    </div>
                </td>

                <!-- Thời gian -->
                <td class="px-6 py-5 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold">
                        ⏰ {{ item.time }}
                    </div>
                </td>

                <!-- Ghi chú -->
                <td class="px-6 py-5 text-center">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-medium max-w-xs">
                        📝 {{ item.note ? item.note : 'Chưa có ghi chú' }}
                    </div>
                </td>

                <!-- Trạng thái -->
                <td class="px-6 py-5 text-center">
                    <div v-if="item.status === 'Sắp diễn ra'"
                         class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold text-sm">
                        🔵 Sắp diễn ra
                    </div>
                    <div v-else-if="item.status === 'Đã xác nhận'"
                         class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 font-semibold text-sm">
                        ✅ Hoàn thành
                    </div>
                    <div v-else
                         class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-yellow-100 text-yellow-700 font-semibold text-sm">
                        ⏳ Chờ xác nhận
                    </div>
                </td>
            </tr>

            <!-- Empty state -->
            <tr v-if="reviewSchedules.length === 0">
                <td colspan="6" class="py-16 text-center">
                    <div class="flex flex-col items-center justify-center">
                        <div class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-4xl mb-4">
                            📅
                        </div>
                        <h3 class="font-semibold text-slate-700 text-lg">Chưa có lịch phản biện</h3>
                        <p class="text-slate-500 text-sm mt-1">Hiện chưa có lịch phản biện nào được công bố</p>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
    </div>
</template>

<script setup>
defineProps({
    reviewSchedules: {
        type: Array,
        default: () => [],
    },
});

function statusClass(status) {
    if (status === "Đã xác nhận") {
        return "bg-blue-100 text-blue-700";
    }

    if (status === "Sắp diễn ra") {
        return "bg-emerald-100 text-emerald-700";
    }

    return "bg-red-100 text-red-700";
}
</script>
