<script setup lang="ts">
import type { ClassValue } from 'clsx';
import { computed } from 'vue';
import { cn } from '../../../lib/utils';

const props = defineProps<{
    class?: ClassValue;
}>();

const classes = computed(() =>
    cn(
        'text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70',
        props.class,
    ),
);
</script>

<template>
    <!-- Atribut `for` diteruskan otomatis via attrs fallthrough -->
    <label :class="classes"><slot /></label>
</template>
