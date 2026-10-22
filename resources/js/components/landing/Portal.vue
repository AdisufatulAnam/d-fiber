<script setup lang="ts">
const tabs = ['Tagihan', 'Invoice', 'Riwayat', 'Bantuan'];

const benefits = [
    'Melihat tagihan',
    'Mengecek pembayaran',
    'Riwayat pembayaran',
    'Mendapatkan invoice',
    'Melaporkan gangguan',
    'Customer service',
    'Informasi promo',
];
</script>

<template>
    <section class="bg-white py-16 sm:py-24 dark:bg-slate-900">
        <div
            class="mx-auto grid max-w-7xl items-center gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:gap-14 lg:px-8"
        >
            <!-- Mock portal card -->
            <div class="order-2 lg:order-1">
                <div
                    class="mx-auto max-w-md rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-xl shadow-slate-200/60 sm:p-6 dark:border-slate-700 dark:bg-slate-800/60 dark:shadow-none"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-sm font-bold text-white"
                            >
                                DF
                            </span>
                            <div>
                                <p
                                    class="text-sm font-semibold text-slate-900 dark:text-white"
                                >
                                    DFiber Customer
                                </p>
                                <p
                                    class="flex items-center gap-1.5 text-xs text-emerald-600 dark:text-emerald-400"
                                >
                                    <span
                                        class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                                    ></span>
                                    Aktif
                                </p>
                            </div>
                        </div>
                        <span
                            class="rounded-full bg-blue-50 px-2.5 py-1 text-[11px] font-semibold text-blue-700 dark:bg-blue-950/60 dark:text-blue-300"
                        >
                            Portal
                        </span>
                    </div>

                    <div
                        class="mt-5 rounded-2xl bg-white p-5 shadow-sm dark:bg-slate-900"
                    >
                        <p
                            class="text-xs font-medium text-slate-500 dark:text-slate-400"
                        >
                            Tagihan Bulan Ini
                        </p>
                        <p
                            class="mt-1 text-3xl font-extrabold text-slate-900 dark:text-white"
                        >
                            Rp150.000
                        </p>
                        <div
                            class="mt-4 h-2 w-full overflow-hidden rounded-full bg-slate-100 dark:bg-slate-700"
                        >
                            <div
                                class="h-full w-2/3 rounded-full bg-gradient-to-r from-blue-600 to-cyan-500"
                            ></div>
                        </div>
                        <p
                            class="mt-2 text-[11px] text-slate-500 dark:text-slate-400"
                        >
                            Jatuh tempo: akhir bulan
                        </p>
                    </div>

                    <div class="mt-4 grid grid-cols-4 gap-2">
                        <div
                            v-for="(tab, index) in tabs"
                            :key="tab"
                            class="rounded-xl px-1 py-3 text-center text-[11px] font-semibold transition-colors sm:text-xs"
                            :class="
                                index === 0
                                    ? 'bg-blue-600 text-white shadow-md shadow-blue-600/25'
                                    : 'bg-white text-slate-600 dark:bg-slate-900 dark:text-slate-300'
                            "
                        >
                            {{ tab }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Copy -->
            <div class="order-1 lg:order-2">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Portal Pelanggan
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Semua Kebutuhan Internet dalam Satu Layanan
                </h2>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-400">
                    Kelola layanan DFiber dengan lebih mudah melalui sistem
                    pelanggan.
                </p>

                <ul class="mt-7 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <li
                        v-for="item in benefits"
                        :key="item"
                        class="flex items-center gap-2.5 text-sm text-slate-700 dark:text-slate-300"
                    >
                        <svg
                            class="h-4 w-4 shrink-0 text-blue-600 dark:text-blue-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M20 6L9 17l-5-5" />
                        </svg>
                        {{ item }}
                    </li>
                </ul>

                <a
                    href="#kontak"
                    class="mt-8 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 sm:w-auto"
                >
                    Login Pelanggan
                </a>
            </div>
        </div>
    </section>
</template>
