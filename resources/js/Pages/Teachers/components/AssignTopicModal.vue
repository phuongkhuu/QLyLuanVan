<template>
    <div
        v-if="show"
        class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
    >
        <div class="bg-white rounded shadow-lg w-[90%] max-w-2xl p-6">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">
                    Phân công đề tài
                </h3>
            </div>

            <div class="space-y-4">

                <div>
                    <label class="block font-medium mb-1">
                        Tên đề tài
                    </label>

                    <input
                        v-model="localForm.TenDT"
                        type="text"
                        class="w-full border rounded px-3 py-2"
                    />
                </div>

                <div>
                    <label class="block font-medium mb-1">
                        Mô tả
                    </label>

                    <textarea
                        v-model="localForm.MoTa"
                        rows="4"
                        class="w-full border rounded px-3 py-2"
                    ></textarea>
                </div>

                <div>
                    <label class="block font-medium mb-1">
                        Trạng thái
                    </label>

                    <select
                        v-model="selectedStatus"
                        class="w-full border rounded px-3 py-2"
                    >
                        <option value="Được tiếp tục">
                            Được tiếp tục
                        </option>

                        <option value="Cảnh cáo">
                            Cảnh cáo
                        </option>

                        <option value="Bị đình chỉ">
                            Bị đình chỉ
                        </option>

                        <option value="Ý kiến khác">
                            Ý kiến khác
                        </option>
                    </select>

                    <div
                        v-if="selectedStatus === 'Ý kiến khác'"
                        class="mt-2"
                    >
                        <input
                            v-model="localForm.TrangThai"
                            type="text"
                            placeholder="Nhập ý kiến..."
                            class="w-full border rounded px-3 py-2"
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">

                    <button
                        @click="$emit('close')"
                        class="px-4 py-2 border rounded"
                    >
                        Đóng
                    </button>

                    <button
                        @click="handleSave"
                        class="bg-indigo-500 text-white px-4 py-2 rounded"
                    >
                        Lưu
                    </button>

                </div>

            </div>
        </div>
    </div>
</template>

<script setup>
import { reactive, ref, watch } from "vue"

const props = defineProps({
    show: Boolean,
    formData: Object,
})

const emit = defineEmits([
    "save",
    "close",
])

const localForm = reactive({
    ...props.formData,
})

const selectedStatus = ref(localForm.TrangThai || "")

watch(selectedStatus, (val) => {
    if (val !== "Ý kiến khác") {
        localForm.TrangThai = val
    }
})

function handleSave() {
    emit("save", localForm)
}
</script>