<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import Button from '../components/ui/button/Button.vue';

/**
 * Layout panel admin — topbar sederhana tanpa menu/sidebar.
 */
defineProps<{
    admin: { name: string; email: string };
}>();

function logout() {
    router.post('/logout');
}
</script>

<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Topbar (tanpa menu) -->
        <header
            class="sticky top-0 z-40 border-b border-border bg-background/90 backdrop-blur"
        >
            <div
                class="mx-auto flex h-14 max-w-6xl items-center justify-between gap-4 px-4 sm:px-6"
            >
                <div class="flex items-center gap-2.5">
                    <span
                        class="grid h-8 w-8 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 text-white"
                    >
                        <svg
                            class="h-4 w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        >
                            <path d="M5 12.55a11 11 0 0 1 14.08 0" />
                            <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                            <circle
                                cx="12"
                                cy="20"
                                r="1.5"
                                fill="currentColor"
                                stroke="none"
                            />
                        </svg>
                    </span>
                    <div>
                        <p
                            class="text-sm leading-tight font-bold text-foreground"
                        >
                            DFiber
                        </p>
                        <p
                            class="text-[11px] leading-tight text-muted-foreground"
                        >
                            Admin Operasional
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="hidden text-right sm:block">
                        <p class="text-sm leading-tight font-medium">
                            {{ admin.name }}
                        </p>
                        <p
                            class="text-[11px] leading-tight text-muted-foreground"
                        >
                            {{ admin.email }}
                        </p>
                    </div>
                    <Button variant="outline" size="sm" @click="logout">
                        Keluar
                    </Button>
                </div>
            </div>
        </header>

        <main class="mx-auto max-w-6xl px-4 py-8 sm:px-6">
            <slot />
        </main>
    </div>
</template>
