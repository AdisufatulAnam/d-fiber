<script setup lang="ts">
import { isGlobalLoading } from '@/lib/global-loading';
</script>

<template>
    <Transition
        enter-active-class="transition-opacity duration-200 ease-out"
        enter-from-class="opacity-0"
        leave-active-class="transition-opacity duration-150 ease-in"
        leave-to-class="opacity-0"
    >
        <div
            v-if="isGlobalLoading"
            class="fixed inset-0 z-[9999] flex items-center justify-center bg-slate-950/40 backdrop-blur-sm"
            role="status"
            aria-busy="true"
            aria-label="Memuat"
        >
            <div
                class="flex flex-col items-center gap-3 rounded-2xl bg-white px-8 py-7 shadow-2xl dark:bg-slate-900"
            >
                <svg
                    class="size-8 animate-spin text-slate-700 dark:text-slate-200"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    aria-hidden="true"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    />
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
                    />
                </svg>
                <p
                    class="text-sm font-medium text-slate-600 dark:text-slate-300"
                >
                    Memuat…
                </p>
            </div>
        </div>
    </Transition>
</template>
