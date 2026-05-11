<template>
  <div class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8">
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <p class="text-slate-500 text-lg">Đang tải dữ liệu hội đồng…</p>
    </div>

    <template v-else-if="defense">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Hội đồng bảo vệ</h1>
          <p class="text-slate-500 mt-2">
            Theo dõi thông tin hội đồng, lịch bảo vệ và thành viên tham gia chấm luận văn
          </p>
        </div>

        <div :class="statusClass(defense.status)" class="px-5 py-3 rounded-2xl font-semibold shadow-sm">
          {{ defense.status }}
        </div>
      </div>

      <DefenseOverview :defense="defense" />
      <CommitteeMembers :members="members" />
      <DefenseRequirements :requirements="requirements" />
    </template>

    <!-- Fallback -->
    <div v-else class="text-center text-slate-500 py-16">
      Không có dữ liệu hội đồng
    </div>
  </div>
</template>

<script setup>
import CommitteeMembers from '../components/DefenseCommittee/CommitteeMembers.vue'
import DefenseOverview from '../components/DefenseCommittee/DefenseOverview.vue'
import DefenseRequirements from '../components/DefenseCommittee/DefenseRequirements.vue'

const props = defineProps({
  defense: Object,      
  members: Array,       
  requirements: Array,  
  loading: Boolean,
})

function statusClass(status) {
  if (status === 'Đang diễn ra') return 'bg-yellow-100 text-yellow-700'
  if (status === 'Sắp diễn ra') return 'bg-blue-100 text-blue-700'
  if (status === 'Đã diễn ra') return 'bg-emerald-100 text-emerald-700'
  return 'bg-gray-100 text-gray-700'
}
</script>