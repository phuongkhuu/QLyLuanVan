<template>
    <div class="mb-8">
        <p class="text-sm text-slate-500">Tên đề tài</p>
        <h2 class="text-3xl font-bold text-slate-800 mt-2 leading-relaxed">
            {{ review.topic }}
        </h2>
    </div>
</template>

<script setup>
defineProps({
    review: Object,
});
</script>