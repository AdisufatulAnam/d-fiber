<template>
  <button
    @click="toggle"
    class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-border bg-background text-sm font-medium shadow-sm transition-colors hover:bg-accent hover:text-accent-foreground"
  >
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M9 3v18"/></svg>
  </button>
</template>

<script setup lang="ts">
import { inject } from 'vue';

const toggle = inject<() => void>('sidebar-toggle', () => {});
</script>