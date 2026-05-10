<template>
    <div
        class="relative w-full"
    >
        <!-- Search Icon -->
        <div
            class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"
        >
            🔍
        </div>

        <!-- Input -->
        <input
            type="text"
            placeholder="Tìm kiếm theo tên giảng viên hoặc tên đề tài..."
            class="w-full h-14 pl-14 pr-5 rounded-2xl border border-slate-200 bg-slate-50 text-slate-700 placeholder:text-slate-400 shadow-sm outline-none transition-all duration-200 focus:bg-white focus:border-blue-400 focus:ring-4 focus:ring-blue-100"
        />

        <!-- Right Badge -->
        <div
            class="absolute right-4 top-1/2 -translate-y-1/2 hidden md:flex items-center gap-2 px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs text-slate-500 shadow-sm"
        >
            ⌨️ Enter
        </div>
    </div>
</template>