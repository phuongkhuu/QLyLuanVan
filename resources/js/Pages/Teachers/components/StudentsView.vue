<template>
    <div >
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-indigo-600">
                QUẢN LÝ SINH VIÊN
            </h2>
            <div class="flex items-center space-x-2">
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
                </tbody>
            </table>
        </div>
    </div>
</template>
<script setup>
import { ref, computed } from "vue";

const props = defineProps({
  students: {
    type: Array,
    default: () => []
  }
})

const studentSearch = ref("")

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

function updateGroup(student) {
    axios
        .post("/update-student-group", {
            mssv: student.mssv,
            group_number: student.group,
        })
        .then((res) => {
            alert("Cập nhật nhóm thành công!");
        })
        .catch((err) => {
            alert(err.response.data.error || "Cập nhật nhóm thất bại!");
        });
    fetchStudents();
}


</script>
