<template>
    <div class="min-h-[50vh] flex items-center justify-center">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-indigo-600">
                Xin chào, {{ user.name }}
            </h2>

            <p class="text-gray-600 mt-2">
                Chúc bạn một ngày làm việc hiệu quả.
            </p>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

const user = usePage().props.auth.user;
</script>