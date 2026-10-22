<template>
  <div :class="cn('min-h-svh bg-muted/30', $attrs.class as any)">
    <slot />
  </div>
</template>

<script setup lang="ts">
import { cn } from '@/lib/utils';
</script>