<template>
    <div
        class="min-h-screen bg-gray-100 flex flex-col"
    >
        <!-- Header -->
        <Header :user="user" />

        <!-- Content -->
        <div class="flex flex-1">

            <!-- Sidebar -->
            <Sidebar
                :currentView="currentView"

                @changeView="setCurrentView"
            />

            <!-- Main -->
            <main class="flex-1">

                <!-- Dashboard -->
                <Maincontent
                    v-if="
                        currentView === 'dashboard'
                    "

                    :topics="topics"
                />

                <!-- lịch gặp giảng viên hướng dẫn -->
                <MeetingSchedule
                    v-if="
                        currentView === 'meetingSchedule'
                    "
                />
                <!-- Xem điểm 50% -->
                <Score50
                    v-if="
                        currentView === 'score50'
                    "
                />

                <ReviewLecturer
                    v-if="
                        currentView === 'reviewLecturer'
                    "
                />
              <!-- Lịch gặp giáo viên phản biện -->
                <ReviewSchedule
                    v-if="
                        currentView === 'reviewSchedule'
                    "
                />
              <!-- gặp hội đồng -->
                <DefenseCommittee
                    v-if="
                        currentView === 'defenseCommittee'
                    "
                />
                <test
                    v-if="
                        currentView === 'test'
                    "
                />

            </main>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue"

import Header from "./components/Header.vue"

import Sidebar from "./components/Sidebar.vue"

import Maincontent from "./components/Maincontent.vue"

import MeetingSchedule
from "./pages/MeetingSchedule.vue"
import Score50 from "./pages/Score50.vue"
import ReviewLecturer from "./pages/ReviewLecturer.vue"
import DefenseCommittee from "./pages/DefenseCommittee.vue"
import ReviewSchedule from "./pages/ReviewSchedule.vue"

import test from "./pages/test.vue"

defineProps({
    user: Object,
})

const currentView =
    ref("dashboard")

function setCurrentView(view) {
    currentView.value = view
}

const topics = [
    {
        code: "",
        title: "",
        lecturer: "",
        limit: 0,
        status: "",
    },
]
</script>