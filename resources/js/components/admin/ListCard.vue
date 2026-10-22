<script setup lang="ts">
// Layout kartu daftar: header kolom sticky + area scroll + slot baris.
// Root = flex column yang mengisi sisa tinggi (flex-1), scroll hanya di area konten.
defineProps<{
    columns: { label: string; class?: string }[];
    isEmpty: boolean;
    emptyText?: string;
    emptyIcon?: boolean;
}>();
</script>

<template>
    <div class="flex flex-1 flex-col min-h-0">
        <!-- Header kolom (sticky) -->
        <div class="flex shrink-0 items-center gap-3 rounded-lg border border-gray-300 bg-gray-100 px-4 py-2">
            <span class="shrink-0 w-8 text-center text-xs font-semibold text-gray-600">#</span>
            <span
                v-for="col in columns"
                :key="col.label"
                :class="['text-xs font-semibold text-gray-600', col.class || 'flex-1 min-w-0']"
            >{{ col.label }}</span>
            <span class="shrink-0 w-4"></span>
        </div>

        <!-- Area scroll -->
        <div class="flex-1 min-h-0 overflow-y-auto mt-1 pr-1">
            <div v-if="isEmpty" class="flex h-full items-center justify-center">
                <div class="text-center text-sm text-muted-foreground">
                    <svg v-if="emptyIcon" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-300"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                    <p class="mt-2">{{ emptyText || 'Belum ada data.' }}</p>
                </div>
            </div>
            <div v-else class="flex flex-col gap-2 pb-2">
                <slot />
            </div>
        </div>
    </div>
</template>
