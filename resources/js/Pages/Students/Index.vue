<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <Header :user="user" />

    <!-- Content -->
    <div class="flex flex-1">
      <!-- Sidebar -->
      <Sidebar :currentView="currentView" @changeView="setCurrentView" />

      <!-- Main -->
      <main class="flex-1">
        <!-- Loading state -->
        <div v-if="!user || isLoading" class="p-8 text-center text-slate-500">
          Đang tải dữ liệu…
        </div>

        <!-- Dashboard -->
        <Maincontent
          v-else-if="currentView === 'dashboard'"
          :topics="topics"
        />

        <!-- Other views unchanged -->
        <MeetingSchedule v-if="currentView === 'meetingSchedule'" />
        <Score50
          v-if="currentView === 'score50'"
          :evaluation="evaluation"
          :loading="loadingEvaluation"
          :student="student"
        />
        <ReviewLecturer v-if="currentView === 'reviewLecturer'" />
        <ReviewSchedule v-if="currentView === 'reviewSchedule'" />
        <DefenseCommittee v-if="currentView === 'defenseCommittee'" />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from "vue"
import axios from "axios"

import Header from "./components/Header.vue"
import Sidebar from "./components/Sidebar.vue"
import Maincontent from "./components/Maincontent.vue"
import MeetingSchedule from "./pages/MeetingSchedule.vue"
import Score50 from "./pages/Score50.vue"
import ReviewLecturer from "./pages/ReviewLecturer.vue"
import DefenseCommittee from "./pages/DefenseCommittee.vue"
import ReviewSchedule from "./pages/ReviewSchedule.vue"

const props = defineProps({
  user: Object,
  mssv: String,
})

const currentView = ref("dashboard")
const topics = ref([])
const isLoading = ref(false)

// ----- Evaluation state (MISSING) -----
const evaluation = ref(null)           
const loadingEvaluation = ref(false)
const student = ref(null)              // Add student state to pass to StudentInfo component

// Fetch topics based on the current user (your existing code)
watch(
  () => props.user,
  (user) => {
    if (user) {
      fetchTopics(user)
    }
  },
  { immediate: true }
)

async function fetchTopics(user) {
  isLoading.value = true
  try {
    if (user.role === "SinhVien" && props.mssv) {
      const studenta = await axios.get(`/students/${props.mssv}`).then(res => res.data)
      if (studenta) {
        const groupStr = studenta.Nhom
        const groupNumber = groupStr ? parseInt(groupStr.split('-')[1]) : 0
        const teacherName = await axios.post(`/teacher-by-magv/${studenta.Giang_vien_huong_dan}`)
        const topic = await axios.get(`/topic-by-student/${props.mssv}`).then(res => res.data)
        topics.value = [
          {
            code: topic?.MaDT || "N/A",
            title: topic?.TenDeTai || "Chưa có đề tài",
            lecturer: teacherName.data.Ho_va_Ten || "",
            limit: groupNumber,
            status: topic.TrangThai || "",
          },
        ]
      } else {
        topics.value = []
      }
    } else {
      const { data } = await axios.post("/topics/getAll")
      topics.value = data
    }
  } catch (error) {
    console.error("Lỗi khi tải danh sách đề tài:", error)
    topics.value = []
  } finally {
    isLoading.value = false
  }
}

// Fetch evaluation when switching to score50 (keep this)
watch(currentView, (newView) => {
  if (newView === 'score50' && !evaluation.value && props.mssv) {
    fetchEvaluation()
  }
}, { immediate: true })

async function fetchEvaluation() {
  loadingEvaluation.value = true
  try {
    const studenta = await axios.get(`/students/${props.mssv}`).then(res => res.data)
    const groupStr = studenta.Nhom
    const groupNumber = groupStr ? parseInt(groupStr.split('-')[1]) : 0
    const teacherName = await axios.post(`/teacher-by-magv/${studenta.Giang_vien_huong_dan}`)
    const topic = await axios.get(`/topic-by-student/${props.mssv}`).then(res => res.data)
    evaluation.value = {
      topic: topic.TenDeTai || "Chưa có đề tài",
      lecturer: teacherName.data.Ho_va_Ten || "",
      date: topic.TrangThai || "",
      score: studenta.Diem ?? 0,
      result: topic.TrangThai || "Chưa có kết quả",
      comment: studenta.GhiChu || "Không có nhận xét",
    }
    student.value = {
      name: studenta.Ho_va_Ten || "",
      mssv: studenta.MSSV || "",
      class: studenta.Lop || "",
      group: groupNumber || "",
    }
  } catch (error) {
    console.error("Lỗi tải đánh giá 50%:", error)
    evaluation.value = {
      topic: "",
      lecturer: "",
      date: "",
      score: 0,
      result: "Lỗi tải dữ liệu",
      comment: ""
    }
  } finally {
    loadingEvaluation.value = false
  }
}

function setCurrentView(view) {
  currentView.value = view
}
</script>