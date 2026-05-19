<template>
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-indigo-600">
                ĐIỂM PHẢN BIỆN
            </h2>

            <input
                :value="reviewScoreSearch"
                @input="$emit('updateSearch', $event.target.value)"
                type="text"
                placeholder="Tìm mã hoặc tên đề tài..."
                class="w-72 px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none"
            />
        </div>

        <!-- Table -->
        <div
            class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
        >
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <!-- Header -->
                    <thead class="bg-indigo-100 text-indigo-700">
                        <tr>
                            <th class="px-4 py-3 text-center font-semibold">
                                STT
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Mã đề tài
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Tên đề tài
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                MSSV
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Họ và tên sinh viên
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody>
                        <!-- Empty -->
                        <tr v-if="reviewScoreRows.length === 0">
                            <td
                                colspan="6"
                                class="py-8 text-center text-gray-400"
                            >
                                Không có đề tài
                            </td>
                        </tr>

                        <!-- Rows -->
                        <tr
                            v-for="(row, idx) in reviewScoreRows"
                            :key="
                                (row.topic?.MaDT ||
                                    row.topic?.id ||
                                    't') +
                                '-' +
                                idx
                            "
                            @mouseenter="$emit('hoverTopic', row.topic)"
                            @mouseleave="$emit('leaveTopic')"
                            :class="[
                                isTopicHovered(row.topic)
                                    ? 'bg-indigo-50'
                                    : 'bg-white',

                                row.isFirst
                                    ? 'border-t-[8px] border-gray-100'
                                    : '',

                                'transition-colors duration-150'
                            ]"
                        >
                            <!-- STT -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-4 text-center align-middle text-gray-700"
                            >
                                {{ row.stt }}
                            </td>

                            <!-- Mã đề tài -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-4 text-center align-middle"
                            >
                                <span
                                    class="px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-bold"
                                >
                                    {{
                                        row.topic?.MaDT ||
                                        row.topic?.id ||
                                        "-"
                                    }}
                                </span>
                            </td>

                            <!-- Tên đề tài -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-4 align-middle"
                            >
                                <div
                                    class="max-w-[260px] font-semibold text-gray-800 leading-relaxed"
                                >
                                    {{
                                        row.topic?.TenDeTai ||
                                        row.topic?.TenDT ||
                                        row.topic?.title ||
                                        "-"
                                    }}
                                </div>
                            </td>

                            <!-- MSSV -->
                            <td
                                class="px-4 py-4 text-center text-gray-700 whitespace-nowrap"
                            >
                                {{ row.MSSV || "-" }}
                            </td>

                            <!-- Họ tên -->
                            <td
                                class="px-4 py-4 text-gray-800 font-medium whitespace-nowrap"
                            >
                                {{ row.name || "-" }}
                            </td>

                            <!-- Action -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-4 text-center align-middle"
                            >
                                <button
                                    @click="$emit('openReview', row)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all"
                                >
                                    Chấm điểm
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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
});

defineEmits([
    "updateSearch",
    "openReview",
    "hoverTopic",
    "leaveTopic",
]);
</script>