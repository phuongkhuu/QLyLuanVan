<template>
    <aside class="w-64 bg-white border-r p-6 min-h-full">
        <nav class="flex flex-col space-y-4 text-indigo-700 font-medium">
            <button
                @click="setCurrentView('dashboard')"
                :class="
                    currentView === 'dashboard'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Trang chủ
            </button>

            <button
                @click="setCurrentView('students')"
                :class="
                    currentView === 'students'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Danh sách sinh viên
            </button>

            <button
                @click="setCurrentView('assignTopic')"
                :class="
                    currentView === 'assignTopic'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Phân công đề tài
            </button>

            <button
                @click="setCurrentView('evaluation50')"
                :class="
                    currentView === 'evaluation50'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Đánh giá 50%
            </button>

            <button
                @click="setCurrentView('reviewScore')"
                :class="
                    currentView === 'reviewScore'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Điểm phản biện
            </button>

            <button
                @click="setCurrentView('guideScore')"
                :class="
                    currentView === 'guideScore'
                        ? 'bg-indigo-100 text-indigo-900 rounded px-3 py-2'
                        : 'text-left hover:text-indigo-900'
                "
            >
                Điểm hướng dẫn
            </button>
        </nav>
    </aside>
</template>

<!-- <script setup>
import { ref } from "vue";
import { useRouter, useRoute } from "vue-router";
const currentView = ref("dashboard");

const students = ref([]);
const students1 = ref([]);
const studentsReviewer = ref([]);
const topics = ref([]);
const topicsReview = ref([]);
function setCurrentView(view) {
    currentView.value = view;
}
</script> -->
<script setup>
defineProps({
  currentView: String
})

const emit = defineEmits(['changeView'])

function setCurrentView(view) {
  emit('changeView', view)
}
</script>
