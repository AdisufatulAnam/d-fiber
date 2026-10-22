<script setup lang="ts">
// Field detail: label kecil + value dalam kotak bg-gray-100.
defineProps<{
    label: string;
}>();
</script>

<template>
    <div>
        <p class="mb-1 flex items-center gap-1.5 text-xs font-medium text-gray-500">
            <span v-if="$slots.icon" class="inline-flex text-gray-400"><slot name="icon" /></span>
            {{ label }}
        </p>
        <div class="rounded-lg bg-gray-100 px-3 py-2.5">
            <slot />
        </div>
    </div>
</template>
