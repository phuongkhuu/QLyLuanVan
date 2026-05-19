<template>
    <div class="min-h-screen bg-gray-50 font-vietnamese text-gray-800">
        <!-- Header -->
        <header
            class="flex justify-between items-center bg-gradient-to-r from-indigo-400 to-indigo-600 text-white px-6 py-4 shadow-md"
        >
            <div>
                <h1 class="text-xl font-bold tracking-wide">
                    HỆ THỐNG QUẢN LÝ LUẬN VĂN TỐT NGHIỆP
                </h1>
                <p class="text-sm mt-1">KHOA CÔNG NGHỆ THÔNG TIN</p>
            </div>

            <div class="flex items-center gap-6">
                <div class="relative">
                    <button
                        @click="toggleNotifications"
                        class="relative p-2 text-white hover:bg-indigo-500 rounded-full transition"
                    >
                        <svg
                            class="w-6 h-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"
                            ></path>
                        </svg>
                        <span
                            v-if="$page.props.auth.notifications.length > 0"
                            class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full transform translate-x-1/4 -translate-y-1/4"
                        >
                            {{ $page.props.auth.notifications.length }}
                        </span>
                    </button>

                    <div
                        v-if="showNotifications"
                        class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-xl z-50 overflow-hidden border border-gray-100"
                    >
                        <div
                            class="bg-gray-50 px-4 py-3 border-b text-gray-700 font-semibold flex justify-between items-center"
                        >
                            <span>Thông báo</span>
                            <span
                                class="text-xs bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full"
                                >{{
                                    $page.props.auth.notifications.length
                                }}
                                mới</span
                            >
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <div
                                v-if="
                                    $page.props.auth.notifications.length === 0
                                "
                                class="p-4 text-center text-sm text-gray-500"
                            >
                                Không có thông báo mới.
                            </div>
                            <div
                                v-for="notification in $page.props.auth
                                    .notifications"
                                :key="notification.id"
                                class="p-4 border-b hover:bg-gray-50 cursor-pointer flex flex-col gap-1"
                                @click="markAsRead(notification.id)"
                            >
                                <div class="flex justify-between items-start">
                                    <span
                                        class="font-semibold text-sm text-gray-800"
                                        >{{ notification.data.title }}</span
                                    >
                                    <span class="text-[10px] text-gray-400">{{
                                        new Date(
                                            notification.created_at,
                                        ).toLocaleDateString("vi-VN")
                                    }}</span>
                                </div>
                                <p class="text-xs text-gray-600 leading-snug">
                                    {{ notification.data.message }}
                                </p>
                                <div class="text-right mt-1">
                                    <span
                                        class="text-[10px] text-indigo-500 hover:text-indigo-700"
                                        >Đánh dấu đã đọc</span
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div
                        @click="toggleMenu"
                        class="flex items-center space-x-3 cursor-pointer"
                    >
                        <div
                            class="w-10 h-10 bg-white text-indigo-600 font-bold rounded-full flex items-center justify-center"
                        >
                            {{
                                user.name
                                    ? user.name.charAt(0).toUpperCase()
                                    : "?"
                            }}
                        </div>
                        <div class="text-right hidden md:block">
                            <div class="font-semibold text-sm text-white">
                                {{ user.name }}
                            </div>
                            <div class="text-xs opacity-80">
                                {{ user.role || "Admin" }}
                            </div>
                        </div>
                    </div>
                    <div
                        v-if="showMenu"
                        class="absolute right-0 top-full mt-2 w-40 bg-white text-black rounded shadow z-50"
                    >
                        <button
                            @click="goProfile"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm"
                        >
                            Trang cá nhân
                        </button>
                        <button
                            @click="logout"
                            class="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600 text-sm"
                        >
                            Đăng xuất
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- BODY -->
        <div class="flex h-[calc(100vh-4rem)]">
            <!-- ========================================================= -->
            <!-- SIDEBAR -->
            <!-- ========================================================= -->
            <aside
                :class="[
                    collapsed ? 'w-24' : 'w-72',
                    'bg-white border-r border-slate-200 h-screen sticky top-0 transition-all duration-300 flex flex-col shadow-sm',
                ]"
            >
                <!-- ========================================================= -->
                <!-- TOP -->
                <!-- ========================================================= -->
                <div
                    class="h-20 border-b border-slate-200 flex items-center justify-between px-5 shrink-0"
                >
                    <!-- LOGO -->
                   
                    <!-- TOGGLE -->
                    <button
                        @click="collapsed = !collapsed"
                        class="w-11 h-11 rounded-2xl bg-slate-100 hover:bg-indigo-100 text-indigo-600 flex items-center justify-center transition-all shadow-sm"
                    >
                        ☰
                    </button>
                </div>

                <!-- ========================================================= -->
                <!-- MENU -->
                <!-- ========================================================= -->
                <nav class="flex-1 overflow-y-auto p-4 space-y-2">
                    <!-- ITEM -->
                    <button
                        v-for="item in menus"
                        :key="item.view"
                        @click="setCurrentView(item.view)"
                        :class="[
                            'w-full flex items-center rounded-2xl transition-all duration-200 group',

                            collapsed
                                ? 'justify-center px-0 py-4'
                                : 'gap-4 px-4 py-4',

                            currentView === item.view
                                ? 'bg-gradient-to-r from-indigo-500 to-indigo-600 text-white shadow-lg'
                                : 'text-slate-600 hover:bg-slate-100',
                        ]"
                    >
                        <!-- ICON -->
                        <div
                            :class="[
                                'w-11 h-11 rounded-xl flex items-center justify-center text-lg transition-all shrink-0',

                                currentView === item.view
                                    ? 'bg-white/20 text-white'
                                    : 'bg-white border border-slate-200 text-indigo-600 group-hover:bg-indigo-50',
                            ]"
                        >
                            {{ item.icon }}
                        </div>

                        <!-- LABEL -->
                        <transition name="fade">
                            <div
                                v-if="!collapsed"
                                class="flex flex-col items-start min-w-0"
                            >
                                <span class="font-semibold text-sm truncate">
                                    {{ item.label }}
                                </span>

                                <span
                                    class="text-xs opacity-70"
                                    v-if="currentView === item.view"
                                >
                                    Đang hoạt động
                                </span>
                            </div>
                        </transition>
                    </button>
                </nav>
            </aside>

            <!-- Main content -->
            <main class="flex-1 p-8 overflow-auto">
                <!-- HOME -->
                <div v-if="currentView === 'home'">
                    <h2
                        class="text-2xl font-bold text-indigo-600 mb-6 text-center"
                    >
                        TRANG CHỦ - THỐNG KÊ TỔNG QUAN
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div
                                    class="p-3 rounded-full bg-blue-100 text-blue-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Tổng số sinh viên
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ totalStudents }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div
                                    class="p-3 rounded-full bg-green-100 text-green-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Tổng số giảng viên
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ totalTeachers }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-lg shadow-md">
                            <div class="flex items-center">
                                <div
                                    class="p-3 rounded-full bg-purple-100 text-purple-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p
                                        class="text-sm font-medium text-gray-600"
                                    >
                                        Tổng số đề tài
                                    </p>
                                    <p
                                        class="text-2xl font-semibold text-gray-900"
                                    >
                                        {{ totalTopics }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Tình trạng sinh viên
                            </h3>

                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex items-center gap-2 text-green-600"
                                >
                                    <span
                                        class="w-2 h-2 bg-green-500 rounded-full"
                                    ></span>
                                    {{ svDaCoGV }} Sinh viên đã được phân công
                                    giảng viên hướng dẫn
                                </div>

                                <div
                                    class="flex items-center gap-2 text-red-600"
                                >
                                    <span
                                        class="w-2 h-2 bg-red-500 rounded-full"
                                    ></span>
                                    {{ svChuaCoGV }} Sinh viên chưa được phân
                                    công giảng viên hướng dẫn
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-md p-6">
                            <h3
                                class="text-lg font-semibold text-gray-900 mb-4"
                            >
                                Tình trạng đề tài
                            </h3>

                            <div class="space-y-3 text-sm">
                                <div
                                    class="flex items-center gap-2 text-green-600"
                                >
                                    <span
                                        class="w-2 h-2 bg-green-500 rounded-full"
                                    ></span>
                                    {{ dtTiepTuc }} Đề tài được tiếp tục
                                </div>

                                <div
                                    class="flex items-center gap-2 text-red-600"
                                >
                                    <span
                                        class="w-2 h-2 bg-red-500 rounded-full"
                                    ></span>
                                    {{ dtDinhChi }} Đề tài bị đình chỉ
                                </div>

                                <div
                                    class="flex items-center gap-2 text-yellow-600"
                                >
                                    <span
                                        class="w-2 h-2 bg-yellow-500 rounded-full"
                                    ></span>
                                    {{ dtXinHoan }} Đề tài xin hoãn
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUẢN LÝ GIẢNG VIÊN -->
                <div v-if="currentView === 'teachers'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-4xl font-extrabold text-slate-800 tracking-tight"
                            >
                                QUẢN LÝ GIẢNG VIÊN
                            </h2>

                            <p class="text-slate-500 mt-2 text-sm">
                                Quản lý thông tin giảng viên trong hệ thống
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <input
                                    v-model="teacherSearch"
                                    type="text"
                                    placeholder="Tìm kiếm giảng viên..."
                                    class="w-80 h-12 rounded-2xl border border-slate-200 bg-white px-5 pr-12 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- Icon -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- Add -->
                            <button
                                @click="openAddForm"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold shadow-lg transition-all"
                            >
                                + Thêm mới
                            </button>
                        </div>
                    </div>

                    <!-- TABLE CARD -->
                    <div
                        class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <!-- TABLE -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-xs tracking-wide"
                                >
                                    <tr>
                                        <th class="px-6 py-5 text-center">
                                            Mã GV
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Họ và tên
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Email
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Số điện thoại
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Sinh viên HD
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(teacher, i) in filteredTeachers"
                                        :key="teacher.id"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- Mã GV -->
                                        <td
                                            class="px-6 py-5 text-center font-semibold text-slate-700"
                                        >
                                            {{ teacher.MaGV }}
                                        </td>

                                        <!-- Họ tên -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold flex items-center justify-center shadow-md"
                                                >
                                                    {{
                                                        teacher.name
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- Info -->
                                                <div>
                                                    <h3
                                                        class="font-semibold text-slate-800"
                                                    >
                                                        {{ teacher.name }}
                                                    </h3>

                                                    <p
                                                        class="text-xs text-slate-400 mt-1"
                                                    >
                                                        Giảng viên
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Email -->
                                        <td class="px-6 py-5 text-slate-600">
                                            {{ teacher.email }}
                                        </td>

                                        <!-- Phone -->
                                        <td
                                            class="px-6 py-5 text-center text-slate-600"
                                        >
                                            {{ teacher.sdt }}
                                        </td>

                                        <!-- Student Count -->
                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[45px] h-10 px-3 rounded-2xl bg-indigo-100 text-indigo-600 font-bold"
                                            >
                                                {{ teacher.So_luong_sinh_vien }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center justify-center gap-3"
                                            >
                                                <!-- Edit -->
                                                <button
                                                    @click="
                                                        openEditForm(teacher)
                                                    "
                                                    class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Sửa
                                                </button>

                                                <!-- Delete -->
                                                <button
                                                    @click="
                                                        deleteItem(teacher.MaGV)
                                                    "
                                                    class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr v-if="filteredTeachers.length === 0">
                                        <td
                                            colspan="6"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có dữ liệu từ database
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-8 py-5 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-sm text-slate-500">
                                Tổng:
                                {{ filteredTeachers.length }}
                                giảng viên
                            </p>

                            <div class="flex items-center gap-2">
                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-semibold"
                                >
                                    1
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUẢN LÝ SINH VIÊN -->
                <!-- QUẢN LÝ SINH VIÊN -->
                <div v-if="currentView === 'students'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-4xl font-extrabold text-slate-800 tracking-tight"
                            >
                                QUẢN LÝ SINH VIÊN
                            </h2>

                            <p class="text-slate-500 mt-2 text-sm">
                                Quản lý danh sách sinh viên trong hệ thống
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <input
                                    v-model="studentSearch"
                                    @input="onStudentSearchInput"
                                    type="text"
                                    placeholder="Tìm kiếm sinh viên..."
                                    class="w-80 h-12 rounded-2xl border border-slate-200 bg-white px-5 pr-12 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- Icon -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- Add -->
                            <button
                                @click="openAddForm"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold shadow-lg transition-all"
                            >
                                + Thêm mới
                            </button>
                        </div>
                    </div>

                    <!-- TABLE CARD -->
                    <div
                        class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <!-- TABLE -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-xs tracking-wide"
                                >
                                    <tr>
                                        <th class="px-6 py-5 text-center">
                                            MSSV
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Họ và tên
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Nhóm
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Email
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Số điện thoại
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(s, idx) in filteredStudents"
                                        :key="s.mssv || s.id || idx"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- MSSV -->
                                        <td
                                            class="px-6 py-5 text-center font-semibold text-slate-700"
                                        >
                                            {{ s.mssv }}
                                        </td>

                                        <!-- NAME -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold flex items-center justify-center shadow-md"
                                                >
                                                    {{
                                                        s.name
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- Info -->
                                                <div>
                                                    <h3
                                                        class="font-semibold text-slate-800"
                                                    >
                                                        {{ s.name }}
                                                    </h3>

                                                    <p
                                                        class="text-xs text-slate-400 mt-1"
                                                    >
                                                        Sinh viên
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- GROUP -->
                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[45px] h-10 px-3 rounded-2xl bg-indigo-100 text-indigo-600 font-bold"
                                            >
                                                {{ s.group || "-" }}
                                            </span>
                                        </td>

                                        <!-- EMAIL -->
                                        <td class="px-6 py-5 text-slate-600">
                                            {{ s.email || "-" }}
                                        </td>

                                        <!-- PHONE -->
                                        <td
                                            class="px-6 py-5 text-center text-slate-600"
                                        >
                                            {{ s.phone || "-" }}
                                        </td>

                                        <!-- ACTION -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center justify-center gap-3"
                                            >
                                                <!-- Edit -->
                                                <button
                                                    @click="openEditForm(s)"
                                                    class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Sửa
                                                </button>

                                                <!-- Delete -->
                                                <button
                                                    @click="deleteItem(s.mssv)"
                                                    class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr v-if="filteredStudents.length === 0">
                                        <td
                                            colspan="6"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có dữ liệu từ database
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-8 py-5 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-sm text-slate-500">
                                Tổng:
                                {{ filteredStudents.length }}
                                sinh viên
                            </p>

                            <!-- Pagination -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-semibold"
                                >
                                    1
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUẢN LÝ ĐỀ TÀI -->
                <!-- QUẢN LÝ ĐỀ TÀI -->
                <div v-if="currentView === 'topics'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-4xl font-extrabold text-slate-800 tracking-tight"
                            >
                                QUẢN LÝ ĐỀ TÀI
                            </h2>

                            <p class="text-slate-500 mt-2 text-sm">
                                Quản lý danh sách đề tài luận văn tốt nghiệp
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- Search -->
                            <div class="relative">
                                <input
                                    v-model="topicSearch"
                                    type="text"
                                    placeholder="Tìm kiếm đề tài..."
                                    class="w-80 h-12 rounded-2xl border border-slate-200 bg-white px-5 pr-12 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- Icon -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- Add -->
                            <button
                                @click="openAddForm"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold shadow-lg transition-all"
                            >
                                + Thêm mới
                            </button>
                        </div>
                    </div>

                    <!-- TABLE CARD -->
                    <div
                        class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <!-- TABLE -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-xs tracking-wide"
                                >
                                    <tr>
                                        <th class="px-6 py-5 text-center">
                                            Mã đề tài
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Tên đề tài
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Giảng viên
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Trạng thái
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(topic, i) in filteredTopics"
                                        :key="topic.MaDT"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- CODE -->
                                        <td
                                            class="px-6 py-5 text-center font-semibold text-slate-700"
                                        >
                                            {{ topic.MaDT }}
                                        </td>

                                        <!-- TOPIC -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <!-- Icon -->
                                                <div
                                                    class="w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-md text-xl"
                                                >
                                                    📚
                                                </div>

                                                <!-- Info -->
                                                <div>
                                                    <h3
                                                        class="font-semibold text-slate-800 leading-relaxed"
                                                    >
                                                        {{ topic.TenDeTai }}
                                                    </h3>

                                                    <p
                                                        class="text-xs text-slate-400 mt-1"
                                                    >
                                                        Đề tài luận văn
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- TEACHER -->
                                        <td class="px-6 py-5 text-slate-600">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center"
                                                >
                                                    {{
                                                        topic.GiangVien?.charAt(
                                                            0,
                                                        )?.toUpperCase()
                                                    }}
                                                </div>

                                                <span class="font-medium">
                                                    {{ topic.GiangVien }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- STATUS -->
                                        <td class="px-6 py-5 text-center">
                                            <!-- WAIT -->
                                            <span
                                                v-if="
                                                    topic.TrangThai ===
                                                    'Chờ sinh viên chọn'
                                                "
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 text-xs font-bold"
                                            >
                                                🟢 Chờ SV chọn
                                            </span>

                                            <!-- SELECTED -->
                                            <span
                                                v-else-if="
                                                    topic.TrangThai ===
                                                    'Đã được chọn'
                                                "
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-100 text-amber-700 text-xs font-bold"
                                            >
                                                🟡 Đã được chọn
                                            </span>

                                            <!-- LOCK -->
                                            <span
                                                v-else
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-100 text-rose-700 text-xs font-bold"
                                            >
                                                🔴 Đã khóa
                                            </span>
                                        </td>

                                        <!-- ACTION -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center justify-center gap-3"
                                            >
                                                <!-- Edit -->
                                                <button
                                                    @click="openEditForm(topic)"
                                                    class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Sửa
                                                </button>

                                                <!-- Delete -->
                                                <button
                                                    @click="
                                                        deleteItem(topic.MaDT)
                                                    "
                                                    class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-semibold shadow transition-all"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr v-if="filteredTopics.length === 0">
                                        <td
                                            colspan="5"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có dữ liệu từ database
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-8 py-5 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-sm text-slate-500">
                                Tổng:
                                {{ filteredTopics.length }}
                                đề tài
                            </p>

                            <!-- Pagination -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-semibold"
                                >
                                    1
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- FORM MODAL -->
                <div
                    v-if="showForm"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="w-full max-w-4xl max-h-[95vh] overflow-hidden rounded-[32px] bg-white shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-3xl font-bold tracking-tight">
                                    {{
                                        formMode === "add"
                                            ? "THÊM MỚI"
                                            : "CHỈNH SỬA"
                                    }}
                                    {{ getFormTitle() }}
                                </h3>

                                <p class="text-indigo-100 text-sm mt-2">
                                    Nhập đầy đủ thông tin dữ liệu
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="closeForm"
                                class="w-12 h-12 rounded-2xl bg-white/15 hover:bg-white/20 transition flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </button>
                        </div>

                        <!-- BODY -->
                        <div
                            class="flex-1 overflow-y-auto px-8 py-8 bg-slate-50"
                        >
                            <!-- ASSIGNMENT -->
                            <div
                                v-if="currentView === 'assignments'"
                                class="space-y-8"
                            >
                                <!-- SECTION -->
                                <div
                                    class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6"
                                >
                                    <h4
                                        class="text-xl font-bold text-slate-800 mb-6"
                                    >
                                        Thông tin sinh viên
                                    </h4>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- MSSV -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                MSSV
                                            </label>

                                            <input
                                                v-model="formData.mssv"
                                                type="text"
                                                placeholder="Nhập MSSV"
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            />
                                        </div>

                                        <!-- NAME -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                Họ và tên
                                            </label>

                                            <input
                                                v-model="formData.name"
                                                type="text"
                                                placeholder="Nhập họ và tên"
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            />
                                        </div>

                                        <!-- GROUP -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                Nhóm
                                            </label>

                                            <input
                                                v-model="formData.group"
                                                type="text"
                                                placeholder="Nhập nhóm"
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            />
                                        </div>

                                        <!-- TOPIC -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                Đề tài
                                            </label>

                                            <input
                                                v-model="formData.topic"
                                                type="text"
                                                placeholder="Nhập đề tài"
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            />
                                        </div>

                                        <!-- TEACHER -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                Giảng viên HD
                                            </label>

                                            <input
                                                v-model="formData.lecturer"
                                                type="text"
                                                placeholder="Nhập giảng viên"
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            />
                                        </div>

                                        <!-- STATUS -->
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-600 mb-2"
                                            >
                                                Trạng thái
                                            </label>

                                            <select
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            >
                                                <option value="Chưa gặp">
                                                    Chưa gặp
                                                </option>

                                                <option value="Đã gặp">
                                                    Đã gặp
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                    <!-- NOTE -->
                                    <div class="mt-6">
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Ghi chú
                                        </label>

                                        <textarea
                                            rows="4"
                                            placeholder="Nhập ghi chú..."
                                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-4 outline-none resize-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- TEACHER -->
                            <div
                                v-if="currentView === 'teachers'"
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6"
                            >
                                <h4
                                    class="text-xl font-bold text-slate-800 mb-6"
                                >
                                    Thông tin giảng viên
                                </h4>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- NAME -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Họ và tên
                                        </label>

                                        <input
                                            v-model="formData.teacher.name"
                                            type="text"
                                            placeholder="Nhập họ và tên"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- EMAIL -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Email
                                        </label>

                                        <input
                                            v-model="formData.teacher.email"
                                            type="email"
                                            placeholder="Nhập email"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- PHONE -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Số điện thoại
                                        </label>

                                        <input
                                            v-model="formData.teacher.sdt"
                                            type="tel"
                                            placeholder="Nhập số điện thoại"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- STUDENT -->
                            <div
                                v-if="currentView === 'students'"
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6"
                            >
                                <h4
                                    class="text-xl font-bold text-slate-800 mb-6"
                                >
                                    Thông tin sinh viên
                                </h4>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- MSSV -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            MSSV
                                        </label>

                                        <input
                                            :disabled="formMode !== 'add'"
                                            v-model="formData.student.MSSV"
                                            type="text"
                                            placeholder="Nhập MSSV"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 disabled:bg-slate-100 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- NAME -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Họ và tên
                                        </label>

                                        <input
                                            v-model="formData.student.Ho_va_Ten"
                                            type="text"
                                            placeholder="Nhập họ và tên"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- GROUP -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Nhóm
                                        </label>

                                        <input
                                            v-model="formData.student.Nhom"
                                            type="text"
                                            placeholder="Nhập nhóm"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- EMAIL -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Email
                                        </label>

                                        <input
                                            v-model="formData.student.email"
                                            type="email"
                                            placeholder="Nhập email"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- PHONE -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Số điện thoại
                                        </label>

                                        <input
                                            v-model="formData.student.sdt"
                                            type="tel"
                                            placeholder="Nhập số điện thoại"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>
                                </div>
                            </div>

                            <!-- TOPIC -->
                            <div
                                v-if="currentView === 'topics'"
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6"
                            >
                                <h4
                                    class="text-xl font-bold text-slate-800 mb-6"
                                >
                                    Thông tin đề tài
                                </h4>

                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- CODE -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Mã đề tài
                                        </label>

                                        <input
                                            :disabled="formMode !== 'add'"
                                            v-model="formData.topic.MaDT"
                                            type="text"
                                            placeholder="Nhập mã đề tài"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 disabled:bg-slate-100 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- NAME -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Tên đề tài
                                        </label>

                                        <input
                                            v-model="formData.topic.TenDeTai"
                                            type="text"
                                            placeholder="Nhập tên đề tài"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- TEACHER -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-600 mb-2"
                                        >
                                            Giảng viên
                                        </label>

                                        <select
                                            v-model="formData.topic.MaGV"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        >
                                            <option value="">
                                                -- Chọn giảng viên --
                                            </option>

                                            <option
                                                v-for="teacher in teachers"
                                                :key="teacher.MaGV"
                                                :value="teacher.MaGV"
                                            >
                                                {{ teacher.name }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-end gap-4 px-8 py-6 border-t border-slate-200 bg-white"
                        >
                            <!-- Cancel -->
                            <button
                                @click="closeForm"
                                class="h-12 px-6 rounded-2xl border border-slate-300 hover:bg-slate-100 text-slate-700 font-semibold transition"
                            >
                                Hủy
                            </button>

                            <!-- Submit -->
                            <button
                                @click="
                                    formMode === 'add'
                                        ? addItem()
                                        : updateItem()
                                "
                                class="h-12 px-8 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold shadow-lg transition-all"
                            >
                                {{
                                    formMode === "add" ? "Thêm mới" : "Cập nhật"
                                }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- BẢNG PHÂN CÔNG LUẬN VĂN -->
                <div v-if="currentView === 'assignments'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-6"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-xl font-extrabold text-slate-800 tracking-tight"
                            >
                                BẢNG PHÂN CÔNG LUẬN VĂN
                            </h2>

                            <p class="text-slate-500 mt-1 text-sm">
                                Quản lý danh sách phân công luận văn tốt nghiệp
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- SEARCH -->
                            <div class="relative">
                                <input
                                    v-model="assignmentSearch"
                                    type="text"
                                    placeholder="Tìm MSSV / tên / đề tài..."
                                    class="w-72 h-11 rounded-2xl border border-slate-200 bg-white px-4 pr-11 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- ICON -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- IMPORT -->
                            <button
                                @click="$refs.excelInput.click()"
                                class="h-11 px-5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                📥 Import
                            </button>

                            <!-- INPUT -->
                            <input
                                ref="excelInput"
                                type="file"
                                accept=".xlsx,.xls"
                                class="hidden"
                                @change="handleExcelImport"
                            />

                            <!-- EXPORT -->
                            <button
                                @click="exportExcel"
                                class="h-11 px-5 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                📤 Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div
                        class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden max-w-full"
                    >
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-[13px] border-collapse">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                >
                                    <tr>
                                        <!-- MSSV -->
                                        <th
                                            class="px-2 py-4 text-center w-[90px]"
                                        >
                                            MSSV
                                        </th>

                                        <!-- NAME -->
                                        <th
                                            class="px-2 py-4 text-left w-[170px]"
                                        >
                                            Họ và tên SV
                                        </th>

                                        <!-- GROUP -->
                                        <th
                                            class="px-2 py-4 text-center w-[60px]"
                                        >
                                            Nhóm
                                        </th>

                                        <!-- TOPIC -->
                                        <th
                                            class="px-2 py-4 text-left w-[180px]"
                                        >
                                            Đề tài LVTN
                                        </th>

                                        <!-- LECTURER -->
                                        <th
                                            class="px-2 py-4 text-left w-[150px] cursor-pointer hover:bg-indigo-200 transition select-none group"
                                            @click="handleSort('lecturer')"
                                        >
                                            <div
                                                class="flex items-center gap-1"
                                            >
                                                <span> GVHD </span>

                                                <!-- SORT -->
                                                <span
                                                    v-if="
                                                        sortColumn ===
                                                        'lecturer'
                                                    "
                                                    class="text-[10px]"
                                                >
                                                    {{
                                                        sortDirection === "asc"
                                                            ? "▲"
                                                            : "▼"
                                                    }}
                                                </span>

                                                <span
                                                    v-else
                                                    class="text-[10px] text-indigo-300 opacity-0 group-hover:opacity-100 transition-opacity"
                                                >
                                                    ↕
                                                </span>
                                            </div>
                                        </th>

                                        <!-- NOTE -->
                                        <th
                                            class="px-2 py-4 text-center w-[120px]"
                                        >
                                            Ghi chú
                                        </th>

                                        <!-- ACTION -->
                                        <th
                                            class="px-2 py-4 text-center w-[170px]"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(item, i) in filteredAssignments"
                                        :key="i"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- MSSV -->
                                        <td
                                            class="px-2 py-4 text-center font-semibold text-slate-700"
                                        >
                                            {{ item.mssv }}
                                        </td>

                                        <!-- NAME -->
                                        <td class="px-2 py-4">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow shrink-0"
                                                >
                                                    {{
                                                        item.name
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- Info -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate"
                                                    >
                                                        {{ item.name }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 mt-0.5 truncate"
                                                    >
                                                        Sinh viên
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- GROUP -->
                                        <td class="px-2 py-4 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[34px] h-8 px-2 rounded-xl bg-indigo-100 text-indigo-600 text-xs font-bold"
                                            >
                                                {{ item.group }}
                                            </span>
                                        </td>

                                        <!-- TOPIC -->
                                        <td class="px-2 py-4">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <!-- Icon -->
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-sm shadow shrink-0"
                                                >
                                                    📚
                                                </div>

                                                <!-- Info -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate max-w-[140px]"
                                                    >
                                                        {{ item.topic }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 truncate"
                                                    >
                                                        Đề tài luận văn
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- LECTURER -->
                                        <td class="px-2 py-4">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 font-bold text-xs flex items-center justify-center shrink-0"
                                                >
                                                    {{
                                                        item.lecturer
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <span
                                                    class="font-medium text-slate-700 truncate max-w-[100px]"
                                                >
                                                    {{ item.lecturer }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- NOTE -->
                                        <td class="px-2 py-4">
                                            <div
                                                class="px-2 py-1.5 rounded-xl bg-slate-100 text-slate-500 italic text-[11px] truncate"
                                            >
                                                {{ item.note || "Không có" }}
                                            </div>
                                        </td>

                                        <!-- ACTION -->
                                        <td class="px-2 py-4">
                                            <div
                                                class="flex items-center justify-center gap-1"
                                            >
                                                <!-- ASSIGN -->
                                                <button
                                                    @click="
                                                        openAssignPanel(item)
                                                    "
                                                    class="px-2.5 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-[10px] font-semibold shadow transition-all"
                                                >
                                                    Phân công
                                                </button>

                                                <!-- DELETE -->
                                                <button
                                                    class="px-2.5 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-[10px] font-semibold shadow transition-all"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr v-if="filteredAssignments.length === 0">
                                        <td
                                            colspan="7"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có dữ liệu từ database
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-xs text-slate-500">
                                Tổng:
                                {{ filteredAssignments.length }}
                                phân công luận văn
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                                >
                                    1
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL: DANH SÁCH GIẢNG VIÊN -->
                <div
                    v-if="showAssignModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Danh sách giảng viên
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    Chọn giảng viên để phân công sinh viên
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="closeAssignPanel"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- SEARCH -->
                            <div class="flex justify-end mb-5">
                                <div class="relative">
                                    <input
                                        v-model="teacherSearch"
                                        type="text"
                                        placeholder="Tìm giảng viên..."
                                        class="w-72 h-11 rounded-2xl border border-slate-200 bg-white px-4 pr-11 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                    />

                                    <!-- ICON -->
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"
                                    >
                                        🔍
                                    </div>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 overflow-hidden"
                            >
                                <table
                                    class="w-full text-[13px] border-collapse"
                                >
                                    <!-- HEAD -->
                                    <thead
                                        class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                    >
                                        <tr>
                                            <th
                                                class="px-3 py-4 text-center w-[70px]"
                                            >
                                                STT
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[120px]"
                                            >
                                                MSGV
                                            </th>

                                            <th class="px-3 py-4 text-left">
                                                Họ và tên giảng viên
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[160px]"
                                            >
                                                Số lượng SV
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[120px]"
                                            >
                                                Thao tác
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- BODY -->
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="(gv, idx) in teachers"
                                            :key="gv.MaGV || gv.id || idx"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- STT -->
                                            <td class="px-3 py-4 text-center">
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center mx-auto"
                                                >
                                                    {{ idx + 1 }}
                                                </div>
                                            </td>

                                            <!-- MSGV -->
                                            <td
                                                class="px-3 py-4 text-center font-semibold text-slate-700"
                                            >
                                                {{ gv.MaGV ?? gv.id ?? "-" }}
                                            </td>

                                            <!-- TEACHER -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <!-- Avatar -->
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow shrink-0"
                                                    >
                                                        {{
                                                            (
                                                                gv.name ??
                                                                gv.HoTen ??
                                                                "G"
                                                            )
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </div>

                                                    <!-- Info -->
                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{
                                                                gv.name ??
                                                                gv.HoTen ??
                                                                "-"
                                                            }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Giảng viên hướng dẫn
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- COUNT -->
                                            <td class="px-3 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center min-w-[42px] h-8 px-2 rounded-xl bg-emerald-100 text-emerald-600 text-xs font-bold"
                                                >
                                                    {{ countAssignedTo(gv) }}
                                                </span>
                                            </td>

                                            <!-- ACTION -->
                                            <td class="px-3 py-4 text-center">
                                                <button
                                                    @click="
                                                        openStudentModal(gv)
                                                    "
                                                    class="px-3 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-[11px] font-semibold shadow transition-all"
                                                >
                                                    Xem
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- EMPTY -->
                                        <tr v-if="teachers.length === 0">
                                            <td
                                                colspan="5"
                                                class="py-16 text-center text-slate-400"
                                            >
                                                Không có giảng viên
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL: DANH SÁCH SINH VIÊN -->
                <div
                    v-if="showStudentModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-6xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Danh sách sinh viên
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    {{
                                        selectedLecturer.name ??
                                        selectedLecturer.HoTen ??
                                        "-"
                                    }}
                                </p>
                            </div>

                            <!-- ACTION -->
                            <div class="flex items-center gap-3">
                                <!-- CONFIRM -->
                                <button
                                    @click="
                                        confirmAllAssignments(selectedLecturer)
                                    "
                                    class="h-11 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow transition-all"
                                >
                                    ✔ Xác nhận
                                </button>

                                <!-- CLOSE -->
                                <button
                                    @click="closeStudentModal"
                                    class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                                >
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- TABLE -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 overflow-hidden"
                            >
                                <table
                                    class="w-full text-[13px] border-collapse"
                                >
                                    <!-- HEAD -->
                                    <thead
                                        class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                    >
                                        <tr>
                                            <th
                                                class="px-3 py-4 text-center w-[120px]"
                                            >
                                                MSSV
                                            </th>

                                            <th
                                                class="px-3 py-4 text-left w-[220px]"
                                            >
                                                Họ và tên SV
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[90px]"
                                            >
                                                Nhóm
                                            </th>

                                            <th class="px-3 py-4 text-left">
                                                Đề tài
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- BODY -->
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="sv in studentsAssignedTo(
                                                selectedLecturer,
                                            )"
                                            :key="sv.mssv"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- MSSV -->
                                            <td
                                                class="px-3 py-4 text-center font-semibold text-slate-700"
                                            >
                                                {{ sv.mssv }}
                                            </td>

                                            <!-- NAME -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <!-- Avatar -->
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow shrink-0"
                                                    >
                                                        {{
                                                            sv.name
                                                                ?.charAt(0)
                                                                ?.toUpperCase()
                                                        }}
                                                    </div>

                                                    <!-- Info -->
                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{ sv.name }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Sinh viên
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- GROUP -->
                                            <td class="px-3 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center min-w-[40px] h-8 px-2 rounded-xl bg-indigo-100 text-indigo-600 text-xs font-bold"
                                                >
                                                    {{ sv.group }}
                                                </span>
                                            </td>

                                            <!-- TOPIC -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <!-- ICON -->
                                                    <div
                                                        class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow shrink-0"
                                                    >
                                                        📚
                                                    </div>

                                                    <!-- INFO -->
                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{ sv.topic }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Đề tài luận văn
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- EMPTY -->
                                        <tr
                                            v-if="
                                                studentsAssignedTo(
                                                    selectedLecturer,
                                                ).length === 0
                                            "
                                        >
                                            <td
                                                colspan="4"
                                                class="py-16 text-center text-slate-400"
                                            >
                                                Không có sinh viên được phân
                                                công
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ĐÁNH GIÁ 50% -->
                <div v-if="currentView === 'evaluation50'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-4xl font-extrabold text-slate-800 tracking-tight"
                            >
                                ĐÁNH GIÁ 50%
                            </h2>

                            <p class="text-slate-500 mt-2 text-sm">
                                Theo dõi tiến độ đánh giá giữa kỳ của sinh viên
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- SEARCH -->
                            <div class="relative">
                                <input
                                    v-model="evaluationSearch"
                                    type="text"
                                    placeholder="Tìm MSSV / tên..."
                                    class="w-80 h-12 rounded-2xl border border-slate-200 bg-white px-5 pr-12 outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- ICON -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- EXPORT -->
                            <button
                                @click="exportEvaluation50"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold shadow-lg transition-all"
                            >
                                📥 Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- TABLE CARD -->
                    <div
                        class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <!-- TABLE -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-xs tracking-wide"
                                >
                                    <tr>
                                        <th class="px-6 py-5 text-center">
                                            STT
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            MSSV
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Họ và tên
                                        </th>

                                        <th class="px-6 py-5 text-left">
                                            Giảng viên hướng dẫn
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Điểm
                                        </th>

                                        <th class="px-6 py-5 text-center">
                                            Xếp loại
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(s, idx) in filteredStudent"
                                        :key="s.mssv || s.id || idx"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- STT -->
                                        <td class="px-6 py-5 text-center">
                                            <div
                                                class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto"
                                            >
                                                {{ idx + 1 }}
                                            </div>
                                        </td>

                                        <!-- MSSV -->
                                        <td
                                            class="px-6 py-5 text-center font-semibold text-slate-700"
                                        >
                                            {{ s.mssv || "-" }}
                                        </td>

                                        <!-- STUDENT -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center gap-4"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold flex items-center justify-center shadow-md shrink-0"
                                                >
                                                    {{
                                                        s.name
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- Info -->
                                                <div>
                                                    <h3
                                                        class="font-semibold text-slate-800"
                                                    >
                                                        {{ s.name || "-" }}
                                                    </h3>

                                                    <p
                                                        class="text-xs text-slate-400 mt-1"
                                                    >
                                                        Sinh viên
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- LECTURER -->
                                        <td class="px-6 py-5">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <!-- Avatar -->
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 font-bold flex items-center justify-center shrink-0"
                                                >
                                                    {{
                                                        s.lecturer
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <span
                                                    class="font-medium text-slate-700"
                                                >
                                                    {{ s.lecturer || "-" }}
                                                </span>
                                            </div>
                                        </td>

                                        <!-- SCORE -->
                                        <td class="px-6 py-5 text-center">
                                            <span
                                                class="inline-flex items-center justify-center min-w-[60px] h-11 px-4 rounded-2xl bg-indigo-100 text-indigo-600 font-bold text-sm"
                                            >
                                                {{ s.score || "-" }}
                                            </span>
                                        </td>

                                        <!-- RANK -->
                                        <td class="px-6 py-5 text-center">
                                            <!-- EXCELLENT -->
                                            <span
                                                v-if="s.score >= 8.5"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 text-xs font-bold"
                                            >
                                                🟢 Xuất sắc
                                            </span>

                                            <!-- GOOD -->
                                            <span
                                                v-else-if="s.score >= 7"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 text-xs font-bold"
                                            >
                                                🔵 Khá
                                            </span>

                                            <!-- AVERAGE -->
                                            <span
                                                v-else-if="s.score >= 5"
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-amber-100 text-amber-700 text-xs font-bold"
                                            >
                                                🟡 Trung bình
                                            </span>

                                            <!-- FAIL -->
                                            <span
                                                v-else
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-rose-100 text-rose-700 text-xs font-bold"
                                            >
                                                🔴 Yếu
                                            </span>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr v-if="filteredStudent.length === 0">
                                        <td
                                            colspan="6"
                                            class="py-20 text-center text-slate-400"
                                        >
                                            Không có dữ liệu đánh giá
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-8 py-5 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-sm text-slate-500">
                                Tổng:
                                {{ filteredStudent.length }}
                                sinh viên
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-semibold"
                                >
                                    1
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PHÂN CÔNG PHẢN BIỆN -->
                <!-- PHÂN CÔNG PHẢN BIỆN -->
                <div v-if="currentView === 'reviewAssignment'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-6"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-3xl font-extrabold text-slate-800 tracking-tight"
                            >
                                PHÂN CÔNG PHẢN BIỆN
                            </h2>

                            <p class="text-slate-500 mt-1 text-sm">
                                Quản lý phân công giảng viên phản biện cho đề
                                tài luận văn
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- SEARCH -->
                            <div class="relative">
                                <input
                                    v-model="reviewSearch"
                                    type="text"
                                    placeholder="Tìm kiếm theo đề tài..."
                                    class="w-72 h-11 rounded-2xl border border-slate-200 bg-white px-4 pr-11 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- ICON -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- EXPORT -->
                            <button
                                @click="exportReviewAssignment"
                                class="h-11 px-5 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                📥 Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div
                        class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden max-w-full"
                    >
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-[13px] border-collapse">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                >
                                    <tr>
                                        <!-- STT -->
                                        <th
                                            class="px-3 py-4 text-center w-[70px]"
                                        >
                                            STT
                                        </th>

                                        <!-- CODE -->
                                        <th
                                            class="px-3 py-4 text-center w-[110px]"
                                        >
                                            Mã đề tài
                                        </th>

                                        <!-- TOPIC -->
                                        <th
                                            class="px-3 py-4 text-left w-[220px]"
                                        >
                                            Tên đề tài
                                        </th>

                                        <!-- MSSV -->
                                        <th
                                            class="px-3 py-4 text-center w-[110px]"
                                        >
                                            MSSV
                                        </th>

                                        <!-- STUDENT -->
                                        <th
                                            class="px-3 py-4 text-left w-[180px]"
                                        >
                                            Sinh viên
                                        </th>

                                        <!-- REVIEWER -->
                                        <th
                                            class="px-3 py-4 text-left w-[180px]"
                                        >
                                            Giảng viên PB
                                        </th>

                                        <!-- ACTION -->
                                        <th
                                            class="px-3 py-4 text-center w-[190px]"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <tr
                                        v-for="(
                                            row, idx
                                        ) in reviewAssignmentRows"
                                        :key="
                                            (row.topic.MaDT ||
                                                row.topic.id ||
                                                't') +
                                            '-' +
                                            idx
                                        "
                                        @mouseenter="hoverStt = row.stt"
                                        @mouseleave="hoverStt = null"
                                        :class="
                                            hoverStt === row.stt
                                                ? 'bg-indigo-50/40'
                                                : ''
                                        "
                                        class="transition-all"
                                    >
                                        <!-- STT -->
                                        <td
                                            v-if="row.isFirst"
                                            class="px-3 py-4 text-center align-middle border-r border-slate-100"
                                            :rowspan="row.rowSpan"
                                        >
                                            <div
                                                class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center mx-auto"
                                            >
                                                {{ row.stt }}
                                            </div>
                                        </td>

                                        <!-- CODE -->
                                        <td
                                            v-if="row.isFirst"
                                            class="px-3 py-4 text-center font-semibold text-slate-700 align-middle border-r border-slate-100"
                                            :rowspan="row.rowSpan"
                                        >
                                            {{ row.topic.MaDT || "-" }}
                                        </td>

                                        <!-- TOPIC -->
                                        <td
                                            v-if="row.isFirst"
                                            class="px-3 py-4 align-middle border-r border-slate-100"
                                            :rowspan="row.rowSpan"
                                        >
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <!-- ICON -->
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow shrink-0"
                                                >
                                                    📚
                                                </div>

                                                <!-- INFO -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate max-w-[170px]"
                                                    >
                                                        {{
                                                            row.topic
                                                                .TenDeTai ||
                                                            row.topic.title ||
                                                            "-"
                                                        }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 mt-0.5 truncate"
                                                    >
                                                        Đề tài luận văn
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- MSSV -->
                                        <td
                                            class="px-3 py-4 text-center font-semibold text-slate-700"
                                        >
                                            {{ row.mssv || "-" }}
                                        </td>

                                        <!-- STUDENT -->
                                        <td class="px-3 py-4">
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <!-- AVATAR -->
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center shrink-0"
                                                >
                                                    {{
                                                        row.name
                                                            ?.charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- INFO -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate"
                                                    >
                                                        {{ row.name || "-" }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 truncate"
                                                    >
                                                        Sinh viên
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- REVIEWER -->
                                        <td
                                            v-if="row.isFirst"
                                            class="px-3 py-4 align-middle border-l border-slate-100"
                                            :rowspan="row.rowSpan"
                                        >
                                            <div
                                                class="flex items-center gap-2"
                                            >
                                                <!-- AVATAR -->
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 font-bold text-xs flex items-center justify-center shrink-0"
                                                >
                                                    {{
                                                        (
                                                            row.topic
                                                                .GiangVienPhanBien ||
                                                            row.topic
                                                                .reviewerName ||
                                                            "?"
                                                        )
                                                            .charAt(0)
                                                            ?.toUpperCase()
                                                    }}
                                                </div>

                                                <!-- INFO -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate max-w-[130px]"
                                                    >
                                                        {{
                                                            row.topic
                                                                .GiangVienPhanBien ||
                                                            row.topic
                                                                .reviewerName ||
                                                            "-"
                                                        }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 truncate"
                                                    >
                                                        Giảng viên phản biện
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- ACTION -->
                                        <td
                                            v-if="row.isFirst"
                                            class="px-3 py-4 align-middle"
                                            :rowspan="row.rowSpan"
                                        >
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <!-- ASSIGN -->
                                                <button
                                                    @click="
                                                        openReviewerList(
                                                            row.topic,
                                                        )
                                                    "
                                                    class="px-3 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-semibold shadow transition-all whitespace-nowrap"
                                                >
                                                    Phân công
                                                </button>

                                                <!-- DELETE -->
                                                <button
                                                    class="px-3 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-[11px] font-semibold shadow transition-all whitespace-nowrap"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- EMPTY -->
                                    <tr
                                        v-if="reviewAssignmentRows.length === 0"
                                    >
                                        <td
                                            colspan="7"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có đề tài để phân công phản
                                            biện
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-xs text-slate-500">
                                Tổng:
                                {{ reviewAssignmentRows.length }}
                                dòng dữ liệu
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                                >
                                    1
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- MODAL: DANH SÁCH PHẢN BIỆN VIÊN -->
                <div
                    v-if="showReviewerModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Danh sách phản biện viên
                                </h3>

                                <p
                                    class="text-indigo-100 text-sm mt-1 truncate max-w-[700px]"
                                >
                                    {{ selectedTopic?.TenDeTai || "-" }}
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="closeReviewerModal"
                                type="button"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- SEARCH -->
                            <div class="flex justify-end mb-5">
                                <div class="relative">
                                    <input
                                        v-model="reviewerSearch"
                                        type="text"
                                        placeholder="Tìm phản biện viên..."
                                        class="w-72 h-11 rounded-2xl border border-slate-200 bg-white px-4 pr-11 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                    />

                                    <!-- ICON -->
                                    <div
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"
                                    >
                                        🔍
                                    </div>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 overflow-hidden"
                            >
                                <table
                                    class="w-full text-[13px] border-collapse"
                                >
                                    <!-- HEAD -->
                                    <thead
                                        class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                    >
                                        <tr>
                                            <th
                                                class="px-3 py-4 text-center w-[70px]"
                                            >
                                                STT
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[120px]"
                                            >
                                                MSGV
                                            </th>

                                            <th class="px-3 py-4 text-left">
                                                Họ và tên phản biện
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[170px]"
                                            >
                                                Số đề tài
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[120px]"
                                            >
                                                Thao tác
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- BODY -->
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="(
                                                gv, idx
                                            ) in availableReviewers"
                                            :key="gv.MaGV || idx"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- STT -->
                                            <td class="px-3 py-4 text-center">
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center mx-auto"
                                                >
                                                    {{ idx + 1 }}
                                                </div>
                                            </td>

                                            <!-- MSGV -->
                                            <td
                                                class="px-3 py-4 text-center font-semibold text-slate-700"
                                            >
                                                {{ gv.MaGV ?? gv.id ?? "-" }}
                                            </td>

                                            <!-- REVIEWER -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <!-- Avatar -->
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow shrink-0"
                                                    >
                                                        {{
                                                            (
                                                                gv.name ??
                                                                gv.HoTen ??
                                                                "G"
                                                            )
                                                                .charAt(0)
                                                                .toUpperCase()
                                                        }}
                                                    </div>

                                                    <!-- Info -->
                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{
                                                                gv.name ??
                                                                gv.HoTen ??
                                                                "-"
                                                            }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Giảng viên phản biện
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- COUNT -->
                                            <td class="px-3 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center justify-center min-w-[42px] h-8 px-2 rounded-xl bg-amber-100 text-amber-600 text-xs font-bold"
                                                >
                                                    {{
                                                        countReviewAssigned(gv)
                                                    }}
                                                </span>
                                            </td>

                                            <!-- ACTION -->
                                            <td class="px-3 py-4 text-center">
                                                <button
                                                    @click="
                                                        openReviewerTopicList(
                                                            gv,
                                                        )
                                                    "
                                                    class="px-3 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-[11px] font-semibold shadow transition-all"
                                                >
                                                    Xem
                                                </button>
                                            </td>
                                        </tr>

                                        <!-- EMPTY -->
                                        <tr
                                            v-if="
                                                availableReviewers.length === 0
                                            "
                                        >
                                            <td
                                                colspan="5"
                                                class="py-16 text-center text-slate-400"
                                            >
                                                Không có phản biện viên
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- MODAL: DANH SÁCH ĐỀ TÀI -->
                <div
                    v-if="showReviewTopicModal"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Danh sách đề tài phản biện
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    {{
                                        selectedReviewer?.name ??
                                        selectedReviewer?.HoTen ??
                                        "-"
                                    }}
                                </p>
                            </div>

                            <!-- ACTION -->
                            <div class="flex items-center gap-3">
                                <!-- CONFIRM -->
                                <button
                                    @click="
                                        confirmReviewAssignment(
                                            selectedReviewer,
                                        )
                                    "
                                    type="button"
                                    class="h-11 px-5 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold shadow transition-all"
                                >
                                    ✔ Xác nhận
                                </button>

                                <!-- CLOSE -->
                                <button
                                    @click="closeReviewerTopicList"
                                    class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                                >
                                    ✕
                                </button>
                            </div>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- TABLE -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 overflow-hidden"
                            >
                                <table
                                    class="w-full text-[13px] border-collapse"
                                >
                                    <!-- HEAD -->
                                    <thead
                                        class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                    >
                                        <tr>
                                            <th
                                                class="px-3 py-4 text-center w-[150px]"
                                            >
                                                Mã đề tài
                                            </th>

                                            <th class="px-3 py-4 text-left">
                                                Tên đề tài
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[160px]"
                                            >
                                                Trạng thái
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- BODY -->
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="t in reviewTopicsAssignedTo(
                                                selectedReviewer,
                                            )"
                                            :key="t.MaDT"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- CODE -->
                                            <td
                                                class="px-3 py-4 text-center font-semibold text-slate-700"
                                            >
                                                {{ t.MaDT }}
                                            </td>

                                            <!-- TOPIC -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <!-- ICON -->
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow shrink-0"
                                                    >
                                                        📚
                                                    </div>

                                                    <!-- INFO -->
                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{ t.TenDeTai }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Đề tài luận văn
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- STATUS -->
                                            <td class="px-3 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-xs font-bold"
                                                >
                                                    🟢 Đã phân công
                                                </span>
                                            </td>
                                        </tr>

                                        <!-- EMPTY -->
                                        <tr
                                            v-if="
                                                reviewTopicsAssignedTo(
                                                    selectedReviewer,
                                                ).length === 0
                                            "
                                        >
                                            <td
                                                colspan="3"
                                                class="py-16 text-center text-slate-400"
                                            >
                                                Không có đề tài được phân công
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- THÀNH LẬP HỘI ĐỒNG -->
                <div v-if="currentView === 'committee'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-8"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-4xl font-extrabold text-slate-800 tracking-tight"
                            >
                                THÀNH LẬP HỘI ĐỒNG
                            </h2>

                            <p class="text-slate-500 mt-2 text-sm">
                                Quản lý danh sách hội đồng bảo vệ luận văn
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-4">
                            <!-- ADD -->
                            <button
                                @click="openCommitteeAddForm"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-semibold shadow-lg transition-all"
                            >
                                + Thêm hội đồng
                            </button>

                            <!-- EXPORT -->
                            <button
                                @click="exportCommittee"
                                class="h-12 px-6 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white font-semibold shadow-lg transition-all"
                            >
                                📥 Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- TABLE CARD -->
                    <div
                        class="bg-white rounded-[32px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <!-- TABLE -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-xs tracking-wide"
                                >
                                    <tr>
                                        <th class="px-6 py-5 text-center w-20">
                                            STT
                                        </th>

                                        <th
                                            class="px-6 py-5 text-left min-w-[250px]"
                                        >
                                            Tên hội đồng
                                        </th>

                                        <th
                                            class="px-6 py-5 text-center min-w-[220px]"
                                        >
                                            Ngày giờ bắt đầu
                                        </th>

                                        <th
                                            class="px-6 py-5 text-center min-w-[220px]"
                                        >
                                            Ngày giờ kết thúc
                                        </th>

                                        <th
                                            class="px-6 py-5 text-center min-w-[250px]"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <!-- EMPTY -->
                                    <tr
                                        v-if="
                                            groupedAndSortedCommittees.length ===
                                            0
                                        "
                                    >
                                        <td
                                            colspan="5"
                                            class="py-20 text-center text-slate-400"
                                        >
                                            Chưa có hội đồng nào
                                        </td>
                                    </tr>

                                    <!-- GROUP -->
                                    <template
                                        v-for="(
                                            group, gIdx
                                        ) in groupedAndSortedCommittees"
                                        :key="'group-' + gIdx"
                                    >
                                        <!-- DATE HEADER -->
                                        <tr
                                            class="bg-gradient-to-r from-indigo-500 to-indigo-600"
                                        >
                                            <td colspan="5" class="px-6 py-4">
                                                <div
                                                    class="flex items-center gap-3 text-white"
                                                >
                                                    <!-- Icon -->
                                                    <div
                                                        class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center text-lg"
                                                    >
                                                        📅
                                                    </div>

                                                    <!-- Info -->
                                                    <div>
                                                        <h3
                                                            class="font-bold text-lg"
                                                        >
                                                            Ngày bắt đầu:
                                                            {{ group.date }}
                                                        </h3>

                                                        <p
                                                            class="text-indigo-100 text-xs mt-1"
                                                        >
                                                            {{
                                                                group.committees
                                                                    .length
                                                            }}
                                                            hội đồng
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- COMMITTEE -->
                                        <tr
                                            v-for="(
                                                c, cIdx
                                            ) in group.committees"
                                            :key="c.id || cIdx"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- STT -->
                                            <td class="px-6 py-5 text-center">
                                                <div
                                                    class="w-10 h-10 rounded-2xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center mx-auto"
                                                >
                                                    {{ cIdx + 1 }}
                                                </div>
                                            </td>

                                            <!-- NAME -->
                                            <td class="px-6 py-5">
                                                <div
                                                    class="flex items-center gap-4"
                                                >
                                                    <!-- Icon -->
                                                    <div
                                                        class="w-12 h-12 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow-md text-xl shrink-0"
                                                    >
                                                        🏛️
                                                    </div>

                                                    <!-- Info -->
                                                    <div>
                                                        <h3
                                                            class="font-semibold text-slate-800"
                                                        >
                                                            Hội đồng
                                                            {{
                                                                committeeIndexMap.has(
                                                                    c.id,
                                                                )
                                                                    ? committeeIndexMap.get(
                                                                          c.id,
                                                                      ) + 1
                                                                    : cIdx + 1
                                                            }}
                                                        </h3>

                                                        <p
                                                            class="text-xs text-slate-400 mt-1"
                                                        >
                                                            Hội đồng bảo vệ luận
                                                            văn
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- START -->
                                            <td class="px-6 py-5 text-center">
                                                <div
                                                    class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-emerald-100 text-emerald-700 text-sm font-semibold"
                                                >
                                                    🟢
                                                    {{
                                                        c.start
                                                            ? formatDateTime(
                                                                  c.start,
                                                              )
                                                            : "-"
                                                    }}
                                                </div>
                                            </td>

                                            <!-- END -->
                                            <td class="px-6 py-5 text-center">
                                                <div
                                                    class="inline-flex items-center gap-2 px-4 py-3 rounded-2xl bg-amber-100 text-amber-700 text-sm font-semibold"
                                                >
                                                    🟡
                                                    {{
                                                        c.end
                                                            ? formatDateTime(
                                                                  c.end,
                                                              )
                                                            : "-"
                                                    }}
                                                </div>
                                            </td>

                                            <!-- ACTION -->
                                            <td class="px-6 py-5">
                                                <div
                                                    class="flex items-center justify-center gap-3"
                                                >
                                                    <!-- DETAIL -->
                                                    <button
                                                        @click="
                                                            openCommitteeDetail(
                                                                c,
                                                            )
                                                        "
                                                        class="px-4 py-2 rounded-xl bg-indigo-500 hover:bg-indigo-600 text-white text-xs font-semibold shadow transition-all"
                                                    >
                                                        Chi tiết
                                                    </button>

                                                    <!-- EDIT -->
                                                    <button
                                                        @click="
                                                            openCommitteeEditForm(
                                                                c,
                                                            )
                                                        "
                                                        class="px-4 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold shadow transition-all"
                                                    >
                                                        Sửa
                                                    </button>

                                                    <!-- DELETE -->
                                                    <button
                                                        @click="
                                                            deleteCommittee(c)
                                                        "
                                                        class="px-4 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-xs font-semibold shadow transition-all"
                                                    >
                                                        Xóa
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-8 py-5 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-sm text-slate-500">
                                Tổng:
                                {{ groupedAndSortedCommittees.length }}
                                nhóm hội đồng
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl bg-indigo-600 text-white font-semibold"
                                >
                                    1
                                </button>

                                <button
                                    class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- MODAL: FORM THÊM / SỬA HỘI ĐỒNG -->
                <!-- ========================================================= -->
                <div
                    v-if="showCommitteeForm"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    {{
                                        committeeFormMode === "add"
                                            ? "Thêm hội đồng"
                                            : "Sửa hội đồng"
                                    }}
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    Cấu hình giảng viên và thời gian hội đồng
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="closeCommitteeForm"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- MEMBERS -->
                            <div
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6 mb-6"
                            >
                                <!-- TITLE -->
                                <div class="flex items-center gap-3 mb-5">
                                    <div
                                        class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl"
                                    >
                                        👨‍🏫
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-800">
                                            Danh sách giảng viên
                                        </h4>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Chọn giảng viên và chức vụ trong hội
                                            đồng
                                        </p>
                                    </div>
                                </div>

                                <!-- LIST -->
                                <div class="space-y-4">
                                    <div
                                        v-for="(
                                            row, index
                                        ) in committeeForm.members"
                                        :key="index"
                                        class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end p-4 rounded-2xl border border-slate-100 bg-slate-50"
                                    >
                                        <!-- STT -->
                                        <div class="lg:col-span-1">
                                            <div
                                                class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-600 font-bold flex items-center justify-center"
                                            >
                                                {{ index + 1 }}
                                            </div>
                                        </div>

                                        <!-- TEACHER -->
                                        <div class="lg:col-span-6">
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2"
                                            >
                                                Giảng viên
                                            </label>

                                            <select
                                                v-model="
                                                    committeeForm.members[index]
                                                        .teacherId
                                                "
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            >
                                                <option value="">
                                                    -- Chọn giảng viên --
                                                </option>

                                                <option
                                                    v-for="t in availableTeachersForRow(
                                                        index,
                                                    )"
                                                    :key="getTeacherId(t)"
                                                    :value="getTeacherId(t)"
                                                >
                                                    {{
                                                        t.name ??
                                                        t.HoTen ??
                                                        t.Ho_va_Ten ??
                                                        getTeacherId(t)
                                                    }}
                                                </option>
                                            </select>
                                        </div>

                                        <!-- POSITION -->
                                        <div class="lg:col-span-5">
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2"
                                            >
                                                Chức vụ
                                            </label>

                                            <select
                                                v-model="
                                                    committeeForm.members[index]
                                                        .position
                                                "
                                                class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                            >
                                                <option value="">
                                                    -- Chọn chức vụ --
                                                </option>

                                                <option
                                                    v-for="pos in availablePositionsForRow(
                                                        index,
                                                    )"
                                                    :key="pos.value"
                                                    :value="pos.value"
                                                >
                                                    {{ pos.label }}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TIME -->
                            <div
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm p-6"
                            >
                                <!-- TITLE -->
                                <div class="flex items-center gap-3 mb-5">
                                    <div
                                        class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl"
                                    >
                                        📅
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-800">
                                            Thời gian hội đồng
                                        </h4>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Thiết lập thời gian bảo vệ luận văn
                                        </p>
                                    </div>
                                </div>

                                <!-- INPUT -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                >
                                    <!-- START -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                        >
                                            Ngày giờ bắt đầu
                                        </label>

                                        <input
                                            type="datetime-local"
                                            v-model="committeeForm.start"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>

                                    <!-- END -->
                                    <div>
                                        <label
                                            class="block text-sm font-semibold text-slate-700 mb-2"
                                        >
                                            Ngày giờ kết thúc
                                        </label>

                                        <input
                                            type="datetime-local"
                                            v-model="committeeForm.end"
                                            class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-end gap-3 px-8 py-5 border-t border-slate-200 bg-white"
                        >
                            <!-- CANCEL -->
                            <button
                                @click="closeCommitteeForm"
                                class="h-11 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition"
                            >
                                Hủy
                            </button>

                            <!-- SUBMIT -->
                            <button
                                @click="submitCommitteeForm"
                                class="h-11 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                {{
                                    committeeFormMode === "add"
                                        ? "✔ Xác nhận thêm"
                                        : "✔ Xác nhận sửa"
                                }}
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- MODAL: CHI TIẾT HỘI ĐỒNG -->
                <!-- IMPORTANT: z-[70] để nổi lên trên modal form -->
                <!-- ========================================================= -->
                <div
                    v-if="showCommitteeDetailModal"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-5xl max-h-[92vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Chi tiết hội đồng
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    Thông tin chi tiết hội đồng bảo vệ luận văn
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="showCommitteeDetailModal = false"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="flex-1 overflow-y-auto bg-slate-50 p-6">
                            <!-- TIME -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6"
                            >
                                <!-- START -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-lg"
                                        >
                                            🟢
                                        </div>

                                        <div>
                                            <p class="text-xs text-slate-400">
                                                Thời gian bắt đầu
                                            </p>

                                            <h4
                                                class="font-semibold text-slate-800 mt-1"
                                            >
                                                {{
                                                    selectedCommittee?.start
                                                        ? formatDateTime(
                                                              selectedCommittee.start,
                                                          )
                                                        : "-"
                                                }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>

                                <!-- END -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5 shadow-sm"
                                >
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-11 h-11 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-lg"
                                        >
                                            🟡
                                        </div>

                                        <div>
                                            <p class="text-xs text-slate-400">
                                                Thời gian kết thúc
                                            </p>

                                            <h4
                                                class="font-semibold text-slate-800 mt-1"
                                            >
                                                {{
                                                    selectedCommittee?.end
                                                        ? formatDateTime(
                                                              selectedCommittee.end,
                                                          )
                                                        : "-"
                                                }}
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div
                                class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden"
                            >
                                <!-- TITLE -->
                                <div
                                    class="flex items-center gap-3 px-6 py-5 border-b border-slate-100"
                                >
                                    <div
                                        class="w-11 h-11 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl"
                                    >
                                        👨‍🏫
                                    </div>

                                    <div>
                                        <h4 class="font-bold text-slate-800">
                                            Danh sách giảng viên
                                        </h4>

                                        <p class="text-xs text-slate-400 mt-1">
                                            Thành viên trong hội đồng
                                        </p>
                                    </div>
                                </div>

                                <!-- TABLE -->
                                <table
                                    class="w-full text-[13px] border-collapse"
                                >
                                    <!-- HEAD -->
                                    <thead
                                        class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                    >
                                        <tr>
                                            <th
                                                class="px-3 py-4 text-center w-[80px]"
                                            >
                                                STT
                                            </th>

                                            <th class="px-3 py-4 text-left">
                                                Giảng viên
                                            </th>

                                            <th
                                                class="px-3 py-4 text-center w-[220px]"
                                            >
                                                Chức vụ
                                            </th>
                                        </tr>
                                    </thead>

                                    <!-- BODY -->
                                    <tbody class="divide-y divide-slate-100">
                                        <tr
                                            v-for="(
                                                m, idx
                                            ) in selectedCommittee?.members ||
                                            []"
                                            :key="idx"
                                            class="hover:bg-indigo-50/40 transition-all"
                                        >
                                            <!-- STT -->
                                            <td class="px-3 py-4 text-center">
                                                <div
                                                    class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center mx-auto"
                                                >
                                                    {{ idx + 1 }}
                                                </div>
                                            </td>

                                            <!-- TEACHER -->
                                            <td class="px-3 py-4">
                                                <div
                                                    class="flex items-center gap-3"
                                                >
                                                    <div
                                                        class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center shadow shrink-0"
                                                    >
                                                        {{
                                                            (
                                                                m.teacherName ||
                                                                getTeacherNameById(
                                                                    m.teacherId,
                                                                ) ||
                                                                "G"
                                                            )
                                                                .charAt(0)
                                                                ?.toUpperCase()
                                                        }}
                                                    </div>

                                                    <div class="min-w-0">
                                                        <h3
                                                            class="font-semibold text-slate-800 truncate"
                                                        >
                                                            {{
                                                                m.teacherName ||
                                                                getTeacherNameById(
                                                                    m.teacherId,
                                                                ) ||
                                                                m.teacherId
                                                            }}
                                                        </h3>

                                                        <p
                                                            class="text-[11px] text-slate-400 mt-0.5"
                                                        >
                                                            Giảng viên hội đồng
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <!-- POSITION -->
                                            <td class="px-3 py-4 text-center">
                                                <span
                                                    class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-100 text-indigo-600 text-xs font-bold"
                                                >
                                                    {{
                                                        positionLabel(
                                                            m.position,
                                                        )
                                                    }}
                                                </span>
                                            </td>
                                        </tr>

                                        <!-- EMPTY -->
                                        <tr
                                            v-if="
                                                !selectedCommittee ||
                                                (
                                                    selectedCommittee.members ||
                                                    []
                                                ).length === 0
                                            "
                                        >
                                            <td
                                                colspan="3"
                                                class="py-16 text-center text-slate-400"
                                            >
                                                Không có giảng viên trong hội
                                                đồng
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Các view khác (grading) để trống -->
                <div v-if="currentView === 'grading'"></div>

                <!-- PHÂN CÔNG HỘI ĐỒNG -->
                <!-- PHÂN CÔNG HỘI ĐỒNG -->
                <div v-if="currentView === 'committeeAssignment'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-6"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-3xl font-extrabold text-slate-800 tracking-tight"
                            >
                                PHÂN CÔNG HỘI ĐỒNG
                            </h2>

                            <p class="text-slate-500 mt-1 text-sm">
                                Quản lý phân công hội đồng bảo vệ luận văn
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- SEARCH -->
                            <div class="relative">
                                <input
                                    v-model="committeeAssignSearch"
                                    type="text"
                                    placeholder="Tìm MSSV / tên / đề tài..."
                                    class="w-72 h-11 rounded-2xl border border-slate-200 bg-white px-4 pr-11 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400 shadow-sm"
                                />

                                <!-- ICON -->
                                <div
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"
                                >
                                    🔍
                                </div>
                            </div>

                            <!-- EXPORT -->
                            <button
                                @click="exportCommitteeExcel"
                                class="h-11 px-5 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                📤 Xuất Excel
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div
                        class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden max-w-full"
                    >
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-[13px] border-collapse">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                >
                                    <tr>
                                        <!-- MSSV -->
                                        <th
                                            class="px-2 py-4 text-center w-[90px]"
                                        >
                                            MSSV
                                        </th>

                                        <!-- NAME -->
                                        <th
                                            class="px-2 py-4 text-left w-[170px]"
                                        >
                                            Họ và tên SV
                                        </th>

                                        <!-- SCORE -->
                                        <th
                                            class="px-2 py-4 text-center w-[90px]"
                                        >
                                            Điểm 50%
                                        </th>

                                        <!-- TOPIC -->
                                        <th
                                            class="px-2 py-4 text-left w-[180px]"
                                        >
                                            Đề tài
                                        </th>

                                        <!-- GVHD -->
                                        <th
                                            class="px-2 py-4 text-left w-[130px]"
                                        >
                                            GVHD
                                        </th>

                                        <!-- GVPB -->
                                        <th
                                            class="px-2 py-4 text-left w-[130px]"
                                        >
                                            GVPB
                                        </th>

                                        <!-- COMMITTEE -->
                                        <th
                                            class="px-2 py-4 text-center w-[110px]"
                                        >
                                            Hội đồng
                                        </th>

                                        <!-- ACTION -->
                                        <th
                                            class="px-2 py-4 text-center w-[150px]"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <template
                                        v-if="
                                            filteredCommitteeAssignRows.length
                                        "
                                    >
                                        <template
                                            v-for="(
                                                group, gi
                                            ) in filteredCommitteeAssignRows"
                                            :key="
                                                (group.topic || '') + '-' + gi
                                            "
                                        >
                                            <tr
                                                v-for="(
                                                    stu, si
                                                ) in group.students"
                                                :key="
                                                    (stu.mssv || stu.name) +
                                                    '-' +
                                                    si
                                                "
                                                @mouseenter="hoveredGroup = gi"
                                                @mouseleave="
                                                    hoveredGroup = null
                                                "
                                                :class="
                                                    hoveredGroup === gi
                                                        ? 'bg-indigo-50/40'
                                                        : ''
                                                "
                                                class="transition-all"
                                            >
                                                <!-- MSSV -->
                                                <td
                                                    class="px-2 py-4 text-center font-semibold text-slate-700"
                                                >
                                                    {{ stu.mssv || "-" }}
                                                </td>

                                                <!-- NAME -->
                                                <td class="px-2 py-4">
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <!-- Avatar -->
                                                        <div
                                                            class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white font-bold text-xs flex items-center justify-center shadow shrink-0"
                                                        >
                                                            {{
                                                                stu.name
                                                                    ?.charAt(0)
                                                                    ?.toUpperCase()
                                                            }}
                                                        </div>

                                                        <!-- Info -->
                                                        <div class="min-w-0">
                                                            <h3
                                                                class="font-semibold text-slate-800 truncate"
                                                            >
                                                                {{
                                                                    stu.name ||
                                                                    "-"
                                                                }}
                                                            </h3>

                                                            <p
                                                                class="text-[11px] text-slate-400 truncate"
                                                            >
                                                                Sinh viên
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- SCORE -->
                                                <td
                                                    class="px-2 py-4 text-center"
                                                >
                                                    <span
                                                        v-if="
                                                            stu.diem50 !==
                                                                null &&
                                                            stu.diem50 !== ''
                                                        "
                                                        :class="
                                                            stu.diem50 >= 5
                                                                ? 'bg-emerald-100 text-emerald-600'
                                                                : 'bg-rose-100 text-rose-600'
                                                        "
                                                        class="inline-flex items-center justify-center min-w-[45px] h-8 px-2 rounded-xl text-xs font-bold"
                                                    >
                                                        {{ stu.diem50 }}
                                                    </span>

                                                    <span
                                                        v-else
                                                        class="text-slate-400 italic text-xs"
                                                    >
                                                        Chưa có
                                                    </span>
                                                </td>

                                                <!-- TOPIC -->
                                                <td
                                                    v-if="si === 0"
                                                    class="px-2 py-4"
                                                    :rowspan="group.rowSpan"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <!-- Icon -->
                                                        <div
                                                            class="w-9 h-9 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center text-sm shadow shrink-0"
                                                        >
                                                            📚
                                                        </div>

                                                        <div class="min-w-0">
                                                            <h3
                                                                class="font-semibold text-slate-800 truncate max-w-[140px]"
                                                            >
                                                                {{
                                                                    group.topic ||
                                                                    "-"
                                                                }}
                                                            </h3>

                                                            <p
                                                                class="text-[11px] text-slate-400 truncate"
                                                            >
                                                                Đề tài luận văn
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>

                                                <!-- GVHD -->
                                                <td
                                                    v-if="si === 0"
                                                    class="px-2 py-4"
                                                    :rowspan="group.rowSpan"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <div
                                                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 font-bold text-xs flex items-center justify-center shrink-0"
                                                        >
                                                            {{
                                                                group.gvhd
                                                                    ?.charAt(0)
                                                                    ?.toUpperCase()
                                                            }}
                                                        </div>

                                                        <span
                                                            class="font-medium text-slate-700 truncate max-w-[90px]"
                                                        >
                                                            {{
                                                                group.gvhd ||
                                                                "-"
                                                            }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- GVPB -->
                                                <td
                                                    v-if="si === 0"
                                                    class="px-2 py-4"
                                                    :rowspan="group.rowSpan"
                                                >
                                                    <div
                                                        class="flex items-center gap-2"
                                                    >
                                                        <div
                                                            class="w-8 h-8 rounded-lg bg-amber-100 text-amber-600 font-bold text-xs flex items-center justify-center shrink-0"
                                                        >
                                                            {{
                                                                group.gvpb
                                                                    ?.charAt(0)
                                                                    ?.toUpperCase()
                                                            }}
                                                        </div>

                                                        <span
                                                            class="font-medium text-slate-700 truncate max-w-[90px]"
                                                        >
                                                            {{
                                                                group.gvpb ||
                                                                "-"
                                                            }}
                                                        </span>
                                                    </div>
                                                </td>

                                                <!-- COMMITTEE -->
                                                <td
                                                    v-if="si === 0"
                                                    class="px-2 py-4 text-center"
                                                    :rowspan="group.rowSpan"
                                                >
                                                    <span
                                                        v-if="
                                                            group.MaHD &&
                                                            committeeIndexMap.has(
                                                                group.MaHD,
                                                            )
                                                        "
                                                        class="inline-flex items-center justify-center px-3 h-8 rounded-xl bg-indigo-100 text-indigo-600 text-xs font-bold"
                                                    >
                                                        {{
                                                            getCommitteeLabel(
                                                                group.MaHD,
                                                            )
                                                        }}
                                                    </span>

                                                    <span
                                                        v-else
                                                        class="text-slate-400 italic text-xs"
                                                    >
                                                        Chưa có
                                                    </span>
                                                </td>

                                                <!-- ACTION -->
                                                <td
                                                    v-if="si === 0"
                                                    class="px-2 py-4"
                                                    :rowspan="group.rowSpan"
                                                >
                                                    <div
                                                        class="flex items-center justify-center gap-1"
                                                    >
                                                        <!-- ASSIGN -->
                                                        <button
                                                            @click="
                                                                openCommitteeAssign(
                                                                    group,
                                                                )
                                                            "
                                                            class="px-2.5 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-[10px] font-semibold shadow transition-all"
                                                        >
                                                            Phân công
                                                        </button>

                                                        <!-- DELETE -->
                                                        <button
                                                            @click="
                                                                removeGroupAssignment(
                                                                    group,
                                                                )
                                                            "
                                                            class="px-2.5 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-[10px] font-semibold shadow transition-all"
                                                        >
                                                            Xóa
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </template>
                                    </template>

                                    <!-- EMPTY -->
                                    <tr v-else>
                                        <td
                                            colspan="8"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Không có dữ liệu để phân công
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-xs text-slate-500">
                                Tổng:
                                {{ filteredCommitteeAssignRows.length }}
                                nhóm đề tài
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                                >
                                    1
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- MODAL -->
                    <div
                        v-if="showCommitteeAssignModal"
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                    >
                        <!-- MODAL CONTENT -->
                        <div
                            class="bg-white rounded-[32px] w-full max-w-5xl max-h-[90vh] overflow-hidden shadow-2xl border border-slate-200 flex flex-col"
                        >
                            <!-- HEADER -->
                            <div
                                class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                            >
                                <div>
                                    <h3 class="text-2xl font-bold">
                                        Chọn hội đồng
                                    </h3>

                                    <p class="text-indigo-100 text-sm mt-1">
                                        {{ selectedAssignment?.topic || "-" }}
                                    </p>
                                </div>

                                <!-- CLOSE -->
                                <button
                                    @click="closeCommitteeAssignment"
                                    class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                                >
                                    ✕
                                </button>
                            </div>

                            <!-- BODY -->
                            <div class="flex-1 overflow-y-auto p-6 bg-slate-50">
                                <div
                                    class="grid grid-cols-1 lg:grid-cols-2 gap-5"
                                >
                                    <div
                                        v-for="(c, idx) in committees"
                                        :key="c.id || idx"
                                        class="bg-white rounded-[24px] border border-slate-200 p-5 shadow-sm hover:shadow-md transition-all"
                                    >
                                        <!-- TOP -->
                                        <div
                                            class="flex items-start justify-between mb-5"
                                        >
                                            <div>
                                                <h3
                                                    class="text-lg font-bold text-slate-800"
                                                >
                                                    Hội đồng
                                                    {{ idx + 1 }}
                                                </h3>

                                                <p
                                                    class="text-xs text-slate-400 mt-1"
                                                >
                                                    {{
                                                        (c.members || []).length
                                                    }}
                                                    giảng viên
                                                </p>
                                            </div>

                                            <div
                                                class="w-12 h-12 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-xl"
                                            >
                                                🏛️
                                            </div>
                                        </div>

                                        <!-- TIME -->
                                        <div class="space-y-3 mb-5">
                                            <div
                                                class="flex items-center gap-2 text-sm text-slate-600"
                                            >
                                                <span class="text-emerald-500">
                                                    🟢
                                                </span>

                                                {{
                                                    c.start
                                                        ? formatDateTime(
                                                              c.start,
                                                          )
                                                        : "-"
                                                }}
                                            </div>

                                            <div
                                                class="flex items-center gap-2 text-sm text-slate-600"
                                            >
                                                <span class="text-amber-500">
                                                    🟡
                                                </span>

                                                {{
                                                    c.end
                                                        ? formatDateTime(c.end)
                                                        : "-"
                                                }}
                                            </div>
                                        </div>

                                        <!-- ACTION -->
                                        <div class="flex items-center gap-3">
                                            <!-- DETAIL -->
                                            <button
                                                @click="openCommitteeDetail(c)"
                                                class="flex-1 h-11 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition"
                                            >
                                                Chi tiết
                                            </button>

                                            <!-- SELECT -->
                                            <button
                                                @click="assignCommitteeTo(c)"
                                                class="flex-1 h-11 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                                            >
                                                Chọn
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- EMPTY -->
                                <div
                                    v-if="committees.length === 0"
                                    class="py-16 text-center text-slate-400"
                                >
                                    Không có hội đồng
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PHÂN BỔ THỜI GIAN -->
                <!-- PHÂN BỔ THỜI GIAN -->
                <!-- PHÂN BỔ THỜI GIAN -->
                <div v-if="currentView === 'timeAllocation'">
                    <!-- HEADER -->
                    <div
                        class="flex flex-col xl:flex-row xl:items-center xl:justify-between gap-5 mb-6"
                    >
                        <!-- LEFT -->
                        <div>
                            <h2
                                class="text-3xl font-extrabold text-slate-800 tracking-tight"
                            >
                                PHÂN BỔ THỜI GIAN
                            </h2>

                            <p class="text-slate-500 mt-1 text-sm">
                                Quản lý các mốc thời gian của luận văn tốt
                                nghiệp
                            </p>
                        </div>

                        <!-- RIGHT -->
                        <div class="flex items-center gap-3">
                            <!-- ADD -->
                            <button
                                @click="showAddTimeModal = true"
                                class="h-11 px-5 rounded-2xl bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                + Thêm sự kiện
                            </button>
                        </div>
                    </div>

                    <!-- TABLE -->
                    <div
                        class="bg-white rounded-[28px] border border-slate-200 shadow-sm overflow-hidden"
                    >
                        <div class="w-full overflow-hidden">
                            <table class="w-full text-[13px] border-collapse">
                                <!-- HEAD -->
                                <thead
                                    class="bg-slate-100 text-indigo-700 uppercase text-[11px] tracking-wide"
                                >
                                    <tr>
                                        <!-- EVENT -->
                                        <th
                                            class="px-3 py-4 text-left w-[300px]"
                                        >
                                            Sự kiện
                                        </th>

                                        <!-- START -->
                                        <th
                                            class="px-3 py-4 text-center w-[220px]"
                                        >
                                            Ngày bắt đầu
                                        </th>

                                        <!-- END -->
                                        <th
                                            class="px-3 py-4 text-center w-[220px]"
                                        >
                                            Ngày kết thúc
                                        </th>

                                        <!-- STATUS -->
                                        <th
                                            class="px-3 py-4 text-center w-[160px]"
                                        >
                                            Trạng thái
                                        </th>

                                        <!-- ACTION -->
                                        <th
                                            class="px-3 py-4 text-center w-[170px]"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- BODY -->
                                <tbody class="divide-y divide-slate-100">
                                    <!-- EMPTY -->
                                    <tr v-if="timeAllocations.length === 0">
                                        <td
                                            colspan="5"
                                            class="py-16 text-center text-slate-400"
                                        >
                                            Chưa có dữ liệu phân bổ thời gian
                                        </td>
                                    </tr>

                                    <!-- DATA -->
                                    <tr
                                        v-for="(item, index) in timeAllocations"
                                        :key="item.id || index"
                                        class="hover:bg-indigo-50/40 transition-all"
                                    >
                                        <!-- EVENT -->
                                        <td class="px-3 py-4">
                                            <div
                                                class="flex items-center gap-3"
                                            >
                                                <!-- ICON -->
                                                <div
                                                    class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-500 to-indigo-600 text-white flex items-center justify-center shadow shrink-0"
                                                >
                                                    📅
                                                </div>

                                                <!-- INFO -->
                                                <div class="min-w-0">
                                                    <h3
                                                        class="font-semibold text-slate-800 truncate"
                                                    >
                                                        {{ item.TenSuKien }}
                                                    </h3>

                                                    <p
                                                        class="text-[11px] text-slate-400 mt-0.5"
                                                    >
                                                        Sự kiện luận văn
                                                    </p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- START -->
                                        <td class="px-3 py-4 text-center">
                                            <div
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-xs font-semibold"
                                            >
                                                🟢
                                                {{
                                                    formatDateTime(
                                                        item.NgayBatDau,
                                                    )
                                                }}
                                            </div>
                                        </td>

                                        <!-- END -->
                                        <td class="px-3 py-4 text-center">
                                            <div
                                                class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-100 text-amber-700 text-xs font-semibold"
                                            >
                                                🟡
                                                {{
                                                    formatDateTime(
                                                        item.NgayKetThuc,
                                                    )
                                                }}
                                            </div>
                                        </td>

                                        <!-- STATUS -->
                                        <td class="px-3 py-4 text-center">
                                            <!-- ACTIVE -->
                                            <span
                                                v-if="
                                                    new Date(item.NgayBatDau) <=
                                                        new Date() &&
                                                    new Date(
                                                        item.NgayKetThuc,
                                                    ) >= new Date()
                                                "
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-100 text-emerald-700 text-xs font-bold"
                                            >
                                                🟢 Đang diễn ra
                                            </span>

                                            <!-- UPCOMING -->
                                            <span
                                                v-else-if="
                                                    new Date(item.NgayBatDau) >
                                                    new Date()
                                                "
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-blue-100 text-blue-700 text-xs font-bold"
                                            >
                                                🔵 Sắp diễn ra
                                            </span>

                                            <!-- ENDED -->
                                            <span
                                                v-else
                                                class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-200 text-slate-700 text-xs font-bold"
                                            >
                                                ⚫ Đã kết thúc
                                            </span>
                                        </td>

                                        <!-- ACTION -->
                                        <td class="px-3 py-4 text-center">
                                            <div
                                                class="flex items-center justify-center gap-2"
                                            >
                                                <!-- EDIT -->
                                                <button
                                                    @click="
                                                        openTimeEditForm(item)
                                                    "
                                                    class="px-3 py-2 rounded-xl bg-blue-500 hover:bg-blue-600 text-white text-[11px] font-semibold shadow transition-all"
                                                >
                                                    Sửa
                                                </button>

                                                <!-- DELETE -->
                                                <button
                                                    @click="
                                                        deleteEvent(item.id)
                                                    "
                                                    class="px-3 py-2 rounded-xl bg-red-500 hover:bg-red-600 text-white text-[11px] font-semibold shadow transition-all"
                                                >
                                                    Xóa
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-between px-6 py-4 border-t border-slate-100 bg-slate-50"
                        >
                            <p class="text-xs text-slate-500">
                                Tổng:
                                {{ timeAllocations.length }}
                                sự kiện
                            </p>

                            <!-- PAGINATION -->
                            <div class="flex items-center gap-2">
                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    ←
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl bg-indigo-600 text-white font-semibold text-sm"
                                >
                                    1
                                </button>

                                <button
                                    class="w-9 h-9 rounded-xl border border-slate-200 bg-white hover:bg-slate-100 transition text-sm"
                                >
                                    →
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- MODAL: SỬA SỰ KIỆN -->
                <!-- ========================================================= -->
                <div
                    v-if="showEditModal"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-2xl overflow-hidden shadow-2xl border border-slate-200"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">Sửa sự kiện</h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    Cập nhật thông tin phân bổ thời gian
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="showEditModal = false"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="p-6 bg-slate-50 space-y-5">
                            <!-- EVENT NAME -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 p-5"
                            >
                                <label
                                    class="block text-sm font-semibold text-slate-700 mb-3"
                                >
                                    Tên sự kiện
                                </label>

                                <input
                                    type="text"
                                    v-model="editEvent.TenSuKien"
                                    placeholder="Nhập tên sự kiện..."
                                    class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                />
                            </div>

                            <!-- TIME -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- START -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5"
                                >
                                    <label
                                        class="block text-sm font-semibold text-slate-700 mb-3"
                                    >
                                        Ngày bắt đầu
                                    </label>

                                    <input
                                        type="datetime-local"
                                        v-model="editEvent.NgayBatDau"
                                        class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                    />
                                </div>

                                <!-- END -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5"
                                >
                                    <label
                                        class="block text-sm font-semibold text-slate-700 mb-3"
                                    >
                                        Ngày kết thúc
                                    </label>

                                    <input
                                        type="datetime-local"
                                        v-model="editEvent.NgayKetThuc"
                                        class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-end gap-3 px-8 py-5 border-t border-slate-200 bg-white"
                        >
                            <!-- CANCEL -->
                            <button
                                @click="showEditModal = false"
                                class="h-11 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition"
                            >
                                Hủy
                            </button>

                            <!-- SAVE -->
                            <button
                                @click="saveEdit()"
                                class="h-11 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                ✔ Lưu thay đổi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- ========================================================= -->
                <!-- MODAL: THÊM SỰ KIỆN -->
                <!-- ========================================================= -->
                <div
                    v-if="showAddTimeModal"
                    class="fixed inset-0 z-[70] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4"
                >
                    <!-- MODAL -->
                    <div
                        class="bg-white rounded-[32px] w-full max-w-2xl overflow-hidden shadow-2xl border border-slate-200"
                    >
                        <!-- HEADER -->
                        <div
                            class="flex items-center justify-between px-8 py-6 border-b border-slate-200 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white"
                        >
                            <!-- LEFT -->
                            <div>
                                <h3 class="text-2xl font-bold">
                                    Thêm sự kiện mới
                                </h3>

                                <p class="text-indigo-100 text-sm mt-1">
                                    Tạo mốc thời gian mới cho luận văn
                                </p>
                            </div>

                            <!-- CLOSE -->
                            <button
                                @click="showAddTimeModal = false"
                                class="w-11 h-11 rounded-2xl bg-white/20 hover:bg-white/30 transition flex items-center justify-center"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- BODY -->
                        <div class="p-6 bg-slate-50 space-y-5">
                            <!-- EVENT -->
                            <div
                                class="bg-white rounded-[24px] border border-slate-200 p-5"
                            >
                                <label
                                    class="block text-sm font-semibold text-slate-700 mb-3"
                                >
                                    Tên sự kiện
                                </label>

                                <input
                                    type="text"
                                    v-model="newEvent.TenSuKien"
                                    placeholder="Nhập tên sự kiện..."
                                    class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                />
                            </div>

                            <!-- TIME -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <!-- START -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5"
                                >
                                    <label
                                        class="block text-sm font-semibold text-slate-700 mb-3"
                                    >
                                        Ngày bắt đầu
                                    </label>

                                    <input
                                        type="datetime-local"
                                        v-model="newEvent.NgayBatDau"
                                        class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                    />
                                </div>

                                <!-- END -->
                                <div
                                    class="bg-white rounded-[24px] border border-slate-200 p-5"
                                >
                                    <label
                                        class="block text-sm font-semibold text-slate-700 mb-3"
                                    >
                                        Ngày kết thúc
                                    </label>

                                    <input
                                        type="datetime-local"
                                        v-model="newEvent.NgayKetThuc"
                                        class="w-full h-12 rounded-2xl border border-slate-200 bg-white px-4 text-sm outline-none focus:ring-4 focus:ring-indigo-100 focus:border-indigo-400"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- FOOTER -->
                        <div
                            class="flex items-center justify-end gap-3 px-8 py-5 border-t border-slate-200 bg-white"
                        >
                            <!-- CANCEL -->
                            <button
                                @click="showAddTimeModal = false"
                                class="h-11 px-5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold transition"
                            >
                                Hủy
                            </button>

                            <!-- SAVE -->
                            <button
                                @click="addEvent()"
                                class="h-11 px-6 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white text-sm font-semibold shadow transition-all"
                            >
                                ✔ Thêm sự kiện
                            </button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from "vue";
import { router } from "@inertiajs/vue3";
import axios from "axios";

const showNotifications = ref(false);

function toggleNotifications() {
    showNotifications.value = !showNotifications.value;
    if (showNotifications.value) showMenu.value = false; // Đóng menu profile nếu đang mở
}

function markAsRead(id) {
    router.post(
        `/notifications/${id}/mark-as-read`,
        {},
        {
            preserveScroll: true,
            onSuccess: () => {},
        },
    );
}

const showMenu = ref(false);
function toggleMenu() {
    showMenu.value = !showMenu.value;
}
function goProfile() {
    router.visit("/profile");
}
function logout() {
    router.post(route("logout"));
}

const pageProps = defineProps({
    user: { type: Object, default: () => ({ name: "User" }) },
    students: { type: Array, default: () => [] },
});
const user = pageProps.user;
const emit = defineEmits(["assign-committee", "remove-assignment"]);

axios.defaults.withCredentials = true;
axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/** ========================== */
/**  CONSTANTS - CHỨC VỤ HĐ   */
/** ========================== */
const POSITION_OPTIONS = [
    { value: "chu_tich", label: "Chủ tịch hội đồng", unique: true },
    { value: "thu_ky", label: "Thư ký", unique: true },
    { value: "uy_vien", label: "Ủy viên", unique: false }, // cái "t3" có thể trùng
];

/** ========================== */
/**  THỜI GIAN (PHÂN BỔ)      */
/** ========================== */
const timeAllocations = ref([]);

const showAddTimeModal = ref(false);
const newEvent = ref({
    TenSuKien: "",
    NgayBatDau: "",
    NgayKetThuc: "",
});

const showEditModal = ref(false);
const editEvent = ref({
    id: null,
    TenSuKien: "",
    NgayBatDau: "",
    NgayKetThuc: "",
});

const formatDateTime = (dateString) => {
    if (!dateString) return "";
    const date = new Date(
        typeof dateString === "string"
            ? dateString.replace(" ", "T")
            : dateString,
    );

    if (Number.isNaN(date.getTime())) return "";

    const dd = String(date.getDate()).padStart(2, "0");
    const MM = String(date.getMonth() + 1).padStart(2, "0");
    const yyyy = date.getFullYear();

    const hh = String(date.getHours()).padStart(2, "0");
    const mm = String(date.getMinutes()).padStart(2, "0");
    const ss = String(date.getSeconds()).padStart(2, "0");

    return `${dd}/${MM}/${yyyy} ${hh}:${mm}:${ss}`;
};

const loadTimeAllocations = async () => {
    try {
        const response = await axios.get("/thoi-gian");
        timeAllocations.value = response.data;
    } catch (error) {
        console.error("Failed to load time allocations:", error);
    }
};

const addEvent = async () => {
    if (
        !newEvent.value.TenSuKien ||
        !newEvent.value.NgayBatDau ||
        !newEvent.value.NgayKetThuc
    ) {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return;
    }

    try {
        await axios.post("/thoi-gian", newEvent.value);
        newEvent.value = { TenSuKien: "", NgayBatDau: "", NgayKetThuc: "" };
        showAddTimeModal.value = false;
        await loadTimeAllocations();
    } catch (error) {
        console.error("Failed to add event:", error);
    }
};

const openTimeEditForm = (item) => {
    editEvent.value = { ...item };
    showEditModal.value = true;
};

const saveEdit = async () => {
    if (
        !editEvent.value.TenSuKien ||
        !editEvent.value.NgayBatDau ||
        !editEvent.value.NgayKetThuc
    ) {
        alert("Vui lòng nhập đầy đủ thông tin!");
        return;
    }

    try {
        await axios.put(`/thoi-gian/${editEvent.value.id}`, {
            TenSuKien: editEvent.value.TenSuKien,
            NgayBatDau: editEvent.value.NgayBatDau,
            NgayKetThuc: editEvent.value.NgayKetThuc,
        });
        showEditModal.value = false;
        await loadTimeAllocations();
    } catch (error) {
        console.error("Failed to save event:", error);
    }
};

const deleteEvent = async (id) => {
    if (!confirm("Bạn có chắc muốn xóa sự kiện này?")) return;

    try {
        await axios.delete(`/thoi-gian/${id}`);
        await loadTimeAllocations();
    } catch (error) {
        console.error("Failed to delete event:", error);
    }
};

/** ========================== */
/**  VIEW STATE                */
/** ========================== */
const currentView = ref("home");
function setCurrentView(view) {
    currentView.value = view;
}

/** ========================== */
/**  FORM GV/SV/ĐỀ TÀI         */
/** ========================== */
const showForm = ref(false);
const formMode = ref("add");

const formData = ref({
    teacher: {
        name: "",
        email: "",
        sdt: "",
    },
    student: {
        MSSV: "",
        Ho_va_Ten: "",
        email: "",
        sdt: "",
        Nhom: "",
    },
    topic: {
        MaDT: "",
        TenDeTai: "",
        MaGV: "",
        So_luong: 1,
    },
});

let editingId = ref(null);

function openAddForm() {
    formMode.value = "add";
    editingId.value = null;
    if (currentView.value === "teachers") {
        formData.value.teacher = { name: "", email: "", sdt: "" };
    } else if (currentView.value === "students") {
        formData.value.student = {
            MSSV: "",
            Ho_va_Ten: "",
            email: "",
            sdt: "",
            Nhom: "",
        };
    } else if (currentView.value === "topics") {
        formData.value.topic = { MaDT: "", TenDeTai: "", MaGV: "" };
    }
    showForm.value = true;
}

function groupIsFull(group) {
    return students.value.filter((s) => s.group == group).length >= 2;
}

async function updateItem() {
    try {
        if (currentView.value === "teachers") {
            await axios.put("/update-teacher/" + editingId.value, {
                Ho_va_Ten: formData.value.teacher.name,
                email: formData.value.teacher.email,
                sdt: formData.value.teacher.sdt,
            });

            fetchTeachers();
            return closeForm();
        }

        if (currentView.value === "students") {
            if (groupIsFull(formData.value.student.Nhom)) {
                alert("Nhóm này đã đủ 2 sinh viên!");
                return;
            }

            await axios.put("/update-student/" + editingId.value, {
                MSSV: formData.value.student.MSSV,
                Ho_va_Ten: formData.value.student.Ho_va_Ten,
                email: formData.value.student.email,
                sdt: formData.value.student.sdt,
                Nhom: formData.value.student.Nhom,
            });

            fetchStudents();
            return closeForm();
        }

        if (currentView.value === "topics") {
            await axios.put("/update-topic/" + editingId.value, {
                MaDT: formData.value.topic.MaDT,
                TenDeTai: formData.value.topic.TenDeTai,
                MaGV: formData.value.topic.MaGV,
            });

            fetchTopics();
            return closeForm();
        }
    } catch (error) {
        console.error(error.response?.data || error);
        alert("Lỗi khi cập nhật dữ liệu!");
    }
}

async function deleteItem(id) {
    try {
        if (currentView.value === "teachers") {
            await axios.post("/delete-teacher/" + id);
            fetchTeachers();
        } else if (currentView.value === "students") {
            await axios.post("/delete-student/" + id);
            fetchStudents();
        } else if (currentView.value === "topics") {
            await axios.post("/delete-topic/" + id);
            fetchTopics();
        }
    } catch (error) {
        console.error(error);
        alert("Lỗi khi xóa dữ liệu!");
    }
}

async function addItem() {
    try {
        if (currentView.value === "teachers") {
            await axios.post("/add-teacher", formData.value.teacher);
            fetchTeachers();
            return closeForm();
        }

        if (currentView.value === "students") {
            await fetchStudents();
            const group = formData.value.student.Nhom;

            if (groupIsFull(group)) {
                alert(`Nhóm ${group} đã đủ 2 sinh viên!`);
                return;
            }

            await axios.post("/add-student", formData.value.student);
            await fetchStudents();
            return closeForm();
        }

        if (currentView.value === "topics") {
            await axios.post("/add-topic", formData.value.topic);
            fetchTopics();
            return closeForm();
        }
    } catch (error) {
        console.error(error);
        alert("Lỗi khi thêm dữ liệu!");
    }
}

function openEditForm(item) {
    formMode.value = "edit";
    showForm.value = true;
    if (currentView.value === "teachers") {
        formData.value.teacher = {
            name: item.name || "",
            email: item.email || "",
            sdt: item.sdt || "",
        };
        editingId.value = item.MaGV || item.id || null;
    } else if (currentView.value === "students") {
        formData.value.student = {
            MSSV: item.mssv || "",
            Ho_va_Ten: item.name || "",
            email: item.email || "",
            sdt: item.phone || "",
            Nhom: item.group || "",
        };
        editingId.value = item.mssv || item.id || null;
    } else if (currentView.value === "topics") {
        formData.value.topic = {
            MaDT: item.MaDT || "",
            TenDeTai: item.TenDeTai || "",
            MaGV: item.MaGV || "",
        };
        editingId.value = item.MaDT || null;
    }
}

function closeForm() {
    showForm.value = false;
}

function getFormTitle() {
    const titles = {
        assignments: "BẢNG PHÂN CÔNG",
        teachers: "GIẢNG VIÊN",
        students: "SINH VIÊN",
        topics: "ĐỀ TÀI",
    };
    return titles[currentView.value] || "";
}

/** ========================== */
/**  DATA HOLDERS              */
/** ========================== */
const assignments = ref([]);
const students = ref([]);
const teachers = ref([]);
const topics = ref([]);
const svpc = ref([]);

// hội đồng
const committees = ref([]);

const groupedAndSortedCommittees = computed(() => {
    if (!committees.value || committees.value.length === 0) return [];

    const now = new Date().getTime();

    // 1. Sắp xếp mảng phẳng theo khoảng cách thời gian gần với hiện tại nhất
    const sorted = [...committees.value].sort((a, b) => {
        // Chuyển đổi an toàn chuỗi ngày giờ từ Backend sang timestamp
        const timeA = new Date((a.start || "").replace(" ", "T")).getTime();
        const timeB = new Date((b.start || "").replace(" ", "T")).getTime();

        // Tính khoảng cách tuyệt đối so với thời điểm hiện tại
        const diffA = isNaN(timeA) ? Infinity : Math.abs(timeA - now);
        const diffB = isNaN(timeB) ? Infinity : Math.abs(timeB - now);

        return diffA - diffB;
    });

    // 2. Nhóm các hội đồng theo chuỗi ngày (bỏ qua giờ/phút)
    const groupsMap = new Map();
    sorted.forEach((c) => {
        // formatDateTime() trả về "DD/MM/YYYY HH:mm:ss", ta split lấy phần "DD/MM/YYYY"
        const dateStr = c.start
            ? formatDateTime(c.start).split(" ")[0]
            : "Chưa xác định";

        if (!groupsMap.has(dateStr)) {
            groupsMap.set(dateStr, []);
        }
        groupsMap.get(dateStr).push(c);
    });

    // 3. Chuyển Map thành mảng để render ra giao diện
    const result = [];
    groupsMap.forEach((items, date) => {
        result.push({
            date: date,
            committees: items,
        });
    });

    return result;
});

/** ========================== */
/**  SEARCH / STATS            */
/** ========================== */
const assignmentSearch = ref("");
const teacherSearch = ref("");
const studentSearch = ref("");
const topicSearch = ref("");
const evaluationSearch = ref("");
// Sắp xếp theo tên GV
const sortColumn = ref("");
const sortDirection = ref("asc");

const handleSort = (column) => {
    if (sortColumn.value === column) {
        sortDirection.value = sortDirection.value === "asc" ? "desc" : "asc";
    } else {
        sortColumn.value = column;
        sortDirection.value = "asc";
    }
};
const totalStudents = ref(0);
const totalTeachers = ref(0);
const totalTopics = ref(0);

const svDaCoGV = ref(0);
const svChuaCoGV = ref(0);

const dtTiepTuc = ref(0);
const dtDinhChi = ref(0);
const dtXinHoan = ref(0);

const filteredStudent = computed(() => {
    if (!evaluationSearch.value) return students.value;

    const searchTerm = evaluationSearch.value.toLowerCase();
    return students.value.filter(
        (s) =>
            s.mssv.toLowerCase().includes(searchTerm) ||
            s.name.toLowerCase().includes(searchTerm) ||
            (s.lecturer && s.lecturer.toLowerCase().includes(searchTerm)),
    );
});

const filteredAssignments = computed(() => {
    // 1. Lấy dữ liệu gốc
    let result = assignments.value;

    // 2. Logic Tìm kiếm (Search)
    if (assignmentSearch.value) {
        const searchTerm = assignmentSearch.value.toLowerCase();
        result = result.filter(
            (item) =>
                item.mssv.toLowerCase().includes(searchTerm) ||
                item.name.toLowerCase().includes(searchTerm) ||
                (item.group && item.group.toLowerCase().includes(searchTerm)) ||
                (item.topic && item.topic.toLowerCase().includes(searchTerm)) ||
                (item.lecturer &&
                    item.lecturer.toLowerCase().includes(searchTerm)) ||
                (item.note && item.note.toLowerCase().includes(searchTerm)),
        );
    }

    // 3. Logic Sắp xếp (Sort)
    if (sortColumn.value === "lecturer") {
        // Dùng [...result] để copy mảng, tránh báo lỗi mutate computed array của Vue
        result = [...result].sort((a, b) => {
            // Đảm bảo không bị lỗi nếu lecturer bị null/undefined
            const valA = a.lecturer || "";
            const valB = b.lecturer || "";

            // So sánh tiếng Việt chuẩn
            let comparison = valA.localeCompare(valB, "vi");
            return sortDirection.value === "asc" ? comparison : -comparison;
        });
    }

    return result;
});

const filteredTopics = computed(() => {
    if (!topicSearch.value) return topics.value;

    const searchTerm = topicSearch.value.toLowerCase();
    return topics.value.filter(
        (t) =>
            t.MaDT.toLowerCase().includes(searchTerm) ||
            t.TenDeTai.toLowerCase().includes(searchTerm) ||
            t.GiangVien.toLowerCase().includes(searchTerm) ||
            t.TrangThai.toLowerCase().includes(searchTerm),
    );
});

const filteredStudents = computed(() => {
    if (!studentSearch.value) return students.value;

    const searchTerm = studentSearch.value.toLowerCase();
    return students.value.filter(
        (s) =>
            s.mssv.toLowerCase().includes(searchTerm) ||
            s.name.toLowerCase().includes(searchTerm) ||
            (s.group && s.group.toLowerCase().includes(searchTerm)) ||
            (s.email && s.email.toLowerCase().includes(searchTerm)) ||
            (s.phone && s.phone.toLowerCase().includes(searchTerm)),
    );
});

const filteredTeachers = computed(() => {
    if (!teacherSearch.value) return teachers.value;

    const searchTerm = teacherSearch.value.toLowerCase();
    return teachers.value.filter(
        (t) =>
            t.MaGV.toLowerCase().includes(searchTerm) ||
            t.name.toLowerCase().includes(searchTerm) ||
            t.email.toLowerCase().includes(searchTerm) ||
            t.sdt.toLowerCase().includes(searchTerm),
    );
});

const fetchStats = async () => {
    try {
        const res = await axios.get("/stats");
        totalStudents.value = Number(res.data.students ?? 0);
        totalTeachers.value = Number(res.data.teachers ?? 0);
        totalTopics.value = Number(res.data.topics ?? 0);
    } catch (err) {
        console.error("Error fetching stats", err);
    }
};

/** ========================== */
/**  FETCH TOPICS / TEACHERS   */
/** ========================== */
const fetchTopics = async () => {
    try {
        const response = await axios.post("/topics/getAll");
        topics.value = response.data;
    } catch (error) {
        console.error("Error fetching topics:", error);
    }
};

const fetchTeachers = async () => {
    try {
        const response = await axios.post("/teachers/getAll");
        teachers.value = response.data;
    } catch (error) {
        console.error("Error fetching teachers:", error);
    }
};

const fetchStudents = async () => {
    try {
        const response = await axios.post("/students/getAll");

        students.value = response.data.sort(
            (a, b) => Number(a.group) - Number(b.group),
        );

        assignments.value = normalizeStudents(students.value);
    } catch (error) {
        console.error("Error fetching students:", error);
    }
};

function normalizeStudents(studentsArr) {
    return (studentsArr || []).map((s) => ({
        mssv: s.mssv ?? s.MSSV ?? "",
        name: s.name ?? s.Ho_va_Ten ?? s.HoTen ?? "",
        diem50: s.Diem ?? s.diem50 ?? null,
        MaDT: s.MaDT,
        MaHD: s.committee,
        group: s.group ?? s.Nhom ?? "",
        topic: s.topic ?? s.TenDeTai ?? "",
        lecturer: s.lecturer ?? s.Giang_vien_hd ?? "",
        status: s.status ?? "Chưa gặp",
        note: s.note ?? "",
    }));
}

/** ========================== */
/**  EXPORT / IMPORT EXCEL     */
/** ========================== */
function exportExcel() {
    window.open(route("students.export"), "_blank");
}

function exportEvaluation50() {
    window.open(route("evaluation50.export"), "_blank");
}

function exportReviewAssignment() {
    window.open(route("reviewAssignment.export"), "_blank");
}

function exportCommitteeExcel() {
    window.open(route("committeeAssignment.export"), "_blank");
}

function exportCommittee() {
    window.open(route("committees.export"), "_blank");
}

const handleExcelImport = async (e) => {
    const file = e.target.files[0];
    if (!file) {
        alert("Không có file được chọn!");
        return;
    }

    const formDataUpload = new FormData();
    formDataUpload.append("file", file);

    try {
        const res = await axios.post("/import-temp", formDataUpload, {
            headers: { "Content-Type": "multipart/form-data" },
        });
        alert(res.data.message || "Import thành công!");
    } catch (err) {
        console.error("Upload error:", err.response?.data || err.message);
        alert("Import thất bại! Kiểm tra định dạng file.");
    }
};

/** ========================== */
/**  PHÂN CÔNG GV HƯỚNG DẪN    */
/** ========================== */
const showAssignModal = ref(false);
const showStudentModal = ref(false);
const selectedLecturer = ref(null);

function openAssignPanel(item) {
    showAssignModal.value = true;
    svpc.value = item;
}

function closeAssignPanel() {
    showAssignModal.value = false;
    closeStudentModal();
}

function openStudentModal(lecturer) {
    selectedLecturer.value = lecturer;
    showStudentModal.value = true;
}

function closeStudentModal() {
    selectedLecturer.value = null;
    showStudentModal.value = false;
}

function countAssignedTo(lecturer) {
    if (!lecturer) return 0;
    const id = lecturer.MaGV ?? lecturer.id ?? lecturer.name;
    return (
        students.value?.filter((student) => {
            const assigned = (
                student.lecturer ??
                student.Giang_vien_hd ??
                student.lecturer_name ??
                ""
            ).toString();
            return assigned === id || assigned === (lecturer.name ?? "");
        }).length || 0
    );
}

function studentsAssignedTo(lecturer) {
    if (!lecturer) return [];
    const id = lecturer.MaGV ?? lecturer.id ?? lecturer.name;
    return (
        students.value?.filter((student) => {
            const assigned = (
                student.lecturer ??
                student.Giang_vien_hd ??
                student.lecturer_name ??
                ""
            ).toString();
            return assigned === id || assigned === (lecturer.name ?? "");
        }) || []
    );
}

async function confirmAllAssignments(lecturer) {
    if (!lecturer || !students.value) return;

    const lecturerId = lecturer.MaGV ?? lecturer.id ?? lecturer.name;
    const lecturerName = lecturer.name ?? lecturer.HoTen ?? "giảng viên";

    const confirmed = window.confirm(
        `Bạn có chắc muốn phân công sinh viên ${svpc.value.name} cho giảng viên ${lecturerName}?`,
    );

    if (confirmed) {
        await axios.put(`/assign-students/${svpc.value.mssv}`, {
            Giang_vien_huong_dan: lecturerId,
        });
        alert(
            ` Đã phân công sinh viên ${svpc.value.name} cho giảng viên ${lecturerName}`,
        );
        fetchStudents();
        fetchTeachers();
        closeAssignPanel();
    }
}

/** ========================== */
/**  PHÂN CÔNG HỘI ĐỒNG       */
/** ========================== */
const committeeAssignSearch = ref("");
const showCommitteeAssignModal = ref(false);
const selectedAssignment = ref(null);
const hoveredGroup = ref(null);

const filteredCommitteeAssignRows = computed(() => {
    const q = (committeeAssignSearch.value || "")
        .toString()
        .toLowerCase()
        .trim();

    const rows = (assignments.value || []).map((a) => ({
        mssv: a.mssv || a.MSSV || "",
        name: a.name || a.Ho_va_Ten || "",
        MaDT: a.MaDT || a.Ma_de_tai || "",
        MaHD: a.MaHD || a.HoiDong?.MaHD || null,
        topic: (a.topic || a.TenDeTai || "").toString(),
        committee: a.HoiDong || a.committee || null,
    }));

    const groupsMap = new Map();

    rows.forEach((r) => {
        const key =
            r.MaDT && r.MaDT.toString().trim()
                ? `MaDT:${r.MaDT}`
                : `__no_madt__:${r.mssv}`;

        if (!groupsMap.has(key)) {
            groupsMap.set(key, {
                MaDT: r.MaDT,
                MaHD: r.MaHD,
                topic: r.topic,
                students: [],
                committee: r.committee || null,
            });
        }

        const group = groupsMap.get(key);

        group.students.push({
            mssv: r.mssv,
            name: r.name,
            diem50: r.diem50,
        });

        if (!group.MaHD && r.MaHD) {
            group.MaHD = r.MaHD;
        }

        if (!group.committee && r.committee) {
            group.committee = r.committee;
        }
    });

    let groups = Array.from(groupsMap.values()).map((g) => ({
        MaDT: g.MaDT,
        MaHD: g.MaHD,
        topic: g.topic,
        students: g.students,
        committee: g.committee,
        rowSpan: g.students.length || 1,
    }));

    if (q) {
        groups = groups.filter((g) => {
            const text = `
        ${g.MaDT}
        ${g.topic || ""}
        ${g.students.map((s) => `${s.mssv} ${s.name}`).join(" ")}
      `.toLowerCase();

            return text.includes(q);
        });
    }

    return groups;
});

function openCommitteeAssign(item) {
    selectedAssignment.value = item;
    showCommitteeAssignModal.value = true;
}

function closeCommitteeAssignment() {
    selectedAssignment.value = null;
    showCommitteeAssignModal.value = false;
}

async function assignCommitteeTo(c) {
    if (!selectedAssignment.value || !c) return;

    const studentNames = (selectedAssignment.value.students || [])
        .map((s) => s.name)
        .filter(Boolean)
        .join(", ");

    const confirmed = window.confirm(
        `Phân công hội đồng "${c.id}" cho đề tài "${selectedAssignment.value.topic}" của ${studentNames}?`,
    );
    if (!confirmed) return;
    console.log(selectedAssignment.value, c);
    try {
        await axios.post("/committees/assign", {
            MaDT: selectedAssignment.value.MaDT,
            MaHD: c.id,
        });

        selectedAssignment.value.committee = c;

        alert("Phân công hội đồng thành công");
        closeCommitteeAssignment();
        fetchStudents();
        fetchTopics();
        fetchCommittees();
    } catch (err) {
        console.error(err);
        alert("Phân công thất bại");
    }
}

function removeGroupAssignment(group) {
    if (!group) return;
    const confirmed = window.confirm(
        `Xóa phân công cho đề tài "${group.topic || ""}"?`,
    );
    if (!confirmed) return;

    emit("remove-assignment", { topic: group.topic, students: group.students });

    for (let i = assignments.value.length - 1; i >= 0; i--) {
        const a = assignments.value[i];
        const aTopic = (a.topic || a.TenDeTai || a.title || "").toString();
        if (aTopic === (group.topic || "")) assignments.value.splice(i, 1);
    }
}

const reviewSearch = ref("");
const showReviewerModal = ref(false);
const selectedTopic = ref(null);

const showReviewTopicModal = ref(false);
const selectedReviewer = ref(null);

const hoverStt = ref(null);

function openReviewerTopicList(reviewer) {
    selectedReviewer.value = reviewer;
    showReviewTopicModal.value = true;
}

function closeReviewerTopicList() {
    selectedReviewer.value = null;
    showReviewTopicModal.value = false;
}

const reviewTopics = computed(() => {
    const q = (reviewSearch.value || "").toString().toLowerCase().trim();

    return (topics.value || [])
        .map((t) => ({
            ...t,
            reviewerName:
                t.reviewerName ??
                t.reviewer_name ??
                (t.reviewer ? findTeacherName(t.reviewer) : ""),
        }))
        .filter((t) => {
            if (!q) return true;
            const text =
                `${t.MaDT || ""} ${t.TenDeTai || t.title || ""}`.toLowerCase();
            return text.includes(q);
        });
});

const reviewAssignmentRows = computed(() => {
    const result = [];
    let sttCounter = 0;

    const topicList = reviewTopics.value || [];
    const allStudents = students.value || [];

    const norm = (v) => (v ?? "").toString().toLowerCase().trim();

    const topicMap = new Map();
    topicList.forEach((topic, idx) => {
        const maDT = (topic.MaDT || topic.maDT || topic.ma_dt || "")
            .toString()
            .trim();
        const nhom = norm(topic.Nhom || topic.group);

        let key;
        if (maDT !== "") {
            key = `MADT::${maDT}`;
        } else if (nhom !== "") {
            key = `NHOM::${nhom}`;
        } else {
            key = `UNIQ::${idx}`;
        }

        if (!topicMap.has(key)) {
            topicMap.set(key, { key, topic });
        }
    });

    for (const { key, topic } of topicMap.values()) {
        const topicMaDT = (topic.MaDT || topic.maDT || topic.ma_dt || "")
            .toString()
            .trim();
        const topicNhom = norm(topic.Nhom || topic.group);

        const matchedStudents = allStudents
            .filter((s) => {
                const sMaDT = (s.MaDT || s.maDT || s.ma_dt || "")
                    .toString()
                    .trim();
                const sNhom = norm(s.Nhom || s.group);

                if (topicMaDT && sMaDT && topicMaDT === sMaDT) return true;
                if (topicNhom && sNhom && topicNhom === sNhom) return true;

                return false;
            })
            .map((s) => ({
                mssv: s.mssv || s.MSSV || "",
                name: s.name || s.Ho_va_Ten || s.HoTen || "",
            }));

        const stuArr = matchedStudents.length
            ? matchedStudents
            : [{ mssv: "", name: "" }];

        sttCounter += 1;
        const rowSpan = stuArr.length;

        let first = true;
        for (const stu of stuArr) {
            result.push({
                topic,
                key,
                isFirst: first,
                rowSpan,
                stt: sttCounter,
                mssv: stu.mssv,
                name: stu.name,
            });
            first = false;
        }
    }
    console.log(allStudents);

    return result;
});

function reviewTopicsAssignedTo(reviewer) {
    if (!reviewer) return [];
    const id = (reviewer.MaGV ?? reviewer.id ?? reviewer.name).toString();
    return (
        (topics.value || []).filter((t) => {
            const rev = (
                t.MaGVPB ??
                t.reviewer_id ??
                t.reviewerName ??
                ""
            ).toString();
            return rev === id || rev === (reviewer.name ?? "");
        }) || []
    );
}

function openReviewerList(topic) {
    selectedTopic.value = topic;
    showReviewerModal.value = true;
}

function closeReviewerModal() {
    showReviewerModal.value = false;
    selectedTopic.value = null;
}

function countReviewAssigned(gv) {
    if (!gv) return 0;
    const id = gv.MaGV ?? gv.id ?? gv.name;
    return (topics.value || []).filter((t) => {
        const reviewer = (t.MaGVPB ?? t.reviewer_id ?? "").toString();
        return reviewer === id || reviewer === (gv.name ?? "");
    }).length;
}

function findTeacherName(idOrName) {
    if (!idOrName) return "";
    const found = (teachers.value || []).find((gv) => {
        const id = (gv.MaGV ?? gv.id ?? "").toString();
        return (
            id === idOrName.toString() ||
            (gv.name || gv.HoTen || "").toString() === idOrName.toString()
        );
    });
    return found ? (found.name ?? found.HoTen ?? "") : "";
}

const availableReviewers = computed(() => {
    if (!selectedTopic.value) return teachers.value || [];
    const advisorId = (
        selectedTopic.value.MaGV ??
        selectedTopic.value.advisor_id ??
        ""
    )
        .toString()
        .trim();
    return (teachers.value || []).filter((gv) => {
        const gvId = (gv.MaGV ?? gv.id ?? "").toString().trim();
        return gvId.length > 0 && gvId !== advisorId;
    });
});

function deleteTopicReviewer(topic) {
    if (!topic) return;
    const confirmed = window.confirm(
        `Xóa thông tin phản biện của đề tài "${topic.TenDeTai || topic.MaDT}" ?`,
    );
    if (!confirmed) return;
    axios
        .post(`/remove-reviewer/${topic.MaDT}`)
        .then(() => {
            fetchTopics();
        })
        .catch(() => {
            const idx = topics.value.findIndex((t) => t.MaDT === topic.MaDT);
            if (idx !== -1) {
                topics.value[idx].reviewer = "";
                topics.value[idx].reviewerName = "";
            }
        });
}

async function confirmReviewAssignment(gv) {
    if (!gv || !selectedTopic.value) return;
    const reviewerId = gv.MaGV ?? gv.id ?? gv.name;
    const confirmed = window.confirm(
        `Phân công đề tài "${
            selectedTopic.value.TenDeTai || selectedTopic.value.MaDT
        }" cho phản biện viên ${gv.name || gv.HoTen || reviewerId}?`,
    );
    if (!confirmed) return;
    try {
        await axios.put(`/assign-reviewer/${selectedTopic.value.MaDT}`, {
            MaGVPB: reviewerId,
        });
        fetchTopics();
        closeReviewerModal();
    } catch (err) {
        console.error("Lỗi phân công phản biện:", err);
        alert("Lỗi khi phân công phản biện ");
    }
}

/** ========================== */
/**  THÀNH LẬP HỘI ĐỒNG        */
/** ========================== */

// Modal form hội đồng
const showCommitteeForm = ref(false);
const committeeFormMode = ref("add");
const editingCommitteeId = ref(null);

// Modal chi tiết hội đồng
const showCommitteeDetailModal = ref(false);
const selectedCommittee = ref(null);

// Form data
const committeeForm = ref({
    members: [
        { teacherId: "", position: "" },
        { teacherId: "", position: "" },
        { teacherId: "", position: "" },
        { teacherId: "", position: "" },
    ],
    start: "",
    end: "",
});

// Chuẩn hóa dữ liệu hội đồng từ backend
function normalizeCommittees(data) {
    // Laravel có thể trả Collection, object, null...
    if (!Array.isArray(data)) {
        console.error("Committees data is not array:", data);
        return [];
    }

    return data.map((c) => ({
        id: c.id ?? null,
        start: c.start ?? null,
        end: c.end ?? null,
        members: Array.isArray(c.members)
            ? c.members.map((m) => ({
                  teacherId: m.teacherId ?? "",
                  teacherName: m.teacherName ?? "",
                  position: m.position ?? "",
              }))
            : [],
    }));
}

const fetchCommittees = async () => {
    try {
        const res = await axios.get("/committees");
        committees.value = normalizeCommittees(res.data);
    } catch (err) {
        console.error("Lỗi load hội đồng:", err);
        committees.value = [];
    }
};

const committeeIndexMap = computed(() => {
    const map = new Map();
    committees.value.forEach((c, idx) => {
        map.set(c.id, idx);
    });
    return map;
});

function getCommitteeLabel(MaHD) {
    const idx = committeeIndexMap.value.get(MaHD);
    return idx !== undefined ? `Hội đồng ${idx + 1}` : "Chưa phân công";
}

function toDatetimeLocal(str) {
    if (!str) return "";
    const d = new Date(str.replace(" ", "T"));
    if (Number.isNaN(d.getTime())) return "";
    const yyyy = d.getFullYear();
    const MM = String(d.getMonth() + 1).padStart(2, "0");
    const dd = String(d.getDate()).padStart(2, "0");
    const hh = String(d.getHours()).padStart(2, "0");
    const mm = String(d.getMinutes()).padStart(2, "0");
    return `${yyyy}-${MM}-${dd}T${hh}:${mm}`;
}

// helpers cho GV & chức vụ
function getTeacherId(t) {
    return (t?.MaGV ?? t?.id ?? "").toString();
}

function getTeacherNameById(id) {
    if (!id) return "";
    const found = (teachers.value || []).find(
        (t) => getTeacherId(t) === id.toString(),
    );
    return found ? (found.name ?? found.HoTen ?? found.Ho_va_Ten ?? "") : "";
}

function positionLabel(val) {
    const found = POSITION_OPTIONS.find((p) => p.value === val);
    return found ? found.label : val || "";
}

// lấy list GV chưa được chọn ở dòng khác
function availableTeachersForRow(rowIndex) {
    const used = committeeForm.value.members
        .map((m, i) => (i === rowIndex ? null : m.teacherId))
        .filter(Boolean);

    return (teachers.value || []).filter(
        (t) => !used.includes(getTeacherId(t)),
    );
}

// lấy list chức vụ hợp lệ cho từng dòng
function availablePositionsForRow(rowIndex) {
    const current = committeeForm.value.members[rowIndex].position;

    const used = committeeForm.value.members
        .map((m, i) => (i === rowIndex ? null : m.position))
        .filter(Boolean);

    return POSITION_OPTIONS.filter((pos) => {
        if (!pos.unique) return true; // Ủy viên cho phép trùng

        // unique: nếu đã dùng ở dòng khác thì không cho chọn nữa
        if (current === pos.value) return true; // vẫn cho giữ chọn hiện tại
        return !used.includes(pos.value);
    });
}

function openCommitteeAddForm() {
    committeeFormMode.value = "add";
    editingCommitteeId.value = null;
    committeeForm.value = {
        members: [
            { teacherId: "", position: "" },
            { teacherId: "", position: "" },
            { teacherId: "", position: "" },
            { teacherId: "", position: "" },
        ],
        start: "",
        end: "",
    };
    showCommitteeForm.value = true;
}

function openCommitteeEditForm(c) {
    committeeFormMode.value = "edit";
    editingCommitteeId.value = c.id;

    const members = (c.members || []).map((m) => ({
        teacherId: m.teacherId,
        position: m.position,
    }));

    // đảm bảo đủ 4 dòng
    while (members.length < 4) {
        members.push({ teacherId: "", position: "" });
    }

    committeeForm.value = {
        members,
        start: toDatetimeLocal(c.start),
        end: toDatetimeLocal(c.end),
    };

    showCommitteeForm.value = true;
}

function closeCommitteeForm() {
    showCommitteeForm.value = false;
}

function openCommitteeDetail(c) {
    selectedCommittee.value = c;
    console.log(selectedCommittee.value);
    showCommitteeDetailModal.value = true;
}

async function deleteCommittee(c) {
    if (!c || !c.id) return;
    const ok = window.confirm("Bạn có chắc muốn xóa hội đồng này?");
    if (!ok) return;
    try {
        await axios.delete(`/committees/${c.id}`);
        await fetchCommittees();
    } catch (err) {
        console.error("Lỗi xóa hội đồng:", err);
        alert("Lỗi khi xóa hội đồng");
    }
}

// kiểm tra trùng lịch giảng viên
function checkTeacherScheduleConflict(
    members,
    startDate,
    endDate,
    ignoreId = null,
) {
    const teacherIds = members.map((m) => m.teacherId).filter(Boolean);
    if (teacherIds.length === 0) return false;

    const startMs = startDate.getTime();
    const endMs = endDate.getTime();

    return (committees.value || []).some((c) => {
        if (!c) return false;
        if (ignoreId != null && c.id == ignoreId) return false;

        const cStart = new Date(
            c.start && c.start.toString().replace(" ", "T"),
        ).getTime();
        const cEnd = new Date(
            c.end && c.end.toString().replace(" ", "T"),
        ).getTime();
        if (Number.isNaN(cStart) || Number.isNaN(cEnd)) return false;

        // kiểm tra có GV trùng
        const cTeacherIds = (c.members || [])
            .map((m) => m.teacherId)
            .filter(Boolean);
        const shareTeacher = cTeacherIds.some((id) => teacherIds.includes(id));
        if (!shareTeacher) return false;

        // kiểm tra khoảng thời gian bị overlap
        // overlap nếu: newStart < oldEnd && newEnd > oldStart
        return startMs < cEnd && endMs > cStart;
    });
}

async function submitCommitteeForm() {
    // lọc các dòng có dữ liệu
    const members = committeeForm.value.members.filter(
        (m) => m.teacherId && m.position,
    );

    if (members.length < 3) {
        alert("Vui lòng chọn ít nhất 3 giảng viên và chức vụ cho hội đồng.");
        return;
    }

    // không trùng giảng viên
    const teacherIds = members.map((m) => m.teacherId);
    const dupTeacher = teacherIds.find(
        (id, idx) => teacherIds.indexOf(id) !== idx,
    );
    if (dupTeacher) {
        alert("Không được chọn trùng giảng viên trong cùng một hội đồng.");
        return;
    }

    // đảm bảo mỗi chức vụ unique có đúng 1 người, t3 (Ủy viên) có thể trùng nhưng phải có ít nhất 1
    const uniquePositions = POSITION_OPTIONS.filter((p) => p.unique).map(
        (p) => p.value,
    );
    const nonUniquePositions = POSITION_OPTIONS.filter((p) => !p.unique).map(
        (p) => p.value,
    );

    for (const pos of uniquePositions) {
        const count = members.filter((m) => m.position === pos).length;
        if (count !== 1) {
            const label = positionLabel(pos);
            alert(`Chức vụ "${label}" phải có đúng 1 người.`);
            return;
        }
    }

    const hasNonUnique = members.some((m) =>
        nonUniquePositions.includes(m.position),
    );
    if (!hasNonUnique) {
        alert("Hội đồng phải có ít nhất 1 Ủy viên.");
        return;
    }

    // validate thời gian
    if (!committeeForm.value.start || !committeeForm.value.end) {
        alert("Vui lòng chọn thời gian bắt đầu và kết thúc.");
        return;
    }

    const startDate = new Date(committeeForm.value.start);
    const endDate = new Date(committeeForm.value.end);
    if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) {
        alert("Thời gian không hợp lệ.");
        return;
    }
    if (endDate <= startDate) {
        alert("Thời gian kết thúc phải sau thời gian bắt đầu.");
        return;
    }

    // kiểm tra trùng lịch với hội đồng khác
    const hasConflict = checkTeacherScheduleConflict(
        members,
        startDate,
        endDate,
        committeeFormMode.value === "edit" ? editingCommitteeId.value : null,
    );
    if (hasConflict) {
        alert(
            "Có giảng viên đã nằm trong hội đồng khác trùng khoảng thời gian này.",
        );
        return;
    }

    const payload = {
        start_time: committeeForm.value.start,
        end_time: committeeForm.value.end,
        members: members.map((m) => ({
            teacher_id: m.teacherId,
            position: m.position,
        })),
    };

    try {
        if (committeeFormMode.value === "add") {
            await axios.post("/committees", payload);
        } else {
            await axios.put(`/committees/${editingCommitteeId.value}`, payload);
        }
        await fetchCommittees();
        showCommitteeForm.value = false;
    } catch (err) {
        console.error("Lỗi lưu hội đồng:", err);
        alert("Lỗi khi lưu hội đồng. Vui lòng kiểm tra backend.");
    }
}

function onStudentSearchInput() {}

onMounted(() => {
    fetchStats();
    fetchTeachers();
    fetchTopics();
    fetchStudents();
    loadTimeAllocations();
    fetchCommittees();
    axios.get("/dashboard/stats").then((res) => {
        svDaCoGV.value = res.data.sinh_vien.da_co_gv;
        svChuaCoGV.value = res.data.sinh_vien.chua_co_gv;

        dtTiepTuc.value = res.data.de_tai.tiep_tuc;
        dtDinhChi.value = res.data.de_tai.dinh_chi;
        dtXinHoan.value = res.data.de_tai.xin_hoan;
        console.log(res.data);
    });
});

const collapsed = ref(false);

const menus = [
    {
        label: "Trang chủ",
        view: "home",
        icon: "🏠",
    },

    {
        label: "Phân bổ thời gian",
        view: "timeAllocation",
        icon: "📅",
    },

    {
        label: "Quản lý giảng viên",
        view: "teachers",
        icon: "👨‍🏫",
    },

    {
        label: "Quản lý sinh viên",
        view: "students",
        icon: "👨‍🎓",
    },

    {
        label: "Quản lý đề tài",
        view: "topics",
        icon: "📚",
    },

    {
        label: "Bảng phân công",
        view: "assignments",
        icon: "📋",
    },

    {
        label: "Đánh giá 50%",
        view: "evaluation50",
        icon: "📝",
    },

    {
        label: "Phân công phản biện",
        view: "reviewAssignment",
        icon: "🎯",
    },

    {
        label: "Thành lập hội đồng",
        view: "committee",
        icon: "🏛️",
    },

    {
        label: "Phân công hội đồng",
        view: "committeeAssignment",
        icon: "👥",
    },
];
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.2s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.tooltip-enter-active,
.tooltip-leave-active {
    transition: all 0.2s ease;
}

.tooltip-enter-from,
.tooltip-leave-to {
    opacity: 0;
    transform: translateY(-50%) translateX(-10px);
}
</style>
