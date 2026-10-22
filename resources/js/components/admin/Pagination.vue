<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    pagination: {
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    perPage: number;
}>();

const emit = defineEmits<{
    (e: 'update:perPage', value: number): void;
}>();

const onChange = (e: Event) => {
    emit('update:perPage', Number((e.target as HTMLSelectElement).value));
};
</script>

<template>
    <div class="mt-auto flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
        <div class="flex items-center gap-2">
            <span class="text-xs text-muted-foreground">Tampilkan</span>
            <select :value="perPage" @change="onChange"
                class="h-7 rounded-md border border-gray-300 bg-white px-2 text-xs shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500">
                <option :value="10">10</option>
                <option :value="15">15</option>
                <option :value="25">25</option>
                <option :value="50">50</option>
            </select>
            <span class="text-xs text-muted-foreground">dari {{ pagination.total }} data</span>
        </div>
        <div class="flex items-center gap-1">
            <span class="mr-2 text-xs text-muted-foreground">Hal. {{ pagination.current_page }}/{{ pagination.last_page }}</span>
            <Link v-if="pagination.prev_page_url" :href="pagination.prev_page_url"
                class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </Link>
            <span v-else class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-gray-50 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
            </span>
            <Link v-if="pagination.next_page_url" :href="pagination.next_page_url"
                class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </Link>
            <span v-else class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-gray-50 text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
            </span>
        </div>
    </div>
</template>
