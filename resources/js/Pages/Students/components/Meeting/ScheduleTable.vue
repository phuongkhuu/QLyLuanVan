<template>
    <div
        class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden"
    >
        <!-- Top -->
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
            <h2 class="text-lg font-bold text-slate-800">Danh sách lịch gặp</h2>

            <p class="text-sm text-slate-500 mt-1">
                Thông tin lịch hướng dẫn với giảng viên
            </p>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-100">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            STT
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Giảng viên
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Phòng gặp
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Ngày gặp
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Thời gian
                        </th>

                        <th
                            class="px-6 py-4 text-center text-sm font-bold text-slate-700"
                        >
                            Trạng thái
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="(item, index) in schedules"
                        :key="index"
                        class="border-t border-slate-100 hover:bg-blue-50/40 transition-all duration-200"
                    >
                        <!-- STT -->
                        <td class="px-6 py-5">
                            <div
                                class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-semibold text-slate-700"
                            >
                                {{ index + 1 }}
                            </div>
                        </td>

                        <!-- Lecturer -->
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-bold flex items-center justify-center"
                                >
                                    {{
                                        item.lecturer?.charAt(0)?.toUpperCase()
                                    }}
                                </div>

                                <div>
                                    <h3 class="font-semibold text-slate-800">
                                        {{ item.lecturer }}
                                    </h3>

                                    <p class="text-xs text-slate-500">
                                        Giảng viên hướng dẫn
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Room -->
                        <td class="px-6 py-5">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-medium"
                            >
                                🏫 {{ item.room }}
                            </div>
                        </td>

                        <!-- Date -->
                        <td class="px-6 py-5">
                            <div>
                                <p class="font-semibold text-slate-800">
                                    {{ item.date }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    {{ item.day }}
                                </p>
                            </div>
                        </td>

                        <!-- Time -->
                        <td class="px-6 py-5">
                            <div
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold"
                            >
                                ⏰ {{ item.time }}
                            </div>
                        </td>

                        <!-- Status -->
                        <td class="px-6 py-5 text-center">
                            <!-- Upcoming -->
                            <div
                                v-if="item.status === 'Sắp diễn ra'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold text-sm"
                            >
                                🔵 Sắp diễn ra
                            </div>

                            <!-- Done -->
                            <div
                                v-else-if="item.status === 'Hoàn thành'"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 font-semibold text-sm"
                            >
                                ✅ Hoàn thành
                            </div>

                            <!-- Pending -->
                            <div
                                v-else
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-yellow-100 text-yellow-700 font-semibold text-sm"
                            >
                                ⏳ Chờ xác nhận
                            </div>
                        </td>
                    </tr>

                    <!-- Empty -->
                    <tr v-if="schedules.length === 0">
                        <td colspan="6" class="py-16 text-center">
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <div
                                    class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-4xl mb-4"
                                >
                                    📅
                                </div>

                                <h3
                                    class="font-semibold text-slate-700 text-lg"
                                >
                                    Chưa có lịch gặp
                                </h3>

                                <p class="text-slate-500 text-sm mt-1">
                                    Hiện chưa có lịch hướng dẫn nào
                                </p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
const schedules = [
    {
        lecturer: "Nguyễn Văn A",
        room: "B2-203",
        date: "12/05/2026",
        day: "Thứ Hai",
        time: "14:00 - 15:30",
        status: "Sắp diễn ra",
    },

    {
        lecturer: "Nguyễn Văn A",
        room: "A1-104",
        date: "18/05/2026",
        day: "Thứ Tư",
        time: "08:00 - 09:30",
        status: "Hoàn thành",
    },

    {
        lecturer: "Nguyễn Văn A",
        room: "Google Meet",
        date: "22/05/2026",
        day: "Thứ Sáu",
        time: "19:00 - 20:00",
        status: "Chờ xác nhận",
    },
];
</script>
