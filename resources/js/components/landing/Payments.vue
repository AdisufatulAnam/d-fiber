<script setup lang="ts">
const methods = [
    {
        title: 'Transfer Bank',
        desc: 'Transfer melalui rekening bank.',
        icon: 'bank',
    },
    {
        title: 'E-Wallet',
        desc: 'Pembayaran melalui e-wallet.',
        icon: 'wallet',
    },
    {
        title: 'Virtual Account',
        desc: 'Bayar menggunakan VA.',
        icon: 'card',
    },
    {
        title: 'Pembayaran Langsung',
        desc: 'Pembayaran langsung kepada petugas.',
        icon: 'cash',
    },
];
</script>

<template>
    <section class="bg-slate-50 py-16 sm:py-24 dark:bg-slate-950">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Pembayaran
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Bayar Tagihan dengan Mudah
                </h2>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-400">
                    Gunakan metode pembayaran yang paling nyaman.
                </p>
            </div>

            <div
                class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
            >
                <div
                    v-for="method in methods"
                    :key="method.title"
                    class="rounded-2xl border border-slate-200 bg-white p-6 text-center transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100/50 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-700 dark:hover:shadow-none"
                >
                    <span
                        class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-600/20"
                    >
                        <!-- bank -->
                        <svg
                            v-if="method.icon === 'bank'"
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 10 12 4l9 6" />
                            <path d="M5 10v8M9.5 10v8M14.5 10v8M19 10v8" />
                            <path d="M3 21h18" />
                        </svg>
                        <!-- wallet -->
                        <svg
                            v-else-if="method.icon === 'wallet'"
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M19 7V5a2 2 0 0 0-2-2H5a2 2 0 0 0 0 4h14a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5"
                            />
                            <path d="M17 12h.01" />
                        </svg>
                        <!-- card -->
                        <svg
                            v-else-if="method.icon === 'card'"
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="2" y="5" width="20" height="14" rx="2.5" />
                            <path d="M2 10h20" />
                        </svg>
                        <!-- cash -->
                        <svg
                            v-else
                            class="h-6 w-6"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <rect x="2" y="6" width="20" height="12" rx="2.5" />
                            <circle cx="12" cy="12" r="2.5" />
                            <path d="M6 12h.01M18 12h.01" />
                        </svg>
                    </span>

                    <h3
                        class="mt-5 text-base font-semibold text-slate-900 dark:text-white"
                    >
                        {{ method.title }}
                    </h3>
                    <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">
                        {{ method.desc }}
                    </p>
                </div>
            </div>

            <div class="mt-10 text-center">
                <a
                    href="#kontak"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl border border-slate-300 bg-white px-7 py-3.5 text-sm font-semibold text-slate-900 transition-colors hover:bg-slate-100 sm:w-auto dark:border-slate-600 dark:bg-transparent dark:text-white dark:hover:bg-slate-800"
                >
                    Cek Tagihan
                </a>
            </div>
        </div>
    </section>
</template>
