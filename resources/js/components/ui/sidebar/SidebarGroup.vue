<template>
  <div :class="cn('px-3 pb-1 pt-2', $attrs.class as any)">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
</script>