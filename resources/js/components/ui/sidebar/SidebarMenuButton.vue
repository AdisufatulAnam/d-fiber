<template>
  <component
    :is="as"
    :class="cn(
      'flex w-full items-center gap-2 overflow-hidden rounded-md p-2 text-left text-sm outline-none transition-colors hover:bg-sidebar-accent hover:text-sidebar-accent-foreground focus-visible:ring-2 focus-visible:ring-sidebar-ring [&>span:last-child]:truncate [&>svg]:size-4 [&>svg]:shrink-0',
      active
        ? 'bg-sidebar-accent font-medium text-sidebar-accent-foreground'
        : 'text-sidebar-foreground/80',
      $attrs.class as any
    )"
  >
    <slot />
  </component>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';

withDefaults(
  defineProps<{
    active?: boolean;
    as?: string;
  }>(),
  {
    active: false,
    as: 'button',
  },
);
</script>