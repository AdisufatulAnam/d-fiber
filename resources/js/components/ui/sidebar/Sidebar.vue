<template>
    <aside
        :class="[
            'fixed inset-y-0 left-0 z-50 flex flex-col bg-white text-gray-700 border-r border-gray-200 transition-transform duration-300 ease-in-out shadow-lg md:shadow-none',
            isMobile
                ? collapsed
                    ? '-translate-x-full w-72'
                    : 'translate-x-0 w-72'
                : collapsed
                  ? '-translate-x-full w-64'
                  : 'translate-x-0 w-64',
            $attrs.class as any,
        ]"
    >
        <slot />
    </aside>
</template>

<script setup lang="ts">
import { inject, ref } from 'vue';

const collapsed = inject('sidebar-collapsed', ref(false));
const isMobile = inject('is-mobile', ref(false));
</script>