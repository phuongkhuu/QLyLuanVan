<template>
    <div class="space-y-4">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-indigo-600">
                QUẢN LÝ SINH VIÊN
            </h2>

            <div class="flex items-center gap-3">
                <button
                    @click="saveAllGroups"
                    class="bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all"
                >
                    Lưu tất cả
                </button>

                <input
                    v-model="studentSearch"
                    type="text"
                    placeholder="Tìm kiếm sinh viên..."
                    class="w-64 px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none"
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


                            <th
                                class="px-3 py-3 text-center font-semibold w-14"
                            >
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
                                SĐT
                            </th>

                            <th class="px-3 py-3 text-center font-semibold">
                                Email
                            </th>

                            <th
                                class="px-3 py-3 text-center font-semibold w-24"
                            >
                                Nhóm
                            </th>
                        </tr>
                    </thead>

                    <!-- Body -->
                    <tbody>
                        <tr
                            v-for="(s, idx) in filteredStudents"
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

                            <!-- SĐT -->
                            <td class="px-3 py-3 text-center text-gray-500">
                                {{ s.phone || "-" }}
                            </td>

                            <!-- Email -->
                            <td
                                class="px-3 py-3 text-center text-gray-700 whitespace-nowrap"
                            >
                                {{ s.email || "-" }}
                            </td>

                            <!-- Nhóm -->
                            <td class="px-3 py-3 text-center">
                                <input
                                    v-model="s.group"
                                    @keyup.enter="updateGroup(s)"
                                    class="w-16 h-9 rounded-md border border-gray-300 text-center text-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                />
                            </td>
                        </tr>

                        <!-- Empty -->
                        <tr v-if="filteredStudents.length === 0">
                            <td
                                colspan="8"
                                class="py-8 text-center text-gray-400"
                            >
                                Không tìm thấy sinh viên nào
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Modal -->
        <div
            v-if="showGroupModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm"
        >
            <div class="w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                <h3 class="text-xl font-bold text-gray-800 mb-5">
                    Gán nhóm cho {{ selectedStudents.length }} sinh viên
                </h3>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-700">
                        Nhập số nhóm
                    </label>

                    <input
                        v-model="bulkGroupNumber"
                        type="number"
                        min="1"
                        placeholder="VD: 1, 2, 3..."
                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                        @keyup.enter="submitBulkGroup"
                    />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button
                        @click="closeGroupModal"
                        class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all"
                    >
                        Hủy
                    </button>

                    <button
                        @click="submitBulkGroup"
                        :disabled="!bulkGroupNumber"
                        class="px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
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
});

const emit = defineEmits(["refreshStudents", "groupsUpdated"]);

const studentSearch = ref("");
const selectedStudents = ref([]);
const showGroupModal = ref(false);
const bulkGroupNumber = ref("");

/*
|--------------------------------------------------------------------------
| Filter sinh viên
|--------------------------------------------------------------------------
*/
const filteredStudents = computed(() => {
    const q = studentSearch.value.toLowerCase().trim();

    if (!q) return props.students;

    return props.students.filter((s) => {
        const text = [s.mssv, s.name, s.Lop, s.phone, s.email, s.group]
            .join(" ")
            .toLowerCase();

        return text.includes(q);
    });
});

/*
|--------------------------------------------------------------------------
| Checkbox
|--------------------------------------------------------------------------
*/
const isAllSelected = computed(() => {
    return (
        filteredStudents.value.length > 0 &&
        selectedStudents.value.length === filteredStudents.value.length
    );
});

function toggleSelectAll(event) {
    if (event.target.checked) {
        selectedStudents.value = filteredStudents.value.map((s) => s.mssv);
    } else {
        selectedStudents.value = [];
    }
}

/*
|--------------------------------------------------------------------------
| Modal
|--------------------------------------------------------------------------
*/
function openGroupModal() {
    if (selectedStudents.value.length === 0) {
        alert("Vui lòng chọn ít nhất 1 sinh viên!");
        return;
    }

    bulkGroupNumber.value = "";
    showGroupModal.value = true;
}

function closeGroupModal() {
    showGroupModal.value = false;
    bulkGroupNumber.value = "";
}

/*
|--------------------------------------------------------------------------
| Update đơn lẻ
|--------------------------------------------------------------------------
*/
function updateGroup(student) {
    axios
        .post("/update-student-group", {
            mssv: student.mssv,
            group_number: student.group,
        })
        .then(() => {
            alert("Cập nhật nhóm thành công!");

            emit("groupsUpdated", [
                {
                    mssv: student.mssv,
                    group: student.group,
                },
            ]);

            emit("refreshStudents");
        })
        .catch((err) => {
            alert(err.response?.data?.error || "Cập nhật nhóm thất bại!");
        });
}

/*
|--------------------------------------------------------------------------
| Update hàng loạt
|--------------------------------------------------------------------------
*/
function saveAllGroups() {
    const updates = props.students.map(s => ({
        mssv: s.mssv,
        group_number: s.group  
    }));

    axios.post('/update-student-groups', { updates })
        .then(() => {
            alert('Đã lưu tất cả nhóm thành công!');
            emit('refreshStudents');
        })
        .catch(err => {
            alert(err.response?.data?.error || 'Lưu thất bại!');
        });
}

</script>
