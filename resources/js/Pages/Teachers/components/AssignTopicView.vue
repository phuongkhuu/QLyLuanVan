<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                PHÂN CÔNG ĐỀ TÀI
            </h2>

            <input
                :value="assignSearch"
                @input="handleSearch"
                type="text"
                placeholder="Tìm theo sinh viên hoặc đề tài..."
                class="border rounded px-3 py-2 text-sm w-64"
            />
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-100 text-indigo-700">
                    <tr>
                        <th class="p-3 text-center">MSSV</th>
                        <th class="p-3 text-center">Họ và tên</th>
                        <th class="p-3 text-center">Nhóm</th>
                        <th class="p-3 text-center">Tên đề tài</th>
                        <th class="p-3 text-center">Mô tả</th>
                        <th class="p-3 text-center">Trạng thái</th>
                        <th class="p-3 text-center">Thao tác</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    <tr v-for="(row, idx) in processedAssignments" :key="row.MSSV || idx" class="hover:bg-indigo-50 transition-colors">
                        <td class="p-3 text-center align-middle">{{ row.MSSV }}</td>
                        <td class="p-3 text-center align-middle">{{ row.name }}</td>

                        <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center align-middle">
                            {{ row.group }}
                        </td>
                        <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center align-middle">
                            {{ row.topic?.TenDeTai }}
                        </td>
                        <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center align-middle">
                            {{ row.topic?.MoTa }}
                        </td>
                        <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center align-middle">
                            {{ row.topic?.TrangThai }}
                        </td>

                        <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center align-middle">
                            <div class="flex gap-2 justify-center">
                                <button
                                    @click="$emit('openAssign', row)"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                >
                                    Phân công
                                </button>
                                <button
                                    @click="$emit('downloadTemplate', row.topic?.MaDT)"
                                    class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded text-sm transition-colors"
                                >
                                    Xuất nhiệm vụ
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr v-if="processedAssignments.length === 0">
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            Không tìm thấy dữ liệu.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    assignments: {
        type: Array,
        default: () => []
    },
    assignSearch: String,
})

const emit = defineEmits([
    "updateSearch",
    "openAssign",
    "downloadTemplate",
])

function handleSearch(e) {
    emit("updateSearch", e.target.value)
}

// Logic tính toán gộp nhóm (Rowspan)
const processedAssignments = computed(() => {
    if (!props.assignments || props.assignments.length === 0) return [];

    const result = [];
    let i = 0;
    const items = props.assignments;

    while (i < items.length) {
        const currentItem = items[i];
        const currentGroup = currentItem.group;

        // Đếm xem có bao nhiêu sinh viên liền kề có cùng group
        // Điều kiện '&& currentGroup' giúp những sinh viên không có nhóm sẽ không bị gộp chung với nhau
        let rowSpan = 1;
        let j = i + 1;
        
        while (j < items.length && items[j].group === currentGroup && currentGroup) {
            rowSpan++;
            j++;
        }

        // Đánh dấu sinh viên đầu tiên của nhóm (isFirst = true) và gán giá trị rowspan
        result.push({
            ...currentItem,
            isFirst: true,
            rowSpan: rowSpan
        });

        // Đánh dấu các sinh viên còn lại trong nhóm (isFirst = false để ẩn các cột bị trùng)
        for (let k = i + 1; k < j; k++) {
            result.push({
                ...items[k],
                isFirst: false,
                rowSpan: 0
            });
        }

        // Nhảy bước duyệt qua những sinh viên đã được tính toán
        i = j;
    }

    return result;
})
</script>