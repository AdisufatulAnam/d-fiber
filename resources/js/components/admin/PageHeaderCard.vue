<script setup lang="ts">
defineProps<{
    title: string;
    icon?: string;
}>();
</script>

<template>
    <div class="flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
        <div class="flex items-center gap-2">
            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-blue-600">
                <slot name="icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/></svg>
                </slot>
            </div>
            <h2 class="text-sm font-semibold text-gray-900">{{ title }}</h2>
        </div>
        <div class="flex items-center gap-1.5">
            <slot name="actions" />
        </div>
    </div>
</template>
