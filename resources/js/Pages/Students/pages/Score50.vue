<template>
  <div class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8">
    <!-- Loading state -->
    <div v-if="loading" class="flex justify-center items-center h-64">
      <p class="text-slate-500 text-lg">Đang tải dữ liệu đánh giá…</p>
    </div>

    <template v-else-if="evaluation">
      <!-- Header -->
      <div class="flex items-center justify-between mb-8">
        <div>
          <h1 class="text-3xl font-bold text-slate-800">Kết quả đánh giá 50%</h1>
          <p class="text-slate-500 mt-2">Theo dõi kết quả đánh giá tiến độ luận văn</p>
        </div>

        <div
          :class="
            evaluation.date === 'Được tiếp tục'
              ? 'bg-emerald-100 text-emerald-700'
              : 'bg-red-100 text-red-700'
          "
          class="px-5 py-3 rounded-2xl font-semibold shadow-sm"
        >
          {{ evaluation.date }}
        </div>
      </div>

      <!-- Student Info (pass full student object) -->
      <StudentInfo :student="student" />

      <!-- Topic & Score (all needed fields) -->
      <TopicScore
        :topic="evaluation.topic"
        :lecturer="evaluation.lecturer"
        :date="evaluation.date"
        :score="evaluation.score"
      />

      <!-- Lecturer Comment (pass entire evaluation object) -->
      <LecturerComment :evaluation="evaluation" />
    </template>

    <!-- Fallback when no data loaded -->
    <div v-else class="text-center text-slate-500 py-16">
      Không có dữ liệu đánh giá
    </div>
  </div>
</template>

<script setup>
import LecturerComment from '../components/Score50/LecturerComment.vue'
import StudentInfo from '../components/Score50/StudentInfo.vue'
import TopicScore from '../components/Score50/TopicScore.vue'

defineProps({
  evaluation: Object,    
  loading: Boolean,
  student: Object,       
})
</script>