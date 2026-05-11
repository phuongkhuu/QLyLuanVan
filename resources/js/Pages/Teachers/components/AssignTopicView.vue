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

                <tbody>
  <tr v-for="(row, idx) in assignments" :key="idx">
    <td class="p-3 text-center">{{ row.MSSV }}</td>
    <td class="p-3 text-center">{{ row.name }}</td>

    <!-- Show topic info ONLY on the first row of each group, and span down -->
    <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center">
      {{ row.group }}
    </td>
    <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center">
      {{ row.topic?.TenDeTai }}
    </td>
    <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center">
      {{ row.topic?.MoTa }}
    </td>
    <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center">
      {{ row.topic?.TrangThai }}
    </td>

    <td v-if="row.isFirst" :rowspan="row.rowSpan" class="p-3 text-center">
      <div class="flex gap-2 justify-center">
        <button
          @click="$emit('openAssign', row)"
          class="bg-blue-500 text-white px-3 py-1 rounded text-sm"
        >
          Phân công
        </button>
        <button
          @click="$emit('downloadTemplate', row.topic?.MaDT)"
          class="bg-indigo-500 text-white px-3 py-1 rounded text-sm"
        >
          Xuất nhiệm vụ
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
    assignments: Array,
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
</script>