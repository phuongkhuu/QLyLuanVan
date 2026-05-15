<template>
    <div
        class="overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-sm"
    >
        <!-- Table -->
        <div class="overflow-x-auto">
            <table
                class="w-full border-collapse"
            >
                <!-- Head -->
                <thead
                    class="bg-slate-50 border-b border-slate-200"
                >
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Mã đề tài
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Tên đề tài
                        </th>

                        <th
                            class="px-6 py-4 text-left text-sm font-bold text-slate-700"
                        >
                            Giảng viên
                        </th>
                        <th
                            class="px-6 py-4 text-center text-sm font-bold text-slate-700"
                        >
                            Trạng thái
                        </th>
                        <th
                            class="px-6 py-4 text-center text-sm font-bold text-slate-700"
                        >
                            Tải file nhiệm vụ ban đầu
                        </th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody>
                    <!-- Empty -->
                    <tr v-if="topics.length === 0">
                        <td
                            colspan="5"
                            class="py-14 text-center"
                        >
                            <div
                                class="flex flex-col items-center justify-center"
                            >
                                <div
                                    class="w-20 h-20 rounded-full bg-slate-100 flex items-center justify-center text-4xl mb-4"
                                >
                                    📂
                                </div>

                                <h3
                                    class="font-semibold text-slate-700 text-lg"
                                >
                                    Không có đề tài
                                </h3>

                                <p
                                    class="text-slate-500 text-sm mt-1"
                                >
                                    Hiện chưa có dữ liệu đề tài luận văn
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Data -->
                    <tr
                        v-for="(topic, i) in topics"
                        :key="topic.code || i"

                        class="border-b border-slate-100 hover:bg-blue-50/40 transition-all duration-200"
                    >
                        <!-- Code -->
                        <td class="px-6 py-5">
                            <div
                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-slate-100 text-slate-700 font-semibold text-sm"
                            >
                                📘
                                {{ topic.code }}
                            </div>
                        </td>

                        <!-- Title -->
                        <td class="px-6 py-5">
                            <div>
                                <h3
                                    class="font-semibold text-slate-800"
                                >
                                    {{ topic.title }}
                                </h3>

                                <p
                                    class="text-sm text-slate-500 mt-1"
                                >
                                    Luận văn tốt nghiệp 
                                </p>
                            </div>
                        </td>

                        <!-- Lecturer -->
                        <td class="px-6 py-5">
                            <div
                                class="flex items-center gap-3"
                            >
                                <!-- Name -->
                                <div>
                                    <p
                                        class="font-medium text-slate-700"
                                    >
                                        {{ topic.lecturer }}
                                    </p>

                                    <p
                                        class="text-xs text-slate-500"
                                    >
                                        Giảng viên hướng dẫn
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Status -->
                        <td
                            class="px-6 py-5 text-center"
                        >
                            <!-- Done -->
                            <div v-if="topic.status === 'Được tiếp tục'" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-emerald-100 text-emerald-700 font-semibold text-sm"
                            >
                                {{ topic.status }}
                            </div>
                            <div v-else-if="topic.status === 'Cảnh cáo'" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-yellow-100 text-yellow-700 font-semibold text-sm">
                                {{ topic.status }}
                            </div>
                            <div v-else-if="topic.status === 'Bị đình chỉ'" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-red-100 text-red-700 font-semibold text-sm">
                                {{ topic.status }}
                            </div>
                            <div v-else-if="topic.status === 'Ý kiến khác'" class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-slate-100 text-slate-700 font-semibold text-sm">
                                {{ topic.status }}
                            </div>
                        </td>
                        <td
                            class="px-6 py-5 text-center"
                        >
                            <button
                                @click="downloadTemplate(topic.code)"
                                class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-blue-100 text-blue-700 font-semibold text-sm"
                            >
                                Tải về
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
})
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
</script>