<template>
  <div :class="cn('flex flex-col gap-2 overflow-auto', $attrs.class as any)">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
</script>