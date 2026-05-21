<template>
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-indigo-600">
                ĐÁNH GIÁ 50%
            </h2>

            <div class="flex items-center gap-3">
                <button
                    @click="saveAllEvaluations"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all"
                >
                    Lưu tất cả điểm & ghi chú
                </button>

                <input
                    :value="evaluationSearch"
                    @input="$emit('updateSearch', $event.target.value)"
                    type="text"
                    placeholder="Tìm MSSV / tên..."
                    class="w-72 px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                />
            </div>
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
                            <th class="px-3 py-3 text-center font-semibold w-14">
                                STT
                            </th>

                            <th class="px-3 py-3 text-center font-semibold">
                                MSSV
                            </th>

                            <th class="px-3 py-3 text-left font-semibold">
                                Họ và tên
                            </th>

                            <th class="px-3 py-3 text-center font-semibold">
                                Lớp
                            </th>

                            <th class="px-3 py-3 text-center font-semibold">
                                Nhóm
                            </th>

                            <th class="px-3 py-3 text-left font-semibold">
                                Tên đề tài
                            </th>

                            <th class="px-3 py-3 text-center font-semibold w-32">
                                Điểm
                            </th>

                            <th class="px-3 py-3 text-left font-semibold">
                                Ghi chú
                            </th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody>
                        <!-- Empty -->
                        <tr v-if="students.length === 0">
                            <td
                                colspan="9"
                                class="py-8 text-center text-gray-400"
                            >
                                Không có dữ liệu sinh viên
                            </td>
                        </tr>

                        <!-- Rows -->
                        <tr
                            v-for="(s, idx) in students"
                            :key="s.mssv || s.id || idx"
                            class="border-t border-gray-100 hover:bg-indigo-50 transition-colors duration-150"
                        >

                            <!-- STT -->
                            <td class="px-3 py-3 text-center text-gray-700">
                                {{ idx + 1 }}
                            </td>

                            <!-- MSSV -->
                            <td
                                class="px-3 py-3 text-center text-gray-700 whitespace-nowrap"
                            >
                                {{ s.mssv }}
                            </td>

                            <!-- Họ tên -->
                            <td
                                class="px-3 py-3 text-gray-800 font-medium whitespace-nowrap"
                            >
                                {{ s.name }}
                            </td>

                            <!-- Lớp -->
                            <td class="px-3 py-3 text-center text-gray-700">
                                {{ s.Lop || "-" }}
                            </td>

                            <!-- Nhóm -->
                            <td class="px-3 py-3 text-center">
                                <span
                                    class="inline-flex items-center justify-center min-w-[36px] h-[36px] px-2 rounded-full bg-indigo-500 text-white text-xs font-bold"
                                >
                                    {{ s.group || "-" }}
                                </span>
                            </td>

                            <!-- Đề tài -->
                            <td class="px-3 py-3">
                                <div
                                    class="max-w-[240px] text-gray-700 font-medium line-clamp-2"
                                >
                                    {{ s.topic || s.title || "-" }}
                                </div>
                            </td>

                            <!-- Điểm -->
                            <td class="px-3 py-3 text-center">
                                <div
                                    class="flex items-center justify-center gap-2"
                                >
                                    <input
                                        v-model="s.score"
                                        @keyup.enter="$emit('updateScore', s)"
                                        type="number"
                                        min="0"
                                        max="100"
                                        placeholder="0 - 100"
                                        :disabled="!canGrade50"
                                        class="w-20 h-9 rounded-md border border-gray-300 text-center text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none disabled:bg-gray-100"
                                    />

                                    <span class="text-gray-500 text-xs">
                                        %
                                    </span>
                                </div>
                            </td>

                            <!-- Note -->
                            <td class="px-3 py-3">
                                <input
                                    v-model="s.note"
                                    @keyup.enter="$emit('updateNote', s)"
                                    type="text"
                                    placeholder="Ghi chú..."
                                    :disabled="!canGrade50"
                                    class="w-full h-9 rounded-md border border-gray-300 px-3 text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none disabled:bg-gray-100"
                                />
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div
            v-if="showBulkModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div
                class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl"
            >
                <h3 class="text-xl font-bold text-gray-800 mb-5">
                    Đánh giá chung cho
                    {{ selectedStudents.length }} sinh viên
                </h3>

                <div class="space-y-4">
                    <!-- Score -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            Điểm chung (0 - 100%)
                        </label>

                        <input
                            v-model="bulkScore"
                            type="number"
                            min="0"
                            max="100"
                            placeholder="Để trống nếu không đổi điểm"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                    </div>

                    <!-- Note -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700">
                            Ghi chú chung
                        </label>

                        <input
                            v-model="bulkNote"
                            type="text"
                            placeholder="Để trống nếu không đổi ghi chú"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        />
                    </div>
                </div>

                <!-- Actions -->
                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="closeBulkModal"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all"
                    >
                        Hủy
                    </button>

                    <button
                        @click="submitBulkEvaluation"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all"
                    >
                        Lưu thay đổi
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const props = defineProps({
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
});

const emit = defineEmits([
    "updateSearch",
    "updateScore",
    "updateNote",
    "refreshEvaluations",
]);

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/
const selectedStudents = ref([]);
const showBulkModal = ref(false);

const bulkScore = ref("");
const bulkNote = ref("");

/*
|--------------------------------------------------------------------------
| Checkbox
|--------------------------------------------------------------------------
*/
const isAllSelected = computed(() => {
    return (
        props.students.length > 0 &&
        selectedStudents.value.length === props.students.length
    );
});

function toggleSelectAll(event) {
    if (event.target.checked) {
        selectedStudents.value = props.students.map(
            (s) => s.mssv
        );
    } else {
        selectedStudents.value = [];
    }
}

/*
|--------------------------------------------------------------------------
| Modal
|--------------------------------------------------------------------------
*/
function openBulkModal() {
    if (selectedStudents.value.length === 0) {
        alert("Vui lòng chọn ít nhất 1 sinh viên!");
        return;
    }

    bulkScore.value = "";
    bulkNote.value = "";

    showBulkModal.value = true;
}

function closeBulkModal() {
    showBulkModal.value = false;
}

/*
|--------------------------------------------------------------------------
| Bulk Evaluation
|--------------------------------------------------------------------------
*/
function saveAllEvaluations() {
    // Build an array of { mssv, score, note } for EVERY student in the current list
    const updates = props.students.map(s => ({
        mssv: s.mssv,
        score: s.score !== null && s.score !== undefined ? s.score : null,
        note: s.note || null
    }));

    axios.post('/update-all-evaluations', { updates })
        .then(() => {
            alert('Đã lưu tất cả điểm và ghi chú!');
            emit('refreshEvaluations');
        })
        .catch(err => {
            alert(err.response?.data?.error || 'Lưu thất bại!');
        });
}
</script>