<template>
  <ul :class="cn('flex w-full min-w-0 flex-col gap-1', $attrs.class as any)">
    <slot />
  </ul>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
</script>