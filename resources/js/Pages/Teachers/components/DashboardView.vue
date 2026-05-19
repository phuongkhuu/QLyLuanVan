<template>
    <div
        class="w-full min-h-[78vh] px-8 py-8"
    >
        <!-- ===================================================== -->
        <!-- WELCOME -->
        <!-- ===================================================== -->
        <div
            class="relative overflow-hidden rounded-[36px] bg-gradient-to-r from-indigo-600 via-indigo-500 to-violet-500 p-10 shadow-2xl"
        >
            <!-- BACKGROUND -->
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl"
            ></div>

            <div
                class="absolute bottom-0 left-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"
            ></div>

            <!-- CONTENT -->
            <div
                class="relative z-10 flex flex-col xl:flex-row xl:items-center xl:justify-between gap-10"
            >
                <!-- LEFT -->
                <div
                    class="max-w-3xl"
                >
                  

                    <!-- TITLE -->
                    <h1
                        class="text-xl xl:text-3xl font-extrabold text-white leading-tight"
                    >
                        Xin chào,
                       

                        <span
                            class="text-white-300"
                        >
                            {{
                                user.name
                            }}
                        </span>
                    </h1>

                    <!-- DESC -->
                    <p
                        class="text-indigo-100 text-lg leading-relaxed mt-6 max-w-2xl"
                    >
                        Tổng cộng : {{students.length}} sinh viên đang hướng dẫn
                    </p>

                </div>

                
            </div>
        </div>
    </div>
</template>

<script setup>
import { usePage } from "@inertiajs/vue3";

const user =
    usePage().props.auth.user;

const {students } = defineProps(['students']);
</script>