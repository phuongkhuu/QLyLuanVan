<template>
  <div class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <Header :user="headerUser" />

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

        <!--Lịch gặp GVHD -->
        <MeetingSchedule v-if="currentView === 'meetingSchedule'" 
          :lichHenData="lichHenData"
          :gvhd="gvhd"
        />
        <!--Đánh giá 50%-->
        <Score50
          v-if="currentView === 'score50'"
          :evaluation="evaluation"
          :loading="loadingEvaluation"
          :student="student"
        />
        <!--Thông tin giảng viên phản biện-->
        <ReviewLecturer
          v-if="currentView === 'reviewLecturer'"
          :review="review"
          :loading="loadingReview"
        />
        <!--Lịch phản biện-->
        <ReviewSchedule v-if="currentView === 'reviewSchedule'" 
          :reviewSchedules="lichHenData"
          :reviewer="gvpb"
        />
        <!--Hội đồng-->
        <DefenseCommittee
            v-if="currentView === 'defenseCommittee'"
            :defense="defense"
            :members="committeeMembers"
            :requirements="[
                'Có mặt trước giờ bảo vệ ít nhất 15 phút.',
                'Chuẩn bị slide trình bày, báo cáo luận văn và tài liệu minh chứng.',
                'Kiểm tra lại thiết bị trình chiếu hoặc kết nối trực tuyến trước buổi bảo vệ.'
            ]"
            :loading="loadingDefense"
        />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, computed } from "vue"
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
const student = ref(null)             
const review = ref(null)
const loadingReview = ref(false)
const defense = ref(null)
const committeeMembers = ref([])
const loadingDefense = ref(false)
const lichHenData = ref([]);
const gvpb = ref(null);
const gvhd = ref(null);
const studenta = ref(null)      
const guideTeacher = ref(null) 

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

async function fetchLichHenHuongDan() {
    try {
        const studenta = await axios.get(`/students/${props.mssv}`).then(res => res.data);
        const res = await axios.get("/lich-hen/" + studenta.Giang_vien_huong_dan);
        const lichHenArray = (res.data || []).filter(lich => lich.LoaiLich === 1);        
        lichHenData.value = lichHenArray.map(lich => ({
            id: lich.id,
            date: lich.ThoiGianGap ? new Date(lich.ThoiGianGap).toISOString().split('T')[0] : '',
            time: lich.ThoiGianGap ? new Date(lich.ThoiGianGap).toTimeString().slice(0, 5) : '',
            location: lich.DiaDiem || '',
            status: lich.TrangThai || ''
        }));
        gvhd.value = await axios.post("/teacher-by-magv/" + studenta.Giang_vien_huong_dan).then(res => res.data);
    } catch (e) {
        console.error(e);
    }
}

async function fetchLichHenPhanBien() {
    try {
        const studenta = await axios.get(`/students/${props.mssv}`).then(res => res.data)
        const res = await axios.get("/lich-hen/" + studenta.de_tai.MaGVPB);
        const lichHenArray = (res.data || []).filter(lich => lich.LoaiLich === 2);        
        lichHenData.value = lichHenArray.map(lich => ({
            id: lich.id,
            date: lich.ThoiGianGap ? new Date(lich.ThoiGianGap).toISOString().split('T')[0] : '',
            time: lich.ThoiGianGap ? new Date(lich.ThoiGianGap).toTimeString().slice(0, 5) : '',
            location: lich.DiaDiem || '',
            status: lich.TrangThai || ''
        }));
        gvpb.value = await axios.post("/teacher-by-magv/" + studenta.de_tai.MaGVPB).then(res => res.data)
        console.log("GV phản biện:", gvpb.value)
    } catch (e) {
        console.error(e);
    }
}

async function fetchTopics(user) {
  isLoading.value = true
  try {
    if (user.role === "SinhVien" && props.mssv) {
      const studentb = await axios.get(`/students/${props.mssv}`).then(res => res.data)
      studenta.value = studentb  // save for later use
      if (studenta) {
        const groupStr = studentb.Nhom
        const groupNumber = groupStr ? parseInt(groupStr.split('-')[1]) : 0
        const teacherName = await axios.post(`/teacher-by-magv/${studentb.Giang_vien_huong_dan}`)
        guideTeacher.value = teacherName.data   // save the full teacher object
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
  if (newView === 'reviewSchedule' && !review.value && props.mssv) {
    fetchLichHenPhanBien()
  }
  if (newView === 'meetingSchedule' && !review.value && props.mssv) {
    fetchLichHenHuongDan()
  }
  if (newView === 'reviewLecturer' && !review.value && props.mssv) {
    fetchReview()
  }
  if (newView === 'defenseCommittee' && !defense.value && props.mssv) {
    fetchDefenseCommittee()
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

async function fetchReview() {
  loadingReview.value = true
  try {
    // 1. Get the student's topic (contains reviewer teacher ID)
    const topic = await axios.get(`/topic-by-student/${props.mssv}`).then(r => r.data)
    const studentData = await axios.get(`/students/${props.mssv}`).then(r => r.data)
    console.log("Student data for review:", studentData, topic)
    // 2. Get supervisor name (same as before)
    const supervisorRes = await axios.post(`/teacher-by-magv/${studentData.Giang_vien_huong_dan}`)
    const supervisorName = supervisorRes.data.Ho_va_Ten || ""

    // 3. Get reviewer name (if assigned)
    let reviewerName = ""
    if (topic.MaGVPB) {
      const reviewerRes = await axios.post(`/teacher-by-magv/${topic.MaGVPB}`)
      reviewerName = reviewerRes.data.Ho_va_Ten || ""
    }

    // 4. Review date (adjust field name to your actual DB column)
    const reviewDate = topic.NgayPhanBien || "Chưa có lịch"

    review.value = {
      topic: topic.TenDeTai || "Chưa có đề tài",
      supervisor: supervisorName,
      reviewer: reviewerName,
      reviewDate: reviewDate,
    }
  } catch (error) {
    console.error("Lỗi tải dữ liệu phản biện:", error)
    review.value = null
  } finally {
    loadingReview.value = false
  }
}

async function fetchDefenseCommittee() {
  loadingDefense.value = true
  try {
    const topic = await axios.get(`/topic-by-student/${props.mssv}`).then(r => r.data)

    const defaultDefense = {
      topic: topic?.TenDeTai || 'Chưa có đề tài',
      committeeCode: 'Chưa có',
      room: 'Chưa xác định',
      date: '',
      day: '',
      time: '',
      status: 'Chưa phân công',
    }

    if (!topic || !topic.MaHD) {
      defense.value = defaultDefense
      committeeMembers.value = []
      return
    }

    const committeesRes = await axios.get('/committees')
    const committees = committeesRes.data

    const committee = committees.find(c => c.id === topic.MaHD)

    if (!committee) {
      defense.value = { ...defaultDefense, committeeCode: topic.MaHD }
      committeeMembers.value = []
      return
    }

    const startDate = new Date(committee.start)
    const endDate = new Date(committee.end)
    const now = new Date()

    let status = 'Sắp diễn ra'
    if (now > endDate) status = 'Đã diễn ra'
    else if (now >= startDate) status = 'Đang diễn ra'

    const days = ['Chủ Nhật','Thứ Hai','Thứ Ba','Thứ Tư','Thứ Năm','Thứ Sáu','Thứ Bảy']
    const dayName = days[startDate.getDay()]

    defense.value = {
      topic: topic.TenDeTai || 'Chưa có đề tài',
      committeeCode: committee.id,
      room: 'Chưa xác định',
      date: startDate.toLocaleDateString('vi-VN'),
      day: dayName,
      time: startDate.toLocaleTimeString('vi-VN', { hour:'2-digit', minute:'2-digit' }),
      status,
    }

    // ---- Build members with real emails (async) ----
    const memberPromises = committee.members.map(async (m) => {
      try {
        const res = await axios.post(`/teacher-by-magv/${m.teacherId}`)
        const email = res.data.email || ''
        return {
          position: m.position,
          name: m.teacherName,
          email: email,
          department: 'Công nghệ thông tin',   // adjust if you later fetch real department
          avatarClass: 'bg-gradient-to-r from-blue-500 to-indigo-600',
        }
      } catch (error) {
        console.error(`Lỗi lấy email cho GV ${m.teacherId}`, error)
        return {
          position: m.position,
          name: m.teacherName,
          email: '',
          department: 'Công nghệ thông tin',
          avatarClass: 'bg-gradient-to-r from-blue-500 to-indigo-600',
        }
      }
    })

    const membersWithData = await Promise.all(memberPromises)

    // Sort: chủ tịch → thư ký → ủy viên (others at the end)
    const positionOrder = {
      'chu_tich': 1,
      'thu_ky': 2,
      'uy_vien': 3,
    }

    const sortedMembers = membersWithData.sort((a, b) => {
      const orderA = positionOrder[a.position] ?? 99
      const orderB = positionOrder[b.position] ?? 99
      return orderA - orderB
    })

    committeeMembers.value = sortedMembers

  } catch (error) {
    console.error('Lỗi tải dữ liệu hội đồng:', error)
    defense.value = null
    committeeMembers.value = []
  } finally {
    loadingDefense.value = false
  }
}


//Real-time chat
const headerUser = computed(() => {
  const base = { ...props.user }

  if (studenta.value) {
    base.sinh_vien = {
      MSSV: studenta.value.MSSV,
      Ho_va_Ten: studenta.value.Ho_va_Ten,
      Giang_vien_huong_dan: studenta.value.Giang_vien_huong_dan,
      giang_vien_huong_dan: guideTeacher.value ? {
        MaGV: guideTeacher.value.MaGV,
        Ho_va_Ten: guideTeacher.value.Ho_va_Ten,
      } : null,
    }
  }

  return base
})

function setCurrentView(view) {
  currentView.value = view
}
</script>