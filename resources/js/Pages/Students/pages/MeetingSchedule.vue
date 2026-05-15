<template>
    <div
        class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8"
    >
        <!-- Header -->
        <div
            class="flex items-center justify-between mb-8"
        >
            <div>
                <h1
                    class="text-3xl font-bold text-slate-800"
                >
                    Lịch gặp giảng viên hướng dẫn
                </h1>

                <p
                    class="text-slate-500 mt-2"
                >
                    Theo dõi lịch gặp và lịch hướng dẫn luận văn
                </p>
            </div>
        </div>

        <!-- Lecturer Info -->
        <LecturerInfo 
            :lecturer="gvhd"
        />

        <!-- Schedule Table -->
        <ScheduleTable 
            :schedules="lichHenData"
        />
    </div>
</template>
<script setup lang="ts">
import LecturerInfo from '../components/Meeting/LecturerInfo.vue';
import ScheduleTable from '../components/Meeting/ScheduleTable.vue';
defineProps({
    lichHenData: {
        type: Array,
        default: () => ([]),
    },
    gvhd: {
        type: Object,
        default: () => ({}),
    },
});
</script>
