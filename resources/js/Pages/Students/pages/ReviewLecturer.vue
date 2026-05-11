<template>
  <div class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8">
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <p class="text-slate-500 text-lg">Đang tải dữ liệu phản biện…</p>
    </div>

    <template v-else-if="review">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Giảng viên phản biện</h1>
          <p class="text-slate-500 mt-2">Thông tin giảng viên phản biện luận văn tốt nghiệp</p>
        </div>

        <!-- Dynamic status badge -->
        <div
          class="px-5 py-3 rounded-2xl font-semibold shadow-sm"
          :class="
            review.reviewer
              ? 'bg-blue-100 text-blue-700'
              : 'bg-yellow-100 text-yellow-700'
          "
        >
          {{ review.reviewer ? '📢 Đã phân công phản biện' : '⏳ Chưa phân công phản biện' }}
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50">
          <h2 class="text-lg font-bold text-slate-800">Thông tin phản biện luận văn</h2>
          <p class="text-sm text-slate-500 mt-1">
            Sinh viên xem thông tin giảng viên phản biện và lịch phản biện dự kiến
          </p>
        </div>

        <div class="p-8">
          <Topic :review="review" />
          <LecturerCard :review="review" />
          <ReviewDate :review="review" />
        </div>
      </div>
    </template>

    <!-- Fallback when no data loaded at all -->
    <div v-else class="text-center text-slate-500 py-16">
      Không có dữ liệu phản biện
    </div>
  </div>
</template>

<script setup>
import LecturerCard from '../components/ReviewLecturer/LecturerCard.vue'
import Topic from '../components/ReviewLecturer/Topic.vue'
import ReviewDate from '../components/ReviewLecturer/ReviewDate.vue'

// Receive props from parent Index.vue
defineProps({
  review: Object, 
  loading: Boolean,
})
</script>