<template>
    <div class="relative p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-indigo-800 uppercase tracking-wide">
                Quản lý lịch gặp sinh viên
            </h2>

            <div class="flex items-center gap-4">                
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
                    <div class="flex flex-col gap-2 pt-1">
                        <label class="font-medium text-gray-700">Loại lịch hẹn</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" v-model="formSchedule.scheduleType" value="guide" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
                                <span class="text-gray-700">Lịch Hướng dẫn</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" v-model="formSchedule.scheduleType" value="review" class="w-4 h-4 text-blue-600 rounded border-gray-300 focus:ring-blue-500">
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

                            <!-- Custom location input – appears only when "Tùy chỉnh..." is selected -->
                            <input 
                                v-if="formSchedule.location === 'Tùy chỉnh...'"
                                type="text"
                                v-model="customLocation"
                                placeholder="Nhập địa điểm tùy chỉnh..."
                                class="mt-2 w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none"
                            >
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

        <!-- Modal Chi tiết + Chỉnh sửa -->
        <div v-if="isDetailModalOpen" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-40">
            <div class="bg-white rounded-xl shadow-2xl w-[600px] overflow-hidden flex flex-col">
                <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-[#1e1b4b] uppercase tracking-wide">CHI TIẾT LỊCH GẶP</h3>
                    <button @click="closeDetailModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" stroke-width="2" fill="none"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                    </button>
                </div>

                <div class="px-6 py-5 flex-1 overflow-y-auto space-y-4 text-sm" v-if="selectedMeeting">
                    <!-- Loại lịch hẹn (radio) -->
                    <div class="flex flex-col gap-2 pt-1">
                        <label class="font-medium text-gray-700">Loại lịch hẹn</label>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" v-model="editForm.scheduleType" value="guide" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                <span class="text-gray-700">Lịch Hướng dẫn</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="radio" v-model="editForm.scheduleType" value="review" class="w-4 h-4 text-blue-600 rounded border-gray-300">
                                <span class="text-gray-700">Lịch Phản biện</span>
                            </label>
                        </div>
                    </div>

                    <!-- Thời gian & Địa điểm -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Thời gian gặp</label>
                            <div class="flex gap-2">
                                <div class="relative w-3/5">
                                    <input type="date" v-model="editForm.date" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" />
                                </div>
                                <div class="relative w-2/5">
                                    <input type="time" v-model="editForm.time" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none" />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1">
                            <label class="font-medium text-gray-700">Địa điểm</label>
                            <select v-model="editForm.location" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                                <option value="Văn phòng Khoa">Văn phòng Khoa</option>
                                <option value="Thư viện">Thư viện</option>
                                <option value="Online (Zoom)">Online (Zoom)</option>
                                <option value="Online (Google Meet)">Online (Google Meet)</option>
                                <option value="Tùy chỉnh...">Tùy chỉnh...</option>
                            </select>

                            <!-- Custom location input -->
                            <input 
                                v-if="editForm.location === 'Tùy chỉnh...'"
                                type="text"
                                v-model="editCustomLocation"
                                placeholder="Nhập địa điểm tùy chỉnh..."
                                class="mt-2 w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none"
                            >
                        </div>
                    </div>

                    <!-- Trạng thái (có thể chỉnh sửa nếu muốn) -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium text-gray-700">Trạng thái</label>
                        <select v-model="editForm.status" class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none bg-white">
                            <option value="pending">Chờ xác nhận</option>
                            <option value="confirmed">Đã xác nhận</option>
                        </select>
                    </div>

                    <!-- Ghi chú -->
                    <div class="flex flex-col gap-1">
                        <label class="font-medium text-gray-700">Ghi chú (Tùy chọn)</label>
                        <textarea v-model="editForm.note" rows="3" placeholder="Yêu cầu chuẩn bị báo cáo tiến độ..." class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-blue-500 outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="px-6 py-4 bg-white flex justify-end gap-3 rounded-b-xl border-t border-gray-200">
                    <button @click="closeDetailModal" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 border border-gray-300 text-gray-700 rounded text-sm font-medium transition-colors">
                        Hủy
                    </button>
                    <button @click="updateSchedule" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded text-sm font-medium transition-colors">
                        Cập nhật
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
    fetchLichHen: Function
});

const searchQuery = ref('');
const activeTab = ref('guide'); 

// ==========================================
// 2. XỬ LÝ DỮ LIỆU (FORMAT TỪ DATABASE RA GIAO DIỆN)
// ==========================================
// Format dữ liệu lịch hẹn từ DB sang cấu trúc HTML đang dùng
const allFormattedSchedules = computed(() => {
    return props.lichHenData.map(dbItem => {
        const dateObj = new Date(dbItem.ThoiGianGap);
        // Fix bù múi giờ nếu cần, hoặc dùng trực tiếp
        const hourStr = dateObj.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', hour12: false });
        const dateStr = dateObj.toLocaleDateString('vi-VN');

        return {
            id: dbItem.id,
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

    return currentData;
});

// ==========================================
// 3. LOGIC MODAL TẠO LỊCH MỚI (LƯU VÀO DATABASE)
// ==========================================
const isCreateModalOpen = ref(false);
const formSchedule = ref({
    id: null,
    scheduleType: 'guide',
    date: '',
    time: '',
    location: 'Văn phòng Khoa',
    note: '',
    customLocation: ''
});

const openCreateModal = () => {
    resetForm();
    isCreateModalOpen.value = true;
};

const closeCreateModal = () => {
    isCreateModalOpen.value = false;
};

const resetForm = () => {
    formSchedule.value = {
        id: null,
        scheduleType: 'guide',
        date: '', time: '', location: 'Văn phòng Khoa', note: '', customLocation: ''
    };
};


const saveSchedule = async () => {
    if (!formSchedule.value.date || !formSchedule.value.time) {
        alert('Vui lòng điền đủ thông tin sinh viên, ngày và giờ!');
        return;
    }
    console.log('Form data to save:', formSchedule.value);

    const datetime = `${formSchedule.value.date} ${formSchedule.value.time}:00`;

    const payload = {
        ThoiGianGap: datetime,
        DiaDiem: formSchedule.value.location === 'Tùy chỉnh...' ? formSchedule.value.customLocation : formSchedule.value.location,
        TrangThai: 'Chờ xác nhận',
        LoaiLich: formSchedule.value.scheduleType === 'guide' ? 1 : 2,
        GhiChu: formSchedule.value.note || null
    };

    try {
        await axios.post(route('lichhen.store'), payload);
        alert('Tạo lịch gặp mới thành công!');
        closeCreateModal();
        props.fetchLichHen(); // Refresh danh sách sau khi tạo mới
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

    props.fetchLichHen(); // Refresh danh sách sau khi xóa
};

// ==========================================
// 5. LOGIC MODAL CHI TIẾT
// ==========================================

const isDetailModalOpen = ref(false);
const selectedMeeting = ref(null);
const editForm = ref({
    id: null,
    scheduleType: 'guide',
    date: '',
    time: '',
    location: 'Văn phòng Khoa',
    status: 'pending',
    note: ''
});
const editCustomLocation = ref('');

// Override openDetailModal to populate editForm
const openDetailModal = (meetingItem) => {
    // meetingItem comes from filteredSchedules (formatted)
    // Extract date and time in editable format
    let dateStr = '';
    let timeStr = '';
    if (meetingItem.rawDatetime) {
        const d = new Date(meetingItem.rawDatetime);
        dateStr = d.toISOString().split('T')[0];
        timeStr = d.toTimeString().slice(0, 5);
    } else {
        // Fallback: parse from meetingItem.time.date (dd/mm/yyyy) and hour
        const [day, month, year] = meetingItem.time.date.split('/');
        dateStr = `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        timeStr = meetingItem.time.hour;
    }

    // Determine location value
    const locationString = meetingItem.location.join(', ');
    let locValue = locationString;
    let customLoc = '';
    const presetLocations = ['Văn phòng Khoa', 'Thư viện', 'Online (Zoom)', 'Online (Google Meet)'];
    if (!presetLocations.includes(locationString)) {
        locValue = 'Tùy chỉnh...';
        customLoc = locationString;
    }

    editForm.value = {
        id: meetingItem.id,
        scheduleType: meetingItem.LoaiLich === 1 ? 'guide' : 'review',
        date: dateStr,
        time: timeStr,
        location: locValue,
        status: meetingItem.status,   // 'pending' or 'confirmed'
        note: meetingItem.note || ''
    };
    editCustomLocation.value = customLoc;

    selectedMeeting.value = { ...meetingItem }; // keep original for reference
    isDetailModalOpen.value = true;
};

// Update function
const updateSchedule = async () => {
    if (!editForm.value.date || !editForm.value.time) {
        alert('Vui lòng điền đủ ngày và giờ!');
        return;
    }

    const datetime = `${editForm.value.date} ${editForm.value.time}:00`;
    
    let finalLocation = editForm.value.location;
    if (finalLocation === 'Tùy chỉnh...') {
        if (!editCustomLocation.value.trim()) {
            alert('Vui lòng nhập địa điểm tùy chỉnh!');
            return;
        }
        finalLocation = editCustomLocation.value.trim();
    }

    // Map status from frontend to backend format
    const backendStatus = editForm.value.status === 'confirmed' ? 'Đã xác nhận' : 'Chờ xác nhận';

    const payload = {
        ThoiGianGap: datetime,
        DiaDiem: finalLocation,
        TrangThai: backendStatus,
        LoaiLich: editForm.value.scheduleType === 'guide' ? 1 : 2,
        GhiChu: editForm.value.note || null
    };

    try {
        await axios.put(route('lichhen.update', editForm.value.id), payload);
        alert('Cập nhật lịch thành công!');
        closeDetailModal();
        props.fetchLichHen(); // Refresh danh sách sau khi cập nhật
    } catch (error) {
        console.error(error);
        if (error.response?.data?.errors) {
            const messages = Object.values(error.response.data.errors).flat().join('\n');
            alert('Lỗi: ' + messages);
        } else {
            alert('Có lỗi xảy ra khi cập nhật!');
        }
    }
};

// Close modal (reset editForm optionally)
const closeDetailModal = () => {
    isDetailModalOpen.value = false;
    setTimeout(() => {
        selectedMeeting.value = null;
        // Optional: reset editForm to avoid old data showing next time
        editForm.value = {
            id: null,
            scheduleType: 'guide',
            date: '',
            time: '',
            location: 'Văn phòng Khoa',
            status: 'pending',
            note: ''
        };
        editCustomLocation.value = '';
    }, 200);
};
</script>