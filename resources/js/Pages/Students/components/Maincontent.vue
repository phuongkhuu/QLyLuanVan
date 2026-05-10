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

        <!-- Search -->
        <div class="mb-6">

            <div
                class="bg-white border border-slate-200 rounded-3xl shadow-sm p-5"
            >
                <div
                    class="flex items-center justify-between mb-4"
                >
                    <div>
                        <h2
                            class="font-semibold text-slate-800 text-lg"
                        >
                            Tìm kiếm đề tài
                        </h2>

                        <p
                            class="text-sm text-slate-500 mt-1"
                        >
                            Tra cứu thông tin giảng viên và luận văn
                        </p>
                    </div>

                    <div
                        class="hidden md:flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-100 text-slate-600 text-sm"
                    >
                        🔍 Search
                    </div>
                </div>

                <Searchbar />
            </div>
        </div>

        <!-- Table -->
        <div
            class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden"
        >
            <!-- Header -->
            <div
                class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 px-6 py-5 border-b border-slate-200 bg-slate-50"
            >
                <div>
                    <h2
                        class="font-bold text-slate-800 text-lg"
                    >
                        Danh sách đề tài
                    </h2>

                    <p
                        class="text-sm text-slate-500 mt-1"
                    >
                        Quản lý trạng thái và thông tin đề tài
                    </p>
                </div>

                <!-- Status -->
                <div
                    class="flex items-center gap-3"
                >
                    <div
                        class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 text-sm font-semibold"
                    >
                        {{ topics?.length || 0 }} đề tài
                    </div>

                    <div
                        class="px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-sm font-semibold"
                    >
                        Hoạt động
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="p-5">
                <Table :topics="topics" />
            </div>
        </div>
    </main>
</template>

<script setup>
import Searchbar from "./Searchbar.vue"
import Table from "./Table.vue"

defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
})
</script>