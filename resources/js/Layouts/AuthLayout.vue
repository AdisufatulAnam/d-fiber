<script setup lang="ts">
/**
 * Layout tengah untuk halaman autentikasi (tanpa sidebar/menu).
 */
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-slate-50 px-4 py-10"
    >
        <!-- Brand -->
        <div class="mb-8 flex flex-col items-center gap-3 text-center">
            <span
                class="grid h-12 w-12 place-items-center rounded-2xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-600/25"
            >
                <svg
                    class="h-6 w-6"
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
                <p class="text-lg font-extrabold tracking-tight text-slate-900">
                    DFiber
                </p>
                <p class="text-xs font-medium text-slate-500">
                    Panel Admin Operasional
                </p>
            </div>
        </div>

        <div class="w-full max-w-md"><slot /></div>

        <p class="mt-8 text-center text-xs text-slate-400">
            © 2026 DFiber &bull; Piyungan, Bantul, D.I. Yogyakarta
        </p>
    </div>
</template>
