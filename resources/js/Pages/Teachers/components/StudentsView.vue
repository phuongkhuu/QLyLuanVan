<template>
    <div>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                QUẢN LÝ SINH VIÊN
            </h2>
            <div class="flex items-center space-x-4">
                <button 
                    @click="openGroupModal"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-4 py-2 rounded shadow transition-colors"
                >
                    Tạo nhóm
                </button>

                <input
                    v-model="studentSearch"
                    type="text"
                    placeholder="Tìm kiếm sinh viên..."
                    class="border rounded px-3 py-2 text-sm w-64"
                />
            </div>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-indigo-100 text-indigo-700">
                    <tr>
                        <th class="p-3 text-center w-12">
                            <input 
                                type="checkbox" 
                                :checked="isAllSelected" 
                                @change="toggleSelectAll" 
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                            />
                        </th>
                        <th class="p-3 text-center">STT</th>
                        <th class="p-3 text-center">MSSV</th>
                        <th class="p-3 text-center">Họ và tên</th>
                        <th class="p-3 text-center">Lớp</th>
                        <th class="p-3 text-center">SĐT</th>
                        <th class="p-3 text-center">Email</th>
                        <th class="p-3 text-center">Nhóm</th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(s, idx) in filteredStudents"
                        :key="s.mssv || s.id || idx"
                        class="hover:bg-indigo-50"
                    >
                        <td class="p-3 text-center">
                            <input 
                                type="checkbox" 
                                :value="s.mssv" 
                                v-model="selectedStudents"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
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
                            {{ s.phone || "-" }}
                        </td>
                        <td class="p-3 text-center">
                            {{ s.email || "-" }}
                        </td>
                        <td class="p-3 text-center">
                            <input
                                v-model="s.group"
                                @keyup.enter="updateGroup(s)"
                                class="border p-1 w-16 text-center"
                            />
                        </td>
                    </tr>
                    <tr v-if="filteredStudents.length === 0">
                        <td colspan="8" class="p-4 text-center text-gray-500">
                            Không tìm thấy sinh viên nào.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div v-if="showGroupModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-96">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Gán nhóm cho {{ selectedStudents.length }} sinh viên</h3>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nhập số thứ tự nhóm:</label>
                    <input 
                        v-model="bulkGroupNumber" 
                        type="number" 
                        min="1"
                        placeholder="VD: 1, 2, 3..." 
                        class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        @keyup.enter="submitBulkGroup"
                    />
                </div>

                <div class="flex justify-end space-x-3">
                    <button 
                        @click="closeGroupModal" 
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 transition-colors"
                    >
                        Hủy
                    </button>
                    <button 
                        @click="submitBulkGroup" 
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition-colors"
                        :disabled="!bulkGroupNumber"
                        :class="{ 'opacity-50 cursor-not-allowed': !bulkGroupNumber }"
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
// Đảm bảo axios đã được cài đặt hoặc gọi thông qua window.axios (phổ biến trong Laravel)

const props = defineProps({
  students: {
    type: Array,
    default: () => []
  }
})

const studentSearch = ref("")
const selectedStudents = ref([]) // Lưu MSSV của sinh viên được chọn
const showGroupModal = ref(false)
const bulkGroupNumber = ref("")

// Logic lọc sinh viên
const filteredStudents = computed(() => {
  const q = studentSearch.value.toLowerCase().trim()

  if (!q) return props.students

  return props.students.filter((s) => {
    const text = [
      s.mssv,
      s.name,
      s.Lop,
      s.phone,
      s.email,
      s.group,
    ]
      .join(" ")
      .toLowerCase()

    return text.includes(q)
  })
})

// Logic Checkbox "Chọn tất cả"
const isAllSelected = computed(() => {
    return filteredStudents.value.length > 0 && selectedStudents.value.length === filteredStudents.value.length;
})

function toggleSelectAll(event) {
    if (event.target.checked) {
        selectedStudents.value = filteredStudents.value.map(s => s.mssv);
    } else {
        selectedStudents.value = [];
    }
}

// Logic Mở/Đóng Modal
function openGroupModal() {
    if (selectedStudents.value.length === 0) {
        alert("Vui lòng tick chọn ít nhất 1 sinh viên để tạo nhóm!");
        return;
    }
    bulkGroupNumber.value = "";
    showGroupModal.value = true;
}

function closeGroupModal() {
    showGroupModal.value = false;
    bulkGroupNumber.value = "";
}

// Cập nhật đơn lẻ (Hàm cũ của bạn)
function updateGroup(student) {
    axios
        .post("/update-student-group", {
            mssv: student.mssv,
            group_number: student.group,
        })
        .then((res) => {
            alert("Cập nhật nhóm thành công!");
            // Giả định fetchStudents là hàm toàn cục hoặc gọi reload trang
            if (typeof fetchStudents === 'function') {
                fetchStudents();
            } else {
                location.reload(); // Cách fallback nếu không có fetchStudents
            }
        })
        .catch((err) => {
            alert(err.response?.data?.error || "Cập nhật nhóm thất bại!");
        });
}

// Cập nhật hàng loạt (Hàm mới)
function submitBulkGroup() {
    if (!bulkGroupNumber.value) return;

    axios
        .post("/update-multiple-student-group", {
            mssvs: selectedStudents.value,
            group_number: bulkGroupNumber.value,
        })
        .then((res) => {
            alert("Tạo nhóm thành công!");
            closeGroupModal();
            selectedStudents.value = []; // Reset danh sách chọn
            
            // Reload lại dữ liệu
            if (typeof fetchStudents === 'function') {
                fetchStudents();
            } else {
                window.location.reload(); 
            }
        })
        .catch((err) => {
            alert(err.response?.data?.error || "Cập nhật nhóm thất bại!");
        });
}
</script>