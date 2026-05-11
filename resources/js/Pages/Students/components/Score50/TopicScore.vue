<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Topic -->
    <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-sm p-6">
      <p class="text-sm text-slate-500">Tên đề tài</p>
      <h2 class="text-2xl font-bold text-slate-800 mt-2 leading-relaxed">{{ topic }}</h2>

      <div class="mt-5 flex items-center gap-3">
        <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
          👨‍🏫 GVHD: {{ lecturer }}
        </div>
        <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
          📅 Đánh giá: {{ date }}
        </div>
      </div>
    </div>

    <!-- Score -->
    <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 flex flex-col items-center justify-center">
      <p class="text-sm text-slate-500">Điểm đánh giá</p>
      <div :class="score >= 50 ? 'text-emerald-600' : 'text-red-500'" class="text-7xl font-bold mt-4">
        {{ score }}
      </div>
      <p class="text-slate-500 mt-2">/ 100 điểm</p>
    </div>
  </div>
</template>

<script setup>
defineProps({
  topic:    { type: String, default: 'Chưa có đề tài' },
  lecturer: { type: String, default: '' },
  date:     { type: String, default: '' },
  score:    { type: Number, default: 0 },
})
</script>