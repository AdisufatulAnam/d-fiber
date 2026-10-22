<script setup lang="ts">
import type { ClassValue } from 'clsx';
import { computed } from 'vue';
import { cn } from '../../../lib/utils';

const props = defineProps<{ class?: ClassValue }>();

const classes = computed(() =>
    cn('text-sm text-muted-foreground', props.class),
);
</script>

<template>
    <p :class="classes"><slot /></p>
</template>
