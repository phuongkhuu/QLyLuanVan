<template>
    <div class="min-h-screen bg-gray-50 font-vietnamese text-gray-800">
        <!-- Header -->
        <HeaderGV :user="headerUser" />

        <!-- Body -->
        <div class="flex h-[calc(100vh-4rem)]">
            <!-- Sidebar  -->
            <SidebarGV
                :currentView="currentView"
                :user = "user"
                @changeView="currentView = $event"
            />

            <!-- Main content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Dashboard View -->
                <DashboardView
                    v-if="currentView === 'dashboard'"
                    :user="user"
                    :students="students1"
                />

                <!-- Students view -->
                <StudentsView
                    v-if="currentView === 'students'"
                    :students="students1"
                    @refreshStudents="fetchStudents"
                    @groupsUpdated="applyStudentGroupUpdates"
                />

                <!-- Assign Topic view -->
                <AssignTopicView
                    v-if="currentView === 'assignTopic'"
                    :assignments="filteredGuideScoreList"
                    :assignSearch="assignSearch"
                    @updateSearch="assignSearch = $event"
                    @openAssign="openAssignForm"
                    @downloadTemplate="downloadTemplate"
                />
                <AssignTopicModal
                    :show="showForm"
                    :formData="formData"
                    @close="closeForm"
                    @save="saveForm"
                />

                <!-- Evaluation 50% view -->
                <Evaluation50View
                    v-if="currentView === 'evaluation50'"
                    :students="filteredEvaluationStudents"
                    :evaluationSearch="evaluationSearch"
                    :canGrade50="canGrade50"
                    @updateSearch="evaluationSearch = $event"
                    @updateScore="updateScore"
                    @updateNote="updateNote"
                />

                <!-- Review Score view -->
                <ReviewScoreView
                    v-if="currentView === 'reviewScore'"
                    :reviewScoreSearch="reviewScoreSearch"
                    :reviewScoreRows="reviewScoreRows"
                    @updateSearch="reviewScoreSearch = $event"
                    @openReview="openReviewScoreMiniForm"
                    @hoverTopic="setHoveredTopic"
                    @leaveTopic="clearHoveredTopic"
                    :isTopicHovered="isTopicHovered"
                />
                <!-- Lịch gặp sinh viên -->
                <AppointmentView
                    v-if="currentView === 'AppointmentView'"
                    :lichHenData="lichHenData"
                    :fetchLichHen="fetchLichHen"
                />
                <!-- ========================= -->
                <!-- GUIDE SCORE VIEW -->
                <!-- ========================= -->
                <div v-if="currentView === 'guideScore'" class="space-y-4">
                    <!-- Header -->
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-indigo-600">
                            ĐIỂM HƯỚNG DẪN
                        </h2>

                        <input
                            v-model="guideScoreSearch"
                            type="text"
                            placeholder="Tìm mã hoặc tên đề tài..."
                            class="w-72 px-3 py-2 text-sm rounded-lg border border-gray-300 bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                        />
                    </div>

                    <!-- Table -->
                    <div
                        class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
                    >
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <!-- Header -->
                                <thead class="bg-indigo-100 text-indigo-700">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-center font-semibold"
                                        >
                                            STT
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center font-semibold"
                                        >
                                            Mã đề tài
                                        </th>

                                        <th
                                            class="px-4 py-3 text-left font-semibold"
                                        >
                                            Tên đề tài
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center font-semibold"
                                        >
                                            MSSV
                                        </th>

                                        <th
                                            class="px-4 py-3 text-left font-semibold"
                                        >
                                            Họ và tên sinh viên
                                        </th>

                                        <th
                                            class="px-4 py-3 text-center font-semibold"
                                        >
                                            Thao tác
                                        </th>
                                    </tr>
                                </thead>

                                <!-- Body -->
                                <tbody>
                                    <tr
                                        v-for="(row, idx) in guideScoreList"
                                        :key="
                                            (row.topic.MaDT ||
                                                row.topic.id ||
                                                't') +
                                            '-' +
                                            idx
                                        "
                                        @mouseenter="setHoveredTopic(row.topic)"
                                        @mouseleave="clearHoveredTopic"
                                        :class="[
                                            isTopicHovered(row.topic)
                                                ? 'bg-indigo-50'
                                                : 'bg-white',

                                            row.isFirst
                                                ? 'border-t-[8px] border-gray-100'
                                                : '',

                                            'transition-colors duration-150',
                                        ]"
                                    >
                                        <!-- STT -->
                                        <td
                                            v-if="row.isFirst"
                                            :rowspan="row.rowSpan"
                                            class="px-4 py-4 text-center align-middle text-gray-700"
                                        >
                                            {{ row.stt }}
                                        </td>

                                        <!-- Mã đề tài -->
                                        <td
                                            v-if="row.isFirst"
                                            :rowspan="row.rowSpan"
                                            class="px-4 py-4 text-center align-middle"
                                        >
                                            <span
                                                class="px-3 py-1 rounded-full bg-indigo-500 text-white text-xs font-bold"
                                            >
                                                {{
                                                    row.topic.MaDT ||
                                                    row.topic.id ||
                                                    "-"
                                                }}
                                            </span>
                                        </td>

                                        <!-- Tên đề tài -->
                                        <td
                                            v-if="row.isFirst"
                                            :rowspan="row.rowSpan"
                                            class="px-4 py-4 align-middle"
                                        >
                                            <div
                                                class="max-w-[260px] font-semibold text-gray-800 leading-relaxed"
                                            >
                                                {{
                                                    row.topic.TenDeTai ||
                                                    row.topic.TenDT ||
                                                    row.topic.title ||
                                                    "-"
                                                }}
                                            </div>
                                        </td>

                                        <!-- MSSV -->
                                        <td
                                            class="px-4 py-4 text-center text-gray-700 whitespace-nowrap"
                                        >
                                            {{ row.MSSV || "-" }}
                                        </td>

                                        <!-- Name -->
                                        <td
                                            class="px-4 py-4 text-gray-800 font-medium whitespace-nowrap"
                                        >
                                            {{ row.name || "-" }}
                                        </td>

                                        <!-- Action -->
                                        <td
                                            v-if="row.isFirst"
                                            :rowspan="row.rowSpan"
                                            class="px-4 py-4 text-center align-middle"
                                        >
                                            <button
                                                @click="
                                                    openGuideScoreMiniForm(
                                                        row.topic,
                                                        row.MSSV,
                                                        row.name,
                                                    )
                                                "
                                                class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-sm transition-all"
                                            >
                                                Chấm điểm
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Empty -->
                                    <tr
                                        v-if="
                                            (guideScoreList || []).length === 0
                                        "
                                    >
                                        <td
                                            colspan="6"
                                            class="py-8 text-center text-gray-400"
                                        >
                                            Không có đề tài
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ========================= -->
                <!-- GUIDE SCORE MODAL -->
                <!-- ========================= -->
                <div
                    v-if="showGuideScoreMiniForm"
                    class="fixed inset-0 z-50 flex items-start justify-center overflow-auto bg-black/40 backdrop-blur-sm"
                >
                    <div
                        class="relative w-[95%] max-w-6xl rounded-2xl bg-white shadow-2xl mt-8 mb-8"
                    >
                        <!-- Header -->
                        <div
                            class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-200 bg-white px-6 py-4 rounded-t-2xl"
                        >
                            <h3 class="text-2xl font-bold text-indigo-600">
                                Phiếu chấm điểm hướng dẫn
                            </h3>

                            <button
                                @click="closeGuideScoreMiniForm"
                                class="flex items-center justify-center w-9 h-9 rounded-full hover:bg-gray-100 text-gray-500 transition-all"
                            >
                                ✕
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="p-6">
                            <!-- Group Info -->
                            <div
                                class="rounded-2xl border border-indigo-100 bg-indigo-50 p-5 mb-6"
                            >
                                <p class="text-sm text-gray-500 mb-2">
                                    Thành viên nhóm
                                </p>

                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span
                                        v-for="mem in guideScoreMiniForm.students"
                                        :key="mem.mssv"
                                        class="px-3 py-1 rounded-full bg-white border border-indigo-200 text-sm font-medium text-indigo-700"
                                    >
                                        {{ mem.name }} - {{ mem.mssv }}
                                    </span>
                                </div>

                                <div>
                                    <span class="text-sm text-gray-500">
                                        Đề tài:
                                    </span>

                                    <span
                                        class="font-semibold text-gray-800 ml-1"
                                    >
                                        {{ guideScoreMiniForm.TenDeTai }}
                                    </span>
                                </div>
                            </div>

                            <!-- Shared Comment -->
                            <div
                                class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm mb-8"
                            >
                                <h4
                                    class="text-lg font-bold text-indigo-600 mb-6"
                                >
                                    Nhận xét chung cho nhóm
                                </h4>

                                <!-- Radio -->
                                <div class="flex justify-center gap-10 mb-6">
                                    <label
                                        class="flex items-center gap-2 text-sm font-medium"
                                    >
                                        <input
                                            type="radio"
                                            value="Đạt"
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .overall
                                            "
                                        />

                                        Đạt
                                    </label>

                                    <label
                                        class="flex items-center gap-2 text-sm font-medium"
                                    >
                                        <input
                                            type="radio"
                                            value="Không đạt"
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .overall
                                            "
                                        />

                                        Không đạt
                                    </label>
                                </div>

                                <!-- Textareas -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-5"
                                >
                                    <div class="space-y-2">
                                        <label
                                            class="text-sm font-semibold text-gray-700"
                                        >
                                            Yêu cầu điều chỉnh
                                        </label>

                                        <textarea
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .overallNote
                                            "
                                            rows="4"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 resize-none focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="text-sm font-semibold text-gray-700"
                                        >
                                            Ưu điểm chính
                                        </label>

                                        <textarea
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .strengths
                                            "
                                            rows="4"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 resize-none focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="text-sm font-semibold text-gray-700"
                                        >
                                            Thiếu sót chính
                                        </label>

                                        <textarea
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .weaknesses
                                            "
                                            rows="4"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 resize-none focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                        ></textarea>
                                    </div>

                                    <div class="space-y-2">
                                        <label
                                            class="text-sm font-semibold text-gray-700"
                                        >
                                            Câu hỏi cho sinh viên
                                        </label>

                                        <textarea
                                            v-model="
                                                guideScoreMiniForm.shared
                                                    .questions
                                            "
                                            rows="4"
                                            class="w-full rounded-xl border border-gray-300 px-4 py-3 resize-none focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Score -->
                            <div
                                v-for="mem in guideScoreMiniForm.students"
                                :key="mem.mssv"
                                class="rounded-2xl border border-gray-200 bg-white shadow-sm p-5 mb-8"
                            >
                                <!-- Student Header -->
                                <div
                                    class="flex items-center justify-between mb-5"
                                >
                                    <div>
                                        <h4
                                            class="text-lg font-bold text-gray-800"
                                        >
                                            {{ mem.name }}
                                        </h4>

                                        <p class="text-sm text-gray-500">
                                            MSSV: {{ mem.mssv }}
                                        </p>
                                    </div>

                                    <span
                                        class="px-4 py-2 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold"
                                    >
                                        {{ percentGuideScoreOfStudent(mem) }}%
                                    </span>
                                </div>

                                <!-- Table -->
                                <div
                                    class="overflow-hidden rounded-xl border border-gray-200"
                                >
                                    <table class="w-full text-sm">
                                        <thead
                                            class="bg-indigo-100 text-indigo-700"
                                        >
                                            <tr>
                                                <th
                                                    class="px-3 py-3 text-center"
                                                >
                                                    STT
                                                </th>

                                                <th class="px-3 py-3 text-left">
                                                    Nội dung
                                                </th>

                                                <th
                                                    class="px-3 py-3 text-center"
                                                >
                                                    Max
                                                </th>

                                                <th
                                                    class="px-3 py-3 text-center"
                                                >
                                                    Điểm
                                                </th>

                                                <th
                                                    class="px-3 py-3 text-center"
                                                >
                                                    %
                                                </th>

                                                <th class="px-3 py-3 text-left">
                                                    Ghi chú
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr
                                                v-for="(c, idx) in mem.criteria"
                                                :key="idx"
                                                class="border-t border-gray-100"
                                            >
                                                <td
                                                    class="px-3 py-3 text-center"
                                                >
                                                    {{ idx + 1 }}
                                                </td>

                                                <td class="px-3 py-3">
                                                    {{ c.title }}
                                                </td>

                                                <td
                                                    class="px-3 py-3 text-center"
                                                >
                                                    {{ c.max }}
                                                </td>

                                                <td
                                                    class="px-3 py-3 text-center"
                                                >
                                                    <input
                                                        type="number"
                                                        v-model.number="c.score"
                                                        min="0"
                                                        :max="c.max"
                                                        @input="
                                                            onScoreInput(
                                                                $event,
                                                                c,
                                                            )
                                                        "
                                                        class="w-20 h-9 rounded-md border border-gray-300 text-center focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                                    />
                                                </td>

                                                <td
                                                    class="px-3 py-3 text-center font-medium text-indigo-600"
                                                >
                                                    {{ c.score * 10 }}%
                                                </td>

                                                <td class="px-3 py-3">
                                                    <textarea
                                                        v-model="c.note"
                                                        rows="2"
                                                        class="w-full rounded-lg border border-gray-300 px-3 py-2 resize-none focus:ring-2 focus:ring-indigo-400 focus:outline-none"
                                                    ></textarea>
                                                </td>
                                            </tr>

                                            <!-- Total -->
                                            <tr
                                                class="bg-indigo-50 border-t border-indigo-100"
                                            >
                                                <td
                                                    colspan="2"
                                                    class="px-4 py-4 font-bold text-indigo-700"
                                                >
                                                    Tổng điểm
                                                </td>

                                                <td
                                                    class="px-4 py-4 text-center font-bold"
                                                >
                                                    {{
                                                        totalGuideMaxOfStudent(
                                                            mem,
                                                        )
                                                    }}
                                                </td>

                                                <td
                                                    class="px-4 py-4 text-center font-bold"
                                                >
                                                    {{
                                                        totalGuideScoreOfStudent(
                                                            mem,
                                                        )
                                                    }}
                                                </td>

                                                <td
                                                    class="px-4 py-4 text-center font-bold text-indigo-700"
                                                >
                                                    {{
                                                        percentGuideScoreOfStudent(
                                                            mem,
                                                        )
                                                    }}%
                                                </td>

                                                <td></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Recommend -->
                                <div class="mt-5">
                                    <label
                                        class="block text-sm font-semibold text-gray-700 mb-3"
                                    >
                                        Đề nghị bảo vệ
                                    </label>

                                    <div class="flex flex-wrap gap-5">
                                        <label class="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                value="Được bảo vệ"
                                                v-model="mem.recommend"
                                            />

                                            Được bảo vệ
                                        </label>

                                        <label class="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                value="Không được bảo vệ"
                                                v-model="mem.recommend"
                                            />

                                            Không được bảo vệ
                                        </label>

                                        <label class="flex items-center gap-2">
                                            <input
                                                type="radio"
                                                value="Bổ sung/hiệu chỉnh để được bảo vệ"
                                                v-model="mem.recommend"
                                            />

                                            Bổ sung/hiệu chỉnh
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Footer -->
                            <div
                                class="sticky bottom-0 bg-white border-t border-gray-200 pt-5 flex justify-end gap-3"
                            >
                                <button
                                    @click="closeGuideScoreMiniForm"
                                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 transition-all"
                                >
                                    Đóng
                                </button>

                                <button
                                    @click="saveGuideScoreMiniForm"
                                    :disabled="!canGuide"
                                    class="px-6 py-2.5 rounded-xl bg-blue-500 hover:bg-blue-600 disabled:opacity-50 text-white font-semibold shadow-sm transition-all"
                                >
                                    Xác nhận
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive, watch } from "vue";
import axios from "axios";
import HeaderGV from "./components/HeaderGV.vue";
import DashboardView from "./components/DashboardView.vue";
import SidebarGV from "./components/SidebarGV.vue";
import StudentsView from "./components/StudentsView.vue";
import AssignTopicView from "./components/AssignTopicView.vue";
import AssignTopicModal from "./components/AssignTopicModal.vue";
import Evaluation50View from "./components/Evaluation50View.vue";
import ReviewScoreView from "./components/ReviewScoreView.vue";
// cập nhật GUI
import AppointmentView from "./components/AppointmentView.vue";

const props = defineProps({
    user: { type: Object, default: () => ({ name: "Giảng viên" }) },
});
const user = props.user;

// Sidebar view state
const currentView = ref("dashboard");

const students = ref([]);
const students1 = ref([]);
const studentsReviewer = ref([]);
const topics = ref([]);
const topicsReview = ref([]);
const lichHenData = ref([]);
const teacherMaGV = ref(null);
const rawGuideStudents = ref([]);

// Hover theo đề tài cho bảng điểm phản biện
const hoveredTopicKey = ref(null);

function topicKey(topic) {
    if (!topic) return "";
    return (topic.MaDT || topic.id || topic.maDT || "").toString();
}

function setHoveredTopic(topic) {
    hoveredTopicKey.value = topicKey(topic);
}

function clearHoveredTopic() {
    hoveredTopicKey.value = null;
}

function isTopicHovered(topic) {
    return (
        hoveredTopicKey.value !== null &&
        hoveredTopicKey.value === topicKey(topic)
    );
}

const normalizeStudent = (s, role) => {
    const rawGroup = s.group ?? s.Nhom ?? null;
    let displayGroup = rawGroup;

    if (typeof rawGroup === "string" && rawGroup.includes("-")) {
        displayGroup = rawGroup.split("-")[1];
    }

    return {
        ...s,
        role,
        group: displayGroup,
    };
};

function getStudentMssv(s) {
    return s?.mssv ?? s?.MSSV ?? null;
}

function applyGroupToList(listRef, updateMap) {
    if (!Array.isArray(listRef.value)) return;

    listRef.value.forEach((student) => {
        const mssv = getStudentMssv(student);
        if (!mssv || !updateMap.has(mssv)) return;

        const newGroup = updateMap.get(mssv);
        student.group = newGroup;
        if (Object.prototype.hasOwnProperty.call(student, "Nhom")) {
            student.Nhom = newGroup;
        }
    });
}

function sortByGroupAsc(listRef) {
    if (!Array.isArray(listRef.value)) return;
    listRef.value = [...listRef.value].sort(
        (a, b) => Number(a.group ?? 0) - Number(b.group ?? 0),
    );
}

function applyStudentGroupUpdates(updates = []) {
    const validUpdates = (updates || []).filter(
        (u) => u && u.mssv != null && u.group != null,
    );

    if (validUpdates.length === 0) return;

    const updateMap = new Map(
        validUpdates.map((u) => [String(u.mssv), u.group]),
    );

    applyGroupToList(students1, updateMap);
    applyGroupToList(students, updateMap);
    applyGroupToList(studentsReviewer, updateMap);
    applyGroupToList(rawGuideStudents, updateMap);

    sortByGroupAsc(students1);
    sortByGroupAsc(students);
    sortByGroupAsc(studentsReviewer);
}

const fetchStudents = async () => {
    try {
        const teacherRes = await axios.post(
            "/teacher-by-id/" + (user?.id || props.user.id),
        );

        const MaGV = teacherRes.data?.MaGV;
        teacherMaGV.value = MaGV;
        if (!MaGV) return;

        const [resTeacher, resReviewer] = await Promise.all([
            axios.post("/students-by-teacher/" + MaGV),
            axios.post("/students-by-reviewer/" + MaGV),
        ]);

        rawGuideStudents.value = resTeacher.data || [];
        console.log(rawGuideStudents.value[0]);

        const guideStudents = (resTeacher.data || []).map((s) =>
            normalizeStudent(s, "guide"),
        );
        const reviewerStudents = (resReviewer.data || []).map((s) =>
            normalizeStudent(s, "reviewer"),
        );

        const mergedMap = new Map();

        [...guideStudents, ...reviewerStudents].forEach((s) => {
            if (!mergedMap.has(s.mssv)) {
                mergedMap.set(s.mssv, s);
            }
        });
        students1.value =
            guideStudents.sort(
                (a, b) => Number(a.group ?? 0) - Number(b.group ?? 0),
            ) || [];
        students.value = Array.from(mergedMap.values()).sort(
            (a, b) => Number(a.group ?? 0) - Number(b.group ?? 0),
        );
        studentsReviewer.value = reviewerStudents;
    } catch (err) {
        console.error("fetchStudents error:", err);
    }
};

async function fetchTopics() {
    try {
        const teacher = await axios.post("/teacher-by-id/" + user.id);
        const res = await axios.get("/topics-by-teacher/" + teacher.data.MaGV);
        const res1 = await axios.get(
            "/topics-by-review-teacher/" + teacher.data.MaGV,
        );
        topics.value = res.data || [];
        topicsReview.value = res1.data || [];
    } catch (e) {
        console.error(e);
    }
}

async function fetchLichHen() {
    try {
        const res = await axios.get("/lich-hen");
        lichHenData.value = res.data || [];
    } catch (e) {
        console.error(e);
    }
}

onMounted(() => {
    fetchStudents();
    fetchTopics();
    fetchLichHen();
    fetchGrade50Access();
    fetchGuideAccess();
    fetchReviewAccess();
});

function getStudentsOfTopic(topic) {
    if (!topic) return [];

    const topicId = topic.MaDT;
    if (!topicId) {
        console.error("Topic missing MaDT:", topic);
        return [];
    }

    return (students.value || [])
        .filter((s) => String(s.code) === String(topicId))
        .map((s) => ({
            mssv: s.mssv ?? s.MSSV ?? "",
            name: s.name ?? s.HoTen ?? "",
            group: s.group ?? "",
        }))
        .filter((s) => s.mssv || s.name);
}

function onScoreInput(e, c) {
    let val = Number(e.target.value);

    if (isNaN(val)) val = 0;
    if (val < 0) val = 0;
    if (val > c.max) val = c.max; // 2.5

    c.score = val;
}

// ======================
// Review & Guide lists
// ======================
const reviewScoreSearch = ref("");
const reviewScoreList = computed(() => {
    const q = (reviewScoreSearch.value || "").toString().toLowerCase().trim();
    return (topicsReview.value || []).filter((t) => {
        if (!q) return true;
        return (
            (t.MaDT || t.id || "") +
            " " +
            (t.TenDeTai || t.TenDT || t.title || "")
        )
            .toLowerCase()
            .includes(q);
    });
});

const guidingScoreList = computed(() => {
    const q = (guideScoreSearch.value || "").toString().toLowerCase().trim();

    // -------------------------------------------------------------------
    // 1. Build flat list of ALL rows (topic + students + orphans)
    // -------------------------------------------------------------------
    const allRows = [];

    // 1a. From each topic, add its students (or one empty row if no students)
    (topics.value || []).forEach((topic) => {
        const students = getStudentsOfTopic(topic); // still works on the real topic
        const sorted = [...students].sort(
            (a, b) => (Number(a.group) || 999) - (Number(b.group) || 999),
        );

        if (sorted.length === 0) {
            allRows.push({
                topic,
                MSSV: "",
                name: "",
                group: "",
            });
        } else {
            sorted.forEach((s) => {
                allRows.push({
                    topic,
                    MSSV: s.mssv,
                    name: s.name,
                    group: s.group,
                });
            });
        }
    });

    // 1b. Add students that are **not assigned** to any topic
    const assignedMSSVs = new Set(
        allRows.filter((r) => r.MSSV).map((r) => r.MSSV),
    );
    const orphans = (students1.value || []).filter(
        (s) => !assignedMSSVs.has(s.mssv),
    );
    orphans.forEach((s) => {
        allRows.push({
            topic: null,
            MSSV: s.mssv,
            name: s.name,
            group: s.group,
        });
    });

    // -------------------------------------------------------------------
    // 2. Filter by search query (on both student and topic fields)
    // -------------------------------------------------------------------
    let filtered = allRows;
    if (q) {
        filtered = allRows.filter((row) => {
            const t = row.topic || {};
            const text = [
                row.MSSV,
                row.name,
                row.group,
                t.MaDT || t.code || "",
                t.TenDeTai || t.TenDT || t.title || "",
                t.MoTa || t.description || "",
                t.TrangThai || t.status || "",
            ]
                .join(" ")
                .toLowerCase();
            return text.includes(q);
        });
    }

    // -------------------------------------------------------------------
    // 3. Add display helpers: stt, isFirst, rowSpan
    //    (group consecutive rows that share the same topic)
    // -------------------------------------------------------------------
    let sttCounter = 0;
    const result = [];
    let i = 0;
    while (i < filtered.length) {
        // Identify the group: all rows with the same topic reference.
        // For orphan rows (topic == null), each gets its own group.
        const currentTopic = filtered[i].topic;
        let j = i;
        while (j < filtered.length && filtered[j].topic === currentTopic) {
            j++;
        }
        const group = filtered.slice(i, j);
        sttCounter++;

        group.forEach((row, idx) => {
            result.push({
                ...row,
                isFirst: idx === 0,
                stt: idx === 0 ? sttCounter : null,
                rowSpan: idx === 0 ? group.length : null,
            });
        });

        i = j;
    }

    return result;
});

// guideScoreList is now just an alias – you can delete this and use guidingScoreList directly in your template
const guideScoreList = computed(() => guidingScoreList.value);
const guideScoreSearch = ref("");

const rawReviewScoreRows = computed(() => {
    const result = [];
    let sttCounter = 0;

    (topicsReview.value || []).forEach((topic) => {
        const students = getReviewerStudentsOfTopic(topic);
        sttCounter++;

        if (!students.length) {
            result.push({
                topic,
                MSSV: "",
                name: "",
                isFirst: true,
                stt: sttCounter,
                rowSpan: 1,
            });
            return;
        }

        students.forEach((stu, idx) => {
            result.push({
                topic,
                MSSV: stu.mssv,
                name: stu.name,
                isFirst: idx === 0,
                stt: idx === 0 ? sttCounter : null,
                rowSpan: idx === 0 ? students.length : null,
            });
        });
    });

    return result;
});

const reviewScoreRows = computed(() => {
    const q = reviewScoreSearch.value.toLowerCase().trim();
    if (!q) return rawReviewScoreRows.value;

    return rawReviewScoreRows.value.filter((row) => {
        const text = [
            row.topic?.MaDT,
            row.topic?.TenDeTai,
            row.topic?.TenDT,
            row.topic?.title,
            row.MSSV,
            row.name,
        ]
            .join(" ")
            .toLowerCase();

        return text.includes(q);
    });
});

const getReviewerStudentsOfTopic = (topic) => {
    const topicId = topic?.MaDT ?? topic?.id;
    if (!topicId) return [];

    return (studentsReviewer.value || [])
        .filter((s) => String(s.MaDT) === String(topicId))
        .map((s) => ({
            mssv: s.MSSV ?? s.mssv ?? "",
            name: s.HoTen ?? s.name ?? "",
            group: s.group ?? "",
        }))
        .filter((s) => s.mssv || s.name);
};

const showReviewScoreMiniForm = ref(false);
const reviewScoreMiniForm = reactive({
    TenDeTai: "",
    MaDT: "",
    reviewerName: user?.name || "",

    shared: {
        overall: "Đạt",
        overallNote: "",
        strengths: "",
        weaknesses: "",
        questions: "",
    },

    students: [],
});

async function openReviewScoreMiniForm(topic) {
    console.log("openReviewScoreMiniForm topic:", topic);
    reviewScoreMiniForm.TenDeTai =
        topic.topic.TenDeTai || topic.topic.TenDT || topic.topic.title || "";

    reviewScoreMiniForm.MaDT = topic.topic.MaDT || topic.topic.id || "";

    const groupStu = getStudentsOfTopic(topic);

    reviewScoreMiniForm.shared = {
        overall: "Đạt",
        overallNote: "",
        strengths: "",
        weaknesses: "",
        questions: "",
    };

    reviewScoreMiniForm.students = await Promise.all(
        (groupStu.length
            ? groupStu
            : [
                  {
                      name: topic.name || "",
                      mssv: topic.MSSV || "",
                  },
              ]
        ).map(async (stu) => {
            let dbScore = null;
            try {
                const res = await axios.get(`/diem-phan-bien/mssv/${stu.mssv}`);
                dbScore = res.data;
            } catch (_) {
                dbScore = null;
            }

            if (dbScore) {
                reviewScoreMiniForm.shared.overall = dbScore.Danh_gia || "Đạt";
                reviewScoreMiniForm.shared.overallNote =
                    dbScore.dieu_chinh || "";
                reviewScoreMiniForm.shared.strengths = dbScore.uu || "";
                reviewScoreMiniForm.shared.weaknesses = dbScore.nhuoc || "";
                reviewScoreMiniForm.shared.questions = dbScore.cau_hoi || "";
            }

            return {
                name: stu.name,
                mssv: stu.mssv,

                recommend:
                    dbScore?.bao_ve || "Bổ sung/hiệu chỉnh để được bảo vệ",

                criteria: [
                    {
                        title: "Phân tích vấn đề",
                        max: 2.5,
                        score: dbScore?.pttk ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 1),
                    },
                    {
                        title: "Thiết kế vấn đề",
                        max: 2.5,
                        score: dbScore?.tkvd ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 2),
                    },
                    {
                        title: "Hiện thực vấn đề",
                        max: 2.5,
                        score: dbScore?.htvd ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 3),
                    },
                    {
                        title: "Kiểm tra sản phẩm",
                        max: 2.5,
                        score: dbScore?.ktsp ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 4),
                    },
                ],
            };
        }),
    );

    showReviewScoreMiniForm.value = true;
}

function extractNote(ghiChu, index) {
    if (!ghiChu) return "";

    const lines = ghiChu
        .split("\n")
        .map((l) => l.trim())
        .filter((l) => l !== "");

    const titles = [
        "Phân tích vấn đề",
        "Thiết kế vấn đề",
        "Hiện thực vấn đề",
        "Kiểm tra sản phẩm",
    ];

    const targetTitle = titles[index - 1];

    for (const line of lines) {
        const match = line.match(/^\d+\.\s*(.*?):\s*(.*)$/);
        if (match) {
            const [, title, note] = match;
            if (title === targetTitle) return note.trim();
        }
    }

    return "";
}

function closeReviewScoreMiniForm() {
    showReviewScoreMiniForm.value = false;
}

function totalReviewMaxOfStudent(stu) {
    return (stu.criteria || []).reduce((s, c) => s + (Number(c.max) || 0), 0);
}

function totalReviewScoreOfStudent(stu) {
    return (stu.criteria || []).reduce((s, c) => s + (Number(c.score) || 0), 0);
}

function percentReviewScoreOfStudent(stu) {
    const max = totalReviewMaxOfStudent(stu);
    const score = totalReviewScoreOfStudent(stu);
    return Math.round((score / (max || 1)) * 100);
}

async function saveReviewScoreMiniForm() {
    try {
        for (const mem of reviewScoreMiniForm.students) {
            for (const c of mem.criteria) {
                if (c.score < 0) {
                    alert("Điểm không được âm");
                    return;
                }
                if (c.score > c.max) {
                    alert(`Điểm mục "${c.title}" không được vượt quá ${c.max}`);
                    return;
                }
            }

            const ghiChu = mem.criteria
                .filter((c) => c.note && c.note.trim() !== "")
                .map((c, i) => `${i + 1}. ${c.title}: ${c.note}`)
                .join("\n");

            const payload = {
                MaDT: reviewScoreMiniForm.MaDT,
                MSSV: mem.mssv,

                danh_gia: reviewScoreMiniForm.shared.overall,
                dieu_chinh: reviewScoreMiniForm.shared.overallNote,
                uu: reviewScoreMiniForm.shared.strengths,
                nhuoc: reviewScoreMiniForm.shared.weaknesses,
                cau_hoi: reviewScoreMiniForm.shared.questions,

                pttk: mem.criteria[0]?.score ?? null,
                tkvd: mem.criteria[1]?.score ?? null,
                htvd: mem.criteria[2]?.score ?? null,
                ktsp: mem.criteria[3]?.score ?? null,

                tong: totalReviewScoreOfStudent(mem),
                bao_ve: mem.recommend,
                ghi_chu: ghiChu,
            };

            await axios.post("/save-review-score", payload);
        }

        showReviewScoreMiniForm.value = false;
        await fetchTopics();
        await fetchStudents();

        alert("Lưu phiếu phản biện thành công");

        window.open(`/export/phan-bien/${reviewScoreMiniForm.MaDT}`, "_blank");
    } catch (e) {
        console.error(e);
        alert("Lỗi khi lưu phiếu phản biện");
    }
}

const showGuideScoreMiniForm = ref(false);
const guideScoreMiniForm = reactive({
    TenDeTai: "",
    MaDT: "",
    reviewerName: user?.name || "",

    shared: {
        overall: "Đạt",
        overallNote: "",
        strengths: "",
        weaknesses: "",
        questions: "",
    },

    students: [],
});

async function openGuideScoreMiniForm(topic) {
    guideScoreMiniForm.TenDeTai =
        topic.TenDeTai || topic.TenDT || topic.title || "";

    guideScoreMiniForm.MaDT = topic.MaDT || topic.id || "";

    const groupStu = getStudentsOfTopic(topic);

    guideScoreMiniForm.shared = {
        overall: "Đạt",
        overallNote: "",
        strengths: "",
        weaknesses: "",
        questions: "",
    };

    guideScoreMiniForm.students = await Promise.all(
        (groupStu.length
            ? groupStu
            : [
                  {
                      name: topic.studentName || "",
                      mssv: topic.mssv || "",
                  },
              ]
        ).map(async (stu) => {
            let dbScore = null;
            try {
                const res = await axios.get(`/diem-huong-dan/mssv/${stu.mssv}`);
                dbScore = res.data;
            } catch (_) {
                dbScore = null;
            }

            if (dbScore) {
                guideScoreMiniForm.shared.overall = dbScore.Danh_gia || "Đạt";
                guideScoreMiniForm.shared.overallNote =
                    dbScore.dieu_chinh || "";
                guideScoreMiniForm.shared.strengths = dbScore.uu || "";
                guideScoreMiniForm.shared.weaknesses = dbScore.nhuoc || "";
                guideScoreMiniForm.shared.questions = dbScore.cau_hoi || "";
            }

            return {
                name: stu.name,
                mssv: stu.mssv,

                recommend:
                    dbScore?.bao_ve || "Bổ sung/hiệu chỉnh để được bảo vệ",

                criteria: [
                    {
                        title: "Phân tích vấn đề",
                        max: 2.5,
                        score: dbScore?.pttk ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 1),
                    },
                    {
                        title: "Thiết kế vấn đề",
                        max: 2.5,
                        score: dbScore?.tkvd ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 2),
                    },
                    {
                        title: "Hiện thực vấn đề",
                        max: 2.5,
                        score: dbScore?.htvd ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 3),
                    },
                    {
                        title: "Kiểm tra sản phẩm",
                        max: 2.5,
                        score: dbScore?.ktsp ?? 0,
                        note: extractNote(dbScore?.ghi_chu, 4),
                    },
                ],
            };
        }),
    );

    showGuideScoreMiniForm.value = true;
}

function closeGuideScoreMiniForm() {
    showGuideScoreMiniForm.value = false;
}

function totalGuideMaxOfStudent(stu) {
    return (stu.criteria || []).reduce((s, c) => s + (Number(c.max) || 0), 0);
}

function totalGuideScoreOfStudent(stu) {
    return (stu.criteria || []).reduce((s, c) => s + (Number(c.score) || 0), 0);
}

function percentGuideScoreOfStudent(stu) {
    const max = totalGuideMaxOfStudent(stu);
    const score = totalGuideScoreOfStudent(stu);
    return Math.round((score / (max || 1)) * 100);
}

async function saveGuideScoreMiniForm() {
    try {
        for (const mem of guideScoreMiniForm.students) {
            for (const c of mem.criteria) {
                if (c.score < 0) {
                    alert("Điểm không được âm");
                    return;
                }
                if (c.score > c.max) {
                    alert(`Điểm mục "${c.title}" không được vượt quá ${c.max}`);
                    return;
                }
            }

            const ghiChu = mem.criteria
                .filter((c) => c.note && c.note.trim() !== "")
                .map((c, i) => `${i + 1}. ${c.title}: ${c.note}`)
                .join("\n");

            const payload = {
                MaDT: guideScoreMiniForm.MaDT,
                MSSV: mem.mssv,

                danh_gia: guideScoreMiniForm.shared.overall,
                dieu_chinh: guideScoreMiniForm.shared.overallNote,
                uu: guideScoreMiniForm.shared.strengths,
                nhuoc: guideScoreMiniForm.shared.weaknesses,
                cau_hoi: guideScoreMiniForm.shared.questions,

                pttk: mem.criteria[0]?.score ?? null,
                tkvd: mem.criteria[1]?.score ?? null,
                htvd: mem.criteria[2]?.score ?? null,
                ktsp: mem.criteria[3]?.score ?? null,

                tong: totalGuideScoreOfStudent(mem),
                bao_ve: mem.recommend,
                ghi_chu: ghiChu,
                mode: "guide",
            };

            await axios.post("/save-guide-score", payload);
        }

        showGuideScoreMiniForm.value = false;
        await fetchTopics();
        await fetchStudents();

        alert("Lưu phiếu hướng dẫn thành công");

        window.open(`/export/huong-dan/${guideScoreMiniForm.MaDT}`, "_blank");
    } catch (e) {
        console.error(e);
        alert("Lỗi khi lưu phiếu hướng dẫn");
    }
}

const showForm = ref(false);
const formMode = ref("add");
const formData = reactive({
    TenDT: "",
    MoTa: "",
    TrangThai: "",
});
const selectedStatus = ref("");

// Keep formData.TrangThai in sync for non-custom options
watch(selectedStatus, (newVal) => {
    if (newVal !== "Ý kiến khác") {
        formData.TrangThai = newVal;
    } else {
        formData.TrangThai = ""; // clear for custom input
    }
});

async function openAssignForm(student) {
    formMode.value = "add";
    const teacher = await axios.post("/teacher-by-id/" + props.user.id);
    formData.MaGV = teacher.data.MaGV || "";
    formData.MSSV = student.mssv || student.MSSV || "";
    // ✅ safe access even when topic is null
    formData.TenDT = student.topic?.TenDeTai || "";
    formData.MoTa = student.topic?.MoTa || "";
    formData.TrangThai = student.topic?.TrangThai || "Được tiếp tục";
    showForm.value = true;
}
async function downloadTemplate(MaDT) {
    const res = await axios.get(`/nhiem-vu-template/${MaDT}`, {
        responseType: "blob",
    });

    const url = window.URL.createObjectURL(new Blob([res.data]));

    let filename = "download.docx";
    const disposition = res.headers["content-disposition"];
    if (disposition && disposition.indexOf("filename=") !== -1) {
        const match = disposition.match(/filename\*=UTF-8''(.+)$/);
        if (match && match[1]) {
            filename = decodeURIComponent(match[1]);
        } else {
            const match2 = disposition.match(/filename="(.+)"/);
            if (match2 && match2[1]) {
                filename = match2[1];
            }
        }
    }

    const a = document.createElement("a");
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}
async function saveForm(updatedForm) {
    try {
        const payload = {
            MSSV: formData.MSSV,
            TenDT: updatedForm.TenDT,
            MoTa: updatedForm.MoTa,
            TrangThai: updatedForm.TrangThai,
            MaGV: formData.MaGV,
        };
        await axios.post("/save-topic", payload);
        alert("Lưu thành công!");
        showForm.value = false;
        fetchStudents();
        fetchTopics();
    } catch (err) {
        const msg = err.response?.data?.errors
            ? Object.values(err.response.data.errors).flat().join("\n")
            : err.response?.data?.message || "Lỗi khi lưu";
        alert(msg);
    }
}
function closeForm() {
    showForm.value = false;
}

const assignSearch = ref("");
const filteredGuideScoreList = computed(() => {
    const q = (assignSearch.value || "").toLowerCase().trim();

    // ------------------------------------------------------------------
    // 1. Build ALL possible rows (same as your guidingScoreList logic)
    // ------------------------------------------------------------------
    const allRows = [];

    (topics.value || []).forEach((topic) => {
        const students = getStudentsOfTopic(topic);
        const sorted = [...students].sort(
            (a, b) => (Number(a.group) || 999) - (Number(b.group) || 999),
        );

        if (sorted.length === 0) {
            allRows.push({ topic, MSSV: "", name: "", group: "" });
        } else {
            sorted.forEach((s) => {
                allRows.push({
                    topic,
                    MSSV: s.mssv,
                    name: s.name,
                    group: s.group,
                });
            });
        }
    });

    // Add orphans (students without a topic)
    const assignedMSSVs = new Set(
        allRows.filter((r) => r.MSSV).map((r) => r.MSSV),
    );
    const orphans = (students1.value || []).filter(
        (s) => !assignedMSSVs.has(s.mssv),
    );
    orphans.forEach((s) => {
        allRows.push({
            topic: null,
            MSSV: s.mssv,
            name: s.name,
            group: s.group,
        });
    });

    // ------------------------------------------------------------------
    // 2. Apply search filter (on both student & topic fields)
    // ------------------------------------------------------------------
    let filtered = allRows;
    if (q) {
        filtered = allRows.filter((row) => {
            const t = row.topic || {};
            const text = [
                row.MSSV,
                row.name,
                row.group,
                t.MaDT || t.code || "",
                t.TenDeTai || t.TenDT || t.title || "",
                t.MoTa || t.description || "",
                t.TrangThai || t.status || "",
            ]
                .join(" ")
                .toLowerCase();
            return text.includes(q);
        });
    }

    // ------------------------------------------------------------------
    // 3. Sort by group number ascending (lowest first)
    //    (Rows without a group go to the end)
    // ------------------------------------------------------------------
    filtered.sort((a, b) => {
        const gA = parseInt(a.group) || 9999;
        const gB = parseInt(b.group) || 9999;
        return gA - gB;
    });

    // ------------------------------------------------------------------
    // 4. Re‑group consecutive rows by topic and add display helpers
    // ------------------------------------------------------------------
    let sttCounter = 0;
    const result = [];
    let i = 0;
    while (i < filtered.length) {
        const currentTopic = filtered[i].topic;
        let j = i;
        while (j < filtered.length && filtered[j].topic === currentTopic) {
            j++;
        }
        const groupRows = filtered.slice(i, j);
        sttCounter++;

        groupRows.forEach((row, idx) => {
            result.push({
                ...row,
                isFirst: idx === 0,
                stt: idx === 0 ? sttCounter : null,
                rowSpan: idx === 0 ? groupRows.length : null,
            });
        });

        i = j;
    }

    return result;
});
const canGrade50 = ref(false);
2;
async function fetchGrade50Access() {
    try {
        const res = await axios.get(
            "/check-access/" + encodeURIComponent("Chấm điểm 50%"),
        );
        canGrade50.value = res.data.access;
    } catch (err) {
        console.error("Failed to fetch 50% grading access:", err);
        canGrade50.value = false;
    }
}

const canReview = ref(false);

async function fetchReviewAccess() {
    try {
        const res = await axios.get("/check-access/Chấm điểm phản biện");
        canReview.value = res.data.access;
    } catch (err) {
        console.error("Failed to fetch review access:", err);
        canReview.value = false;
    }
}

const canGuide = ref(false);

async function fetchGuideAccess() {
    try {
        const res = await axios.get("/check-access/Chấm điểm hướng dẫn");
        canGuide.value = res.data.access;
    } catch (err) {
        console.error("Failed to fetch guide access:", err);
        canGuide.value = false;
    }
}

const evaluationSearch = ref("");
const filteredEvaluationStudents = computed(() => {
    const q = evaluationSearch.value.toLowerCase().trim();
    if (!q) return students1.value || [];

    return (students1.value || []).filter((s) => {
        const text = [
            s.mssv,
            s.name,
            s.Lop,
            s.group,
            s.topic,
            s.title,
            s.score,
            s.note,
        ]
            .join(" ")
            .toLowerCase();

        return text.includes(q);
    });
});

function updateScore(student) {
    axios
        .post("/update-score", {
            MSSV: student.mssv,
            Diem: student.score,
        })
        .then((res) => {
            alert("Cập nhật điểm thành công!");
        })
        .catch((err) => {
            alert(err.response.data.error || "Cập nhật điểm thất bại!");
        });
    fetchStudents();
}
function updateNote(student) {
    axios
        .post("/update-note", {
            MSSV: student.mssv,
            GhiChu: student.note,
        })
        .then((res) => {
            alert("Cập nhật ghi chú thành công!");
        })
        .catch((err) => {
            alert(err.response.data.error || "Cập nhật ghi chú thất bại!");
        });
    fetchStudents();
}

const headerUser = computed(() => ({
    ...props.user,
    giang_vien: teacherMaGV.value
        ? {
              MaGV: teacherMaGV.value,
              sinh_viens: rawGuideStudents.value, // raw list with MSSV, Ho_va_Ten, ...
          }
        : null,
}));
</script>
