<template>
  <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden mb-8">
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <h2 class="text-lg font-bold text-slate-800">Thành viên hội đồng</h2>
          <p class="text-sm text-slate-500 mt-1">
            Danh sách giảng viên tham gia hội đồng bảo vệ luận văn
          </p>
        </div>
        <div class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-sm font-semibold">
          {{ members.length }} thành viên
        </div>
      </div>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-slate-100">
          <tr>
            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">STT</th>
            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Thành viên</th>
            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Chức vụ</th>
            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Email</th>
            <th class="px-6 py-4 text-left text-sm font-bold text-slate-700">Khoa/Bộ môn</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(member, index) in members"
            :key="member.position + member.name"
            class="border-t border-slate-100 hover:bg-blue-50/40 transition-all duration-200"
          >
            <td class="px-6 py-5">
              <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-semibold text-slate-700">
                {{ index + 1 }}
              </div>
            </td>

            <td class="px-6 py-5 min-w-64">
              <div class="flex items-center gap-3">
                <div
                  :class="member.avatarClass"
                  class="w-11 h-11 rounded-full text-white font-bold flex items-center justify-center"
                >
                  {{ member.name?.charAt(0)?.toUpperCase() }}
                </div>
                <h3 class="font-semibold text-slate-800">{{ member.name }}</h3>
              </div>
            </td>

            <td class="px-6 py-5">
              <div class="inline-flex px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
                {{ positionLabel(member.position) }}
              </div>
            </td>

            <td class="px-6 py-5 max-w-72">
              <p class="text-sm text-slate-600 break-all">{{ member.email }}</p>
            </td>

            <td class="px-6 py-5">
              <div class="inline-flex px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 text-sm font-semibold">
                {{ member.department }}
              </div>
            </td>
          </tr>

          <tr v-if="members.length === 0">
            <td colspan="5" class="py-16 text-center">
              <h3 class="font-semibold text-slate-700 text-lg">Chưa có thành viên hội đồng</h3>
              <p class="text-slate-500 text-sm mt-1">
                Hiện chưa có danh sách giảng viên trong hội đồng
              </p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
defineProps({
  members: {
    type: Array,
    default: () => [],
  },
})

// Map position codes to Vietnamese labels
const positionMap = {
  'chu_tich': 'Chủ tịch hội đồng',
  'thu_ky': 'Thư ký',
  'uy_vien': 'Ủy viên',
  'phan_bien_1': 'Phản biện 1',
  'phan_bien_2': 'Phản biện 2',
}

function positionLabel(position) {
  return positionMap[position] || position // fallback to raw value if not found
}
</script>