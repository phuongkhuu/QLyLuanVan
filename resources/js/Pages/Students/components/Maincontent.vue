<template>
    <main
        class="flex-1 min-h-screen bg-gradient-to-br from-slate-50 via-white to-blue-50 p-8 overflow-auto relative"
    >
        <!-- Top Header -->
        <div
            class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6 mb-8"
        >
            <!-- Left -->
            <div>
                <h1
                    class="text-3xl font-bold text-slate-800 tracking-tight"
                >
                    Bảng phân công
                </h1>

                <p
                    class="text-slate-500 mt-2"
                >
                    Theo dõi và quản lý đề tài luận văn tốt nghiệp
                </p>
            </div>

            
        </div>

        <!-- Table -->
        <div
            class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden"
        >

            <!-- Table -->
            <div class="p-5">
                <Table :topics="topics" />
            </div>
        </div>
    </main>
</template>

<script setup>
import Table from "./Table.vue"

defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
})
</script>