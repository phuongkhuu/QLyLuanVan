<template>
    <div>
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                ĐÁNH GIÁ 50%
            </h2>

            <div class="flex items-center gap-4">
                <button
                    v-if="canGrade50"
                    @click="openBulkModal"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded shadow transition-colors"
                >
                    Đánh giá sinh viên
                </button>

                <input
                    :value="evaluationSearch"
                    @input="$emit('updateSearch', $event.target.value)"
                    type="text"
                    placeholder="Tìm MSSV / tên..."
                    class="w-80 px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-300"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-indigo-100 text-indigo-700">
                    <tr>
                        <th class="p-3 text-center w-12">
                            <input 
                                type="checkbox" 
                                :checked="isAllSelected" 
                                @change="toggleSelectAll" 
                                :disabled="!canGrade50"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 disabled:opacity-50"
                            />
                        </th>
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
                    <tr v-if="students.length === 0">
                        <td
                            colspan="9"
                            class="p-4 text-center text-gray-500"
                        >
                            Không có dữ liệu sinh viên
                        </td>
                    </tr>

                    <tr
                        v-for="(s, idx) in students"
                        :key="s.mssv || s.id || idx"
                        class="hover:bg-indigo-50"
                    >
                        <td class="p-3 text-center">
                            <input 
                                type="checkbox" 
                                :value="s.mssv" 
                                v-model="selectedStudents"
                                :disabled="!canGrade50"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 disabled:opacity-50"
                            />
                        </td>

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

                        <td class="p-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <input
                                    v-model="s.score"
                                    @keyup.enter="$emit('updateScore', s)"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="w-20 px-2 py-1 border rounded text-sm"
                                    placeholder="0 - 100"
                                    :disabled="!canGrade50"
                                />
                                <span class="text-sm text-gray-600">%</span>
                            </div>
                        </td>

                        <td class="p-3 text-center">
                            <input
                                v-model="s.note"
                                @keyup.enter="$emit('updateNote', s)"
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

        <div v-if="showBulkModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-[400px]">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Đánh giá chung cho {{ selectedStudents.length }} sinh viên</h3>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mức điểm chung (0-100%):</label>
                        <input 
                            v-model="bulkScore" 
                            type="number" 
                            min="0"
                            max="100"
                            placeholder="Để trống nếu không muốn đổi điểm" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ghi chú chung:</label>
                        <input 
                            v-model="bulkNote" 
                            type="text" 
                            placeholder="Để trống nếu không muốn đổi ghi chú" 
                            class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        />
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        @click="closeBulkModal" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors"
                    >
                        Hủy
                    </button>
                    <button 
                        @click="submitBulkEvaluation" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors"
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
// Đảm bảo axios có thể truy cập được (import axios from 'axios' hoặc dùng window.axios)

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
})

const emit = defineEmits([
    "updateSearch",
    "updateScore",
    "updateNote",
])

// Các biến quản lý trạng thái Đánh giá hàng loạt
const selectedStudents = ref([]);
const showBulkModal = ref(false);
const bulkScore = ref("");
const bulkNote = ref("");

// Logic Checkbox "Chọn tất cả"
const isAllSelected = computed(() => {
    return props.students.length > 0 && selectedStudents.value.length === props.students.length;
});

function toggleSelectAll(event) {
    if (event.target.checked) {
        selectedStudents.value = props.students.map(s => s.mssv);
    } else {
        selectedStudents.value = [];
    }
}

// Logic Modal Đánh giá
function openBulkModal() {
    if (selectedStudents.value.length === 0) {
        alert("Vui lòng tick chọn ít nhất 1 sinh viên!");
        return;
    }
    bulkScore.value = "";
    bulkNote.value = "";
    showBulkModal.value = true;
}

function closeBulkModal() {
    showBulkModal.value = false;
}

// Hàm gửi dữ liệu lên Backend
function submitBulkEvaluation() {
    if (bulkScore.value === "" && bulkNote.value === "") {
        alert("Vui lòng nhập Điểm hoặc Ghi chú để đánh giá!");
        return;
    }

    axios
        .post("/update-multiple-evaluation", {
            mssvs: selectedStudents.value,
            score: bulkScore.value !== "" ? bulkScore.value : null,
            note: bulkNote.value !== "" ? bulkNote.value : null,
        })
        .then((res) => {
            alert("Đánh giá hàng loạt thành công!");
            closeBulkModal();
            selectedStudents.value = []; // Reset danh sách
            
            // Tải lại trang để đồng bộ điểm mới
            window.location.reload(); 
        })
        .catch((err) => {
            alert(err.response?.data?.error || "Cập nhật đánh giá thất bại!");
        });
}
</script>