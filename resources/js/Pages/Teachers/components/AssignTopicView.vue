<template>
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-indigo-600">
                PHÂN CÔNG ĐỀ TÀI
            </h2>

            <input
                :value="assignSearch"
                @input="handleSearch"
                type="text"
                placeholder="Tìm sinh viên hoặc đề tài..."
                class="w-64 px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none"
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
                                MSSV
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Họ và tên
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Nhóm
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Tên đề tài
                            </th>

                            <th class="px-4 py-3 text-left font-semibold">
                                Mô tả
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Trạng thái
                            </th>

                            <th class="px-4 py-3 text-center font-semibold">
                                Thao tác
                            </th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody>
             <tr
    v-for="(row, idx) in processedAssignments"
    :key="row.MSSV || idx"
    @mouseenter="hoveredGroup = row.group"
    @mouseleave="hoveredGroup = null"
    :class="[
        hoveredGroup === row.group
            ? 'bg-indigo-50'
            : 'bg-white',

        row.isFirst
            ? 'border-t-[8px] border-gray-100'
            : '',

        'transition-colors duration-150'
    ]"
>
                            <!-- MSSV -->
                            <td
                                class="px-4 py-3 text-center text-gray-700 whitespace-nowrap"
                            >
                                {{ row.MSSV }}
                            </td>

                            <!-- Họ tên -->
                            <td
                                class="px-4 py-3 text-gray-700 whitespace-nowrap"
                            >
                                {{ row.name }}
                            </td>

                            <!-- Nhóm -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-3 text-center align-middle"
                            >
                                <div class="flex justify-center">
                                    <span
                                        class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-full bg-indigo-500 text-white text-xs font-bold"
                                    >
                                        {{ row.group }}
                                    </span>
                                </div>
                            </td>

                            <!-- Tên đề tài -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-3 align-middle"
                            >
                                <div
                                    class="font-medium text-gray-800 max-w-[220px]"
                                >
                                    {{ row.topic?.TenDeTai || "Chưa có đề tài" }}
                                </div>
                            </td>

                            <!-- Mô tả -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-3 align-middle"
                            >
                                <div
                                    class="max-w-[250px] text-xs text-gray-500 leading-relaxed line-clamp-2"
                                >
                                    {{ row.topic?.MoTa || "Không có mô tả" }}
                                </div>
                            </td>

                            <!-- Trạng thái -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-3 text-center align-middle"
                            >
                                <span
                                    class="px-3 py-1 rounded-full text-[11px] font-semibold"
                                    :class="
                                        row.topic?.TrangThai ===
                                        'Được tiếp tục'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-yellow-100 text-yellow-700'
                                    "
                                >
                                    {{
                                        row.topic?.TrangThai ||
                                        "Chưa cập nhật"
                                    }}
                                </span>
                            </td>

                            <!-- Action -->
                            <td
                                v-if="row.isFirst"
                                :rowspan="row.rowSpan"
                                class="px-4 py-3 align-middle"
                            >
                                <div class="flex flex-col gap-2 min-w-[140px]">
                                    <button
                                        @click="$emit('openAssign', row)"
                                        class="bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg text-xs font-semibold transition-all"
                                    >
                                        Phân công
                                    </button>

                                    <button
                                        @click="
                                            $emit(
                                                'downloadTemplate',
                                                row.topic?.MaDT
                                            )
                                        "
                                        class="bg-indigo-500 hover:bg-indigo-600 text-white py-2 rounded-lg text-xs font-semibold transition-all"
                                    >
                                        Xuất nhiệm vụ
                                    </button>
                                </div>
                            </td>
                        </tr>

                        <!-- Empty -->
                        <tr v-if="processedAssignments.length === 0">
                            <td
                                colspan="7"
                                class="py-8 text-center text-gray-400"
                            >
                                Không tìm thấy dữ liệu
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";

const props = defineProps({
    assignments: {
        type: Array,
        default: () => [],
    },

    assignSearch: String,
});

const emit = defineEmits([
    "updateSearch",
    "openAssign",
    "downloadTemplate",
]);

const hoveredGroup = ref(null);

function handleSearch(e) {
    emit("updateSearch", e.target.value);
}

/*
|--------------------------------------------------------------------------
| Xử lý rowspan nhóm
|--------------------------------------------------------------------------
*/
const processedAssignments = computed(() => {
    if (!props.assignments?.length) return [];

    const result = [];
    const items = props.assignments;

    let i = 0;

    while (i < items.length) {
        const current = items[i];
        const currentGroup = current.group;

        let rowSpan = 1;
        let j = i + 1;

        while (
            j < items.length &&
            items[j].group === currentGroup &&
            currentGroup
        ) {
            rowSpan++;
            j++;
        }

        result.push({
            ...current,
            isFirst: true,
            rowSpan,
        });

        for (let k = i + 1; k < j; k++) {
            result.push({
                ...items[k],
                isFirst: false,
                rowSpan: 0,
            });
        }

        i = j;
    }

    return result;
});
</script>