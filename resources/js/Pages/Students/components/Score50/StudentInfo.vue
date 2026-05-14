<template>
  <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-6 mb-8">
    <div class="flex flex-col md:flex-row md:items-center gap-6">
      <!-- Avatar -->
      <div class="w-24 h-24 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex items-center justify-center text-4xl font-bold shadow-lg">
        {{ student?.name?.charAt(0)?.toUpperCase() }}
      </div>

      <!-- Info -->
      <div class="flex-1">
        <p class="text-sm text-slate-500">Sinh viên thực hiện</p>
        <h2 class="text-2xl font-bold text-slate-800 mt-1">{{ student?.name }}</h2>

        <div class="flex flex-wrap items-center gap-3 mt-4">
          <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
            🎓 MSSV: {{ student?.mssv }}
          </div>
          <div class="px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 text-sm font-medium">
            🏫 Lớp: {{ student?.class }}
          </div>
          <div class="px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 text-sm font-semibold">
            👥 {{ student?.group }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  student: Object,
})
</script>