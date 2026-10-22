<script setup lang="ts">
// Drawer detail: slide dari kanan. Slot untuk konten body.
defineProps<{
    show: boolean;
    title: string;
    subtitle?: string;
}>();

const emit = defineEmits<{ (e: 'close'): void }>();
</script>

<template>
    <Teleport to="body">
        <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
            <div v-if="show" class="fixed inset-0 z-[999] bg-black/50" @click.self="emit('close')">
                <div class="drawer-panel absolute right-0 top-0 h-full w-full max-w-md bg-white shadow-2xl flex flex-col">
                    <!-- Header -->
                    <div class="flex items-center justify-between border-b border-gray-200 px-5 py-4 shrink-0">
                        <div class="flex items-center gap-3">
                            <div class="flex h-9 w-9 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                                <slot name="icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                </slot>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">{{ title }}</h3>
                                <p v-if="subtitle" class="text-xs text-gray-500">{{ subtitle }}</p>
                            </div>
                        </div>
                        <button @click="emit('close')" class="rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="flex-1 overflow-y-auto px-5 py-4 space-y-4">
                        <slot />
                    </div>

                    <!-- Footer (opsional) -->
                    <div v-if="$slots.footer" class="border-t border-gray-200 px-5 py-3 shrink-0">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.drawer-panel {
    animation: drawer-slide-in 0.3s ease-out;
}
@keyframes drawer-slide-in {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}
</style>
