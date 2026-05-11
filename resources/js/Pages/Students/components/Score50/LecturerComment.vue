<template>
  <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
    <!-- Top -->
    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
      <h2 class="text-lg font-bold text-slate-800">Nhận xét của giảng viên</h2>
      <p class="text-sm text-slate-500 mt-1">Đánh giá tiến độ thực hiện luận văn</p>
    </div>

    <!-- Content -->
    <div class="p-6">
      <div class="flex items-center gap-4 mb-6">
        <div class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xl">
          {{ evaluation?.lecturer?.charAt(0)?.toUpperCase() }}
        </div>
        <div>
          <h3 class="font-bold text-slate-800 text-lg">{{ evaluation?.lecturer }}</h3>
          <p class="text-sm text-slate-500">Giảng viên hướng dẫn</p>
        </div>
      </div>

      <div class="rounded-3xl bg-slate-50 border border-slate-200 p-6 leading-relaxed text-slate-700">
        {{ evaluation?.comment }}
      </div>

      <div class="mt-6">
        <div
          :class="
            evaluation?.result === 'Được tiếp tục'
              ? 'bg-emerald-50 border-emerald-200 text-emerald-700'
              : 'bg-red-50 border-red-200 text-red-700'
          "
          class="border rounded-3xl p-5"
        >
          <h3 class="font-bold text-lg">Kết quả đánh giá</h3>
          <p class="mt-2">
            {{
              evaluation?.result === 'Được tiếp tục'
                ? 'Sinh viên đủ điều kiện tiếp tục thực hiện luận văn tốt nghiệp.'
                : 'Sinh viên không đạt yêu cầu tiến độ và bị đình chỉ luận văn.'
            }}
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  evaluation: Object,  
})
</script>