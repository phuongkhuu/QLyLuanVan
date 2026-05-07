<template>
    <div>
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                ĐIỂM PHẢN BIỆN
            </h2>

            <div>
                <input
                    :value="reviewScoreSearch"
                    @input="
                        $emit('updateSearch', $event.target.value)
                    "
                    type="text"
                    placeholder="Tìm mã hoặc tên đề tài..."
                    class="border rounded px-3 py-2 text-sm w-64"
                />
            </div>
        </div>

        <!-- Table -->
        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">

                <thead class="bg-indigo-100 text-indigo-700">
                    <tr>
                        <th class="p-3 text-center">STT</th>
                        <th class="p-3 text-center">Mã đề tài</th>
                        <th class="p-3 text-center">Tên đề tài</th>
                        <th class="p-3 text-center">MSSV</th>
                        <th class="p-3 text-center">
                            Họ và tên sinh viên
                        </th>
                        <th class="p-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody>

                    <!-- Empty -->
                    <tr v-if="reviewScoreRows.length === 0">
                        <td
                            colspan="6"
                            class="p-4 text-center text-gray-500"
                        >
                            Không có đề tài
                        </td>
                    </tr>

                    <!-- Data -->
                    <tr
                        v-for="(row, idx) in reviewScoreRows"
                        :key="
                            (row.topic?.MaDT ||
                                row.topic?.id ||
                                't') +
                            '-' +
                            idx
                        "

                        :class="
                            isTopicHovered(row.topic)
                                ? 'bg-indigo-50'
                                : ''
                        "

                        @mouseenter="
                            $emit('hoverTopic', row.topic)
                        "

                        @mouseleave="
                            $emit('leaveTopic')
                        "
                    >
                        <!-- STT -->
                        <td
                            v-if="row.isFirst"
                            class="p-3 text-center"
                            :rowspan="row.rowSpan"
                        >
                            {{ row.stt }}
                        </td>

                        <!-- Mã đề tài -->
                        <td
                            v-if="row.isFirst"
                            class="p-3 text-center"
                            :rowspan="row.rowSpan"
                        >
                            {{
                                row.topic?.MaDT ||
                                row.topic?.id ||
                                "-"
                            }}
                        </td>

                        <!-- Tên đề tài -->
                        <td
                            v-if="row.isFirst"
                            class="p-3 text-center"
                            :rowspan="row.rowSpan"
                        >
                            {{
                                row.topic?.TenDeTai ||
                                row.topic?.TenDT ||
                                row.topic?.title ||
                                "-"
                            }}
                        </td>

                        <!-- MSSV -->
                        <td class="p-3 text-center">
                            {{ row.MSSV || "-" }}
                        </td>

                        <!-- Họ tên -->
                        <td class="p-3 text-center">
                            {{ row.name || "-" }}
                        </td>

                        <!-- Action -->
                        <td
                            v-if="row.isFirst"
                            class="p-3 text-center"
                            :rowspan="row.rowSpan"
                        >
                            <div class="flex gap-2 justify-center">

                                <button
                                    @click="
                                        $emit('openReview', row)
                                    "
                                    class="bg-blue-500 text-white px-3 py-1 rounded text-sm"
                                >
                                    Chấm điểm
                                </button>

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
    reviewScoreSearch: {
        type: String,
        default: "",
    },

    reviewScoreRows: {
        type: Array,
        default: () => [],
    },

    isTopicHovered: {
        type: Function,
        required: true,
    },
})

defineEmits([
    "updateSearch",
    "openReview",
    "hoverTopic",
    "leaveTopic",
])
</script>