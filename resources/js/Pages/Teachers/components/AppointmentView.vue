<template>
    <div class="relative p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-800 uppercase tracking-wide">
                Quản lý lịch gặp sinh viên
            </h2>

            <div class="flex items-center gap-4">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="Tìm mã hoặc tên đề tài..."
                    class="border border-gray-300 rounded px-3 py-2 text-sm w-64 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                />
                
                <button 
                    @click="openCreateModal" 
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium flex items-center gap-2 hover:bg-blue-700 transition-colors shadow-sm"
                >
                    <span class="text-lg leading-none">+</span> Tạo lịch gặp mới
                </button>
            </div>
        </div>

        <div class="flex gap-6 mb-4 px-1 border-b border-gray-200">
            <button 
                @click="activeTab = 'guide'"
                :class="activeTab === 'guide' 
                    ? 'text-indigo-700 font-bold border-b-2 border-indigo-700 pb-2 -mb-[1px]' 
                    : 'text-gray-500 hover:text-indigo-700 font-medium pb-2 transition-colors -mb-[1px]'"
            >
                [Lịch Hướng dẫn]
            </button>
            <button 
                @click="activeTab = 'review'"
                :class="activeTab === 'review' 
                    ? 'text-indigo-700 font-bold border-b-2 border-indigo-700 pb-2 -mb-[1px]' 
                    : 'text-gray-500 hover:text-indigo-700 font-medium pb-2 transition-colors -mb-[1px]'"
            >
                [Lịch Phản biện]
            </button>
        </div>

        <div class="bg-white rounded shadow overflow-x-auto border border-gray-200">
            <table class="min-w-full text-sm divide-y divide-gray-200">
                <thead class="bg-indigo-50 text-indigo-800">
                    <tr>
                        <th class="p-3 text-center font-semibold">STT</th>
                        <th class="p-3 text-center font-semibold">MSSV</th>
                        <th class="p-3 text-center font-semibold">Họ và tên sinh viên</th>
                        <th class="p-3 text-center font-semibold max-w-sm">Tên đề tài</th>
                        <th class="p-3 text-center font-semibold">Thời gian gặp</th>
                        <th class="p-3 text-center font-semibold">Địa điểm</th>
                        <th class="p-3 text-center font-semibold">Trạng thái</th>
                        <th class="p-3 text-center font-semibold">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <tr v-if="filteredSchedules.length === 0">
                        <td colspan="8" class="p-8 text-center text-gray-500">
                            Không có dữ liệu lịch gặp phù hợp.
                        </td>
                    </tr>
                    <tr v-for="(item, index) in filteredSchedules" :key="item.id" class="hover:bg-indigo-50 transition-colors">
                        <td class="p-3 text-center">{{ index + 1 }}</td>
                        <td class="p-3 text-center">{{ item.studentId }}</td>
                        <td class="p-3 text-center">{{ item.studentName }}</td>
                        <td class="p-3 text-center max-w-sm leading-relaxed">{{ item.topic }}</td>
                        <td class="p-3 text-center">
                            <template v-if="item.time">
                                <div class="font-bold text-gray-800">{{ item.time.hour }}</div>
                                <div class="text-gray-500 text-xs mt-0.5">{{ item.time.date }}</div>
                            </template>
                            <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="p-3 text-center">
                            <template v-if="item.location">
                                <div v-for="(line, i) in item.location" :key="i">{{ line }}</div>
                            </template>
                            <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="p-3 text-center">
                            <span v-if="item.status === 'confirmed'" class="bg-green-500 text-white px-2.5 py-1 rounded text-xs font-medium whitespace-nowrap">Đã xác nhận</span>
                            <span v-else-if="item.status === 'pending'" class="bg-yellow-500 text-white px-2.5 py-1 rounded text-xs font-medium whitespace-nowrap">Chờ xác nhận</span>
                            <span v-else class="text-gray-400">-</span>
                        </td>
                        <td class="p-3 text-center">
                            <div class="flex gap-2 justify-center" v-if="item.status !== 'none'">
                                <button @click="openDetailModal(item)" class="border border-gray-300 rounded bg-white p-1.5 text-gray-600 hover:text-indigo-600 hover:border-indigo-400 hover:bg-indigo-50 transition-colors shadow-sm" title="Xem chi tiết">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                </button>
                                <button @click="deleteSchedule(item.id)" class="border border-gray-300 rounded bg-white p-1.5 text-gray-600 hover:text-red-600 hover:border-red-400 hover:bg-red-50 transition-colors shadow-sm" title="Xóa">
                                    <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                            <button v-else @click="openCreateModalWithStudent(item)" class="bg-blue-500 text-white px-3 py-1.5 rounded text-sm hover:bg-blue-600 transition-colors shadow-sm whitespace-nowrap">
                                + Tạo lịch
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Modal Tạo Lịch Mới -->
        <div v-if="isCreateModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-xl shadow-2xl w-[600px] overflow-hidden flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-800 uppercase tracking-wide">TẠO LỊCH GẶP MỚI</h3>
                    <button @click="closeCreateModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="px-6 py-5 flex-1 overflow-y-auto space-y-4 text-sm">
                    <div class="flex flex-col gap-1">
                        <label class="font-medium text-gray-700">Chọn Sinh viên (MSSV / Họ và tên)</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            </span>
                            <select v-model="formSchedule.studentId" @change="autoFillTopic" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none appearance-none bg-white">
                                <option value="" disabled>Tìm mã hoặc tên sinh viên...</option>
                                <option v-for="student in studentList" :key="student.id" :value="student.id">
                                    {{ student.id }} - {{ student.name }}
                                </option>
                            </select>
                            <span class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-gray-400">
                                <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" stroke-width="2" fill="none"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </span>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-medium text-gray-700">Tên đề tài</label>
                        <input v-model="formSchedule.topic" type="text" class="w-full px-3 py-2 border border-blue-400 rounded focus:ring-1 focus:ring-blue-500 focus:border-blue-500 outline-none" />
                    </div>

                    <div class="flex flex-col gap-2 pt-1">
                        <label class="font-medium text-gray-700">Loại lịch hẹn</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" v-model="formSchedule.isGuide" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="text-gray-700">Lịch Hướng dẫn</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" v-model="formSchedule.isReview" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="text-gray-700">Lịch Phản biện</span>
                            </label>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Thời gian gặp</label>
                            <div class="flex gap-2">
                                <div class="relative w-3/5">
                                    <input type="date" v-model="formSchedule.date" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" />
                                </div>
                                <div class="relative w-2/5">
                                    <input type="time" v-model="formSchedule.time" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Địa điểm</label>
                            <select v-model="formSchedule.location" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                                <option value="Văn phòng Khoa">Văn phòng Khoa</option>
                                <option value="Thư viện">Thư viện</option>
                                <option value="Online (Zoom)">Online (Zoom)</option>
                                <option value="Online (Google Meet)">Online (Google Meet)</option>
                                <option value="Tùy chỉnh...">Tùy chỉnh...</option>
                            </select>
                        </div>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-medium text-gray-700">Ghi chú (Tùy chọn)</label>
                        <textarea v-model="formSchedule.note" rows="3" placeholder="Yêu cầu chuẩn bị báo cáo tiến độ..." class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                    <button @click="closeCreateModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded text-sm font-medium transition-colors">
                        Hủy
                    </button>
                    <button @click="saveSchedule" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition-colors">
                        Lưu lịch hẹn
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal Chi tiết -->
        <div v-if="isDetailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-xl shadow-2xl w-[600px] overflow-hidden flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-[#1e1b4b] uppercase tracking-wide">CHI TIẾT LỊCH GẶP</h3>
                    <button @click="closeDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="px-6 py-5 flex-1 overflow-y-auto text-sm text-gray-800" v-if="selectedMeeting">
                    <div class="mb-5">
                        <h4 class="font-semibold text-base mb-2">Thông tin sinh viên</h4>
                        <p class="text-gray-900">
                            <span class="font-medium">MSSV:</span> {{ selectedMeeting.studentId }} <span class="mx-2 text-gray-400">|</span> 
                            <span class="font-medium">Họ và tên:</span> {{ selectedMeeting.studentName }}
                        </p>
                    </div>

                    <div>
                        <h4 class="font-semibold text-base mb-3">Thông tin lịch hẹn</h4>
                        
                        <div class="mb-3">
                            <label class="block text-gray-700 mb-1">Tên đề tài</label>
                            <input type="text" :value="selectedMeeting.topic" disabled class="w-full px-3 py-2 bg-gray-200 border border-gray-300 rounded text-gray-700 outline-none cursor-not-allowed" />
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-1">Loại lịch hẹn</label>
                            <div class="flex gap-6 mt-1">
                                <label class="flex items-center gap-2 cursor-not-allowed">
                                    <input type="checkbox" disabled :checked="activeTab === 'guide'" class="w-4 h-4 text-gray-400 bg-gray-200 rounded border-gray-300">
                                    <span class="text-gray-500">Lịch Hướng dẫn</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-not-allowed">
                                    <input type="checkbox" disabled :checked="activeTab === 'review'" class="w-4 h-4 text-gray-400 bg-gray-200 rounded border-gray-300">
                                    <span class="text-gray-500">Lịch Phản biện</span>
                                </label>
                            </div>
                        </div>

                        <div class="grid grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 mb-1">Thời gian gặp</label>
                                <p class="font-medium">{{ selectedMeeting.time?.date || '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1">Giờ gặp</label>
                                <p class="font-medium">{{ selectedMeeting.time?.hour || '-' }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 mb-1">Địa điểm gặp</label>
                                <p class="font-medium mb-3">{{ selectedMeeting.location ? selectedMeeting.location.join(' ') : '-' }}</p>
                                
                                <div class="flex items-center gap-2">
                                    <label class="text-gray-700">Trạng thái:</label>
                                    <span v-if="selectedMeeting.status === 'confirmed'" class="bg-green-500 text-white px-2.5 py-1 rounded text-xs font-medium">Đã xác nhận</span>
                                    <span v-else-if="selectedMeeting.status === 'pending'" class="bg-yellow-500 text-white px-2.5 py-1 rounded text-xs font-medium">Chờ xác nhận</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="block text-gray-700 mb-1">Ghi chú</label>
                            <textarea disabled rows="2" class="w-full px-3 py-2 bg-gray-200 border border-gray-300 rounded text-gray-700 outline-none resize-none cursor-not-allowed" :value="selectedMeeting.note || 'Yêu cầu chuẩn bị báo cáo tiến độ và demo ứng dụng. Có mặt đúng giờ.'"></textarea>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 bg-white flex justify-end gap-3 rounded-b-xl border-t border-gray-200">
                    <button @click="closeDetailModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-300 text-gray-700 rounded text-sm font-medium transition-colors">
                        Đóng
                    </button>
                    <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition-colors">
                        Sửa thông tin
                    </button>
                </div>
            </div>
        </div>

    </div>
</template>
<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import axios from 'axios';

// ==========================================
// 1. NHẬN DỮ LIỆU TỪ LARAVEL CONTROLLER GỬI SANG
// ==========================================
const props = defineProps({
    lichHenData: {
        type: Array,
        default: () => []
    },
    sinhVienData: {
        type: Array,
        default: () => []
    }
});

const searchQuery = ref('');
const activeTab = ref('guide'); 

// ==========================================
// 2. XỬ LÝ DỮ LIỆU (FORMAT TỪ DATABASE RA GIAO DIỆN)
// ==========================================
// Chuyển đổi dữ liệu sinh viên cho thẻ Select
const studentList = computed(() => {
    return props.sinhVienData.map(sv => ({
        id: sv.mssv,
        name: sv.name,
        topic: sv.topic,
        MaDT: sv.MaDT
    }));
});

// Format dữ liệu lịch hẹn từ DB sang cấu trúc HTML đang dùng
const allFormattedSchedules = computed(() => {
    return props.lichHenData.map(dbItem => {
        const dateObj = new Date(dbItem.ThoiGianGap);
        // Fix bù múi giờ nếu cần, hoặc dùng trực tiếp
        const hourStr = dateObj.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', hour12: false });
        const dateStr = dateObj.toLocaleDateString('vi-VN'); // DD/MM/YYYY

        return {
            id: dbItem.id,
            studentId: dbItem.MSSV,
            studentName: dbItem.sinhvien ? dbItem.sinhvien.Ho_va_Ten : 'N/A',
            topic: dbItem.detai ? dbItem.detai.TenDeTai : 'Chưa có đề tài',
            time: { hour: hourStr, date: dateStr },
            location: dbItem.DiaDiem ? dbItem.DiaDiem.split(', ') : [],
            status: dbItem.TrangThai === 'Đã xác nhận' ? 'confirmed' : 'pending',
            note: dbItem.GhiChu,
            LoaiLich: dbItem.LoaiLich
        };
    });
});

// Lọc theo Tab (Hướng dẫn = 1, Phản biện = 2)
const guideSchedules = computed(() => allFormattedSchedules.value.filter(item => item.LoaiLich === 1));
const reviewSchedules = computed(() => allFormattedSchedules.value.filter(item => item.LoaiLich === 2));

// Tìm kiếm
const filteredSchedules = computed(() => {
    const currentData = activeTab.value === 'guide' ? guideSchedules.value : reviewSchedules.value;
    const q = searchQuery.value.trim().toLowerCase();
    if (!q) return currentData;

    return currentData.filter(item => {
        const textToSearch = [item.studentId, item.studentName, item.topic].join(' ').toLowerCase();
        return textToSearch.includes(q);
    });
});

// ==========================================
// 3. LOGIC MODAL TẠO LỊCH MỚI (LƯU VÀO DATABASE)
// ==========================================
const isCreateModalOpen = ref(false);
const formSchedule = ref({
    studentId: '',
    topic: '',
    MaDT: null,
    isGuide: true,
    isReview: false,
    date: '',
    time: '',
    location: 'Văn phòng Khoa',
    note: ''
});

const openCreateModal = () => {
    resetForm();
    isCreateModalOpen.value = true;
};

const openCreateModalWithStudent = (studentItem) => {
    resetForm();
    formSchedule.value.studentId = studentItem.studentId;
    formSchedule.value.topic = studentItem.topic;
    isCreateModalOpen.value = true;
};

const closeCreateModal = () => {
    isCreateModalOpen.value = false;
};

const resetForm = () => {
    formSchedule.value = {
        studentId: '', topic: '', MaDT: null, isGuide: true, isReview: false,
        date: '', time: '', location: 'Văn phòng Khoa', note: ''
    };
};

const autoFillTopic = () => {
    const selected = studentList.value.find(s => s.id === formSchedule.value.studentId);
    if (selected) {
        formSchedule.value.topic = selected.topic;
        formSchedule.value.MaDT = selected.MaDT;
    }
};

const saveSchedule = async () => {
    if (!formSchedule.value.studentId || !formSchedule.value.date || !formSchedule.value.time) {
        alert('Vui lòng điền đủ thông tin sinh viên, ngày và giờ!');
        return;
    }

    const datetime = `${formSchedule.value.date} ${formSchedule.value.time}:00`;

    const payload = {
        MSSV: formSchedule.value.studentId,
        MaDT: formSchedule.value.MaDT || null,
        ThoiGianGap: datetime,
        DiaDiem: formSchedule.value.location,
        TrangThai: 'Chờ xác nhận',
        LoaiLich: formSchedule.value.isGuide ? 1 : 2,
        GhiChu: formSchedule.value.note || null
    };

    try {
        await axios.post(route('lichhen.store'), payload);
        alert('Tạo lịch gặp mới thành công!');
        closeCreateModal();
        // Sau khi tạo, bạn có thể muốn refresh danh sách lịch hẹn
        // fetchLichHen(); // hoặc emit một event
    } catch (error) {
        console.error('Lỗi khi tạo lịch:', error);
        // Hiển thị lỗi chi tiết nếu có
        if (error.response?.data?.errors) {
            const messages = Object.values(error.response.data.errors).flat().join('\n');
            alert('Lỗi: ' + messages);
        } else {
            alert('Có lỗi xảy ra khi lưu vào Database!');
        }
    }
};

// ==========================================
// 4. LOGIC XÓA LỊCH
// ==========================================
const deleteSchedule = (id) => {
    if(!confirm('Bạn có chắc muốn xóa lịch gặp này?')) return;
    
    router.delete(route('lichhen.destroy', id), {
        preserveScroll: true,
        onSuccess: () => alert('Đã xóa thành công!')
    });
};

// ==========================================
// 5. LOGIC MODAL CHI TIẾT
// ==========================================
const isDetailModalOpen = ref(false);
const selectedMeeting = ref(null);

const openDetailModal = (meetingItem) => {
    selectedMeeting.value = { ...meetingItem };
    if (!selectedMeeting.value.note) {
        selectedMeeting.value.note = "Không có ghi chú thêm.";
    }
    isDetailModalOpen.value = true;
};

const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    setTimeout(() => { selectedMeeting.value = null; }, 200);
};
</script>