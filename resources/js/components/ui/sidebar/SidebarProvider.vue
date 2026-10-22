<template>
    <div class="flex h-svh w-full overflow-hidden">
        <!-- Backdrop overlay (mobile only) -->
        <Teleport to="body">
            <Transition
                enter-active-class="transition-opacity duration-200"
                leave-active-class="transition-opacity duration-200"
                enter-from-class="opacity-0"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="isMobile && !collapsed"
                    class="fixed inset-0 z-40 bg-black/40 backdrop-blur-sm"
                    @click="toggle"
                />
            </Transition>
        </Teleport>

        <slot />
    </div>
</template>

<script setup lang="ts">
import { provide, ref, onMounted, onUnmounted } from 'vue';

const collapsed = ref(false);
const isMobile = ref(false);

const toggle = () => (collapsed.value = !collapsed.value);

const checkMobile = () => {
    isMobile.value = window.innerWidth < 768;
    if (isMobile.value) {
        collapsed.value = true;
    }
};

let timeoutId: ReturnType<typeof setTimeout> | null = null;
const handleResize = () => {
    if (timeoutId) clearTimeout(timeoutId);
    timeoutId = setTimeout(checkMobile, 150);
};

onMounted(() => {
    checkMobile();
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    if (timeoutId) clearTimeout(timeoutId);
});

provide('sidebar-collapsed', collapsed);
provide('sidebar-toggle', toggle);
provide('is-mobile', isMobile);
</script>