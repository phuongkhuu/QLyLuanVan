<template>
    <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-6">
        <div class="flex items-start gap-4">
            <div
                class="w-14 h-14 rounded-2xl bg-yellow-100 text-yellow-700 flex items-center justify-center text-2xl font-bold"
            >
                !
            </div>

            <div>
                <p class="text-sm text-yellow-700 font-semibold">
                    Lưu ý trước khi bảo vệ
                </p>

                <ul class="text-slate-700 text-sm mt-2 space-y-2">
                    <li
                        v-for="requirement in requirements"
                        :key="requirement"
                    >
                        {{ requirement }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    requirements: {
        type: Array,
        default: () => [],
    },
});
</script>
