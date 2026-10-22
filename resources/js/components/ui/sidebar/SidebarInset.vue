<template>
    <main
        :class="[
            'flex flex-1 min-h-0 flex-col gap-4 overflow-y-auto transition-all duration-300 ease-in-out',
            isMobile ? 'ml-0 pb-16' : collapsed ? 'ml-0' : 'ml-64',
            $attrs.class as any,
        ]"
    >
        <slot />
    </main>
</template>

<script setup lang="ts">
import { inject, ref } from 'vue';

const collapsed = inject('sidebar-collapsed', ref(false));
const isMobile = inject('is-mobile', ref(false));
</script>