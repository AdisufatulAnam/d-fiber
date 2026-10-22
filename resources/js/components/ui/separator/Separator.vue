<template>
  <div
    role="separator"
    :class="cn(
      'shrink-0 bg-border',
      orientation === 'horizontal' ? 'h-[1px] w-full' : 'h-4 w-[1px]',
      $attrs.class as any
    )"
  />
</template>

<script setup lang="ts">
withDefaults(defineProps<{ orientation?: 'horizontal' | 'vertical' }>(), {
  orientation: 'horizontal',
});

import { cn } from '@/lib/utils';
</script>