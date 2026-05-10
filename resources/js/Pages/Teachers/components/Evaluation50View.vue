<template>
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                ĐÁNH GIÁ 50%
            </h2>

            <div class="flex items-center gap-4">
                <input
                    :value="evaluationSearch"
                    @input="$emit('updateSearch', $event.target.value)"
                    type="text"
                    placeholder="Tìm MSSV / tên..."
                    class="w-80 px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                />
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-indigo-100 text-indigo-700">
                    <tr>
                        <th class="p-3 text-center">STT</th>
                        <th class="p-3 text-center">MSSV</th>
                        <th class="p-3 text-center">Họ và tên</th>
                        <th class="p-3 text-center">Lớp</th>
                        <th class="p-3 text-center">Nhóm</th>
                        <th class="p-3 text-center">Tên đề tài</th>
                        <th class="p-3 text-center">Điểm</th>
                        <th class="p-3 text-center">Ghi chú</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Không có dữ liệu -->
                    <tr v-if="students.length === 0">
                        <td
                            colspan="8"
                            class="p-4 text-center text-gray-500"
                        >
                            Không có dữ liệu sinh viên
                        </td>
                    </tr>

                    <!-- Có dữ liệu -->
                    <tr
                        v-for="(s, idx) in students"
                        :key="s.mssv || s.id || idx"
                        class="hover:bg-indigo-50"
                    >
                        <td class="p-3 text-center">
                            {{ idx + 1 }}
                        </td>

                        <td class="p-3 text-center">
                            {{ s.mssv }}
                        </td>

                        <td class="p-3 text-center">
                            {{ s.name }}
                        </td>

                        <td class="p-3 text-center">
                            {{ s.Lop || "-" }}
                        </td>

                        <td class="p-3 text-center">
                            {{ s.group || "-" }}
                        </td>

                        <td class="p-3 text-center">
                            {{ s.topic || s.title || "-" }}
                        </td>

                        <!-- Điểm -->
                        <td class="p-3 text-center">
                            <div
                                class="flex items-center justify-center gap-2"
                            >
                                <input
                                    v-model="s.score"
                                    @keyup.enter="
                                        $emit('updateScore', s)
                                    "
                                    type="text"
                                    class="w-20 px-2 py-1 border rounded text-sm"
                                    placeholder="0 - 100"
                                    :disabled="!canGrade50"
                                />

                                <span class="text-sm text-gray-600">
                                    %
                                </span>
                            </div>
                        </td>

                        <!-- Ghi chú -->
                        <td class="p-3 text-center">
                            <input
                                v-model="s.note"
                                @keyup.enter="
                                    $emit('updateNote', s)
                                "
                                type="text"
                                class="w-full px-2 py-1 border rounded text-sm mx-auto block"
                                placeholder="Ghi chú..."
                                :disabled="!canGrade50"
                            />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    students: {
        type: Array,
        default: () => [],
    },

    evaluationSearch: {
        type: String,
        default: "",
    },

    canGrade50: {
        type: Boolean,
        default: false,
    },
})

defineEmits([
    "updateSearch",
    "updateScore",
    "updateNote",
])
</script>