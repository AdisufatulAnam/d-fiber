<script setup lang="ts">
import { waLink } from './wa';

const contactHref = waLink(
    'Halo DFiber, saya ingin menanyakan layanan internet.',
);

const chips = [
    { label: 'Kecepatan hingga 100 Mbps', icon: 'bolt' },
    { label: 'WiFi Stabil 24 Jam', icon: 'wifi' },
    { label: 'Untuk Rumah & Bisnis', icon: 'home' },
    { label: 'Customer Support Siap Membantu', icon: 'headset' },
];

const stats = [
    { value: '500+', label: 'Pelanggan Aktif' },
    { value: '99%', label: 'Network Uptime' },
    { value: '24/7', label: 'Monitoring Jaringan' },
    { value: '100 Mbps', label: 'Kecepatan Hingga' },
];
</script>

<template>
    <section
        id="beranda"
        class="relative overflow-hidden bg-[#f8fbff] text-slate-900"
    >
        <!-- Latar radial (seperti referensi: cyan kanan-atas, biru kiri-bawah) -->
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div
                class="absolute -top-40 -right-40 h-[520px] w-[520px] rounded-full bg-cyan-100 blur-[130px]"
            ></div>
            <div
                class="absolute -bottom-48 -left-32 h-96 w-96 rounded-full bg-blue-100 blur-[120px]"
            ></div>
            <div
                class="absolute top-1/3 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-blue-50 blur-[100px]"
            ></div>
        </div>

        <div
            class="relative mx-auto grid max-w-7xl items-center gap-10 px-4 pt-14 pb-14 sm:px-6 lg:grid-cols-2 lg:gap-12 lg:px-8 lg:pt-20 lg:pb-20"
        >
            <!-- Copy -->
            <div>
                <span
                    class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-3.5 py-1.5 text-xs font-bold text-blue-600"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-500 opacity-75"
                        ></span>
                        <span
                            class="relative inline-flex h-2 w-2 rounded-full bg-emerald-500"
                        ></span>
                    </span>
                    Internet Lokal Pilihan Anda
                </span>

                <h1
                    class="mt-6 text-4xl leading-[1.1] font-extrabold tracking-tight text-slate-900 sm:text-5xl lg:text-6xl"
                >
                    Internet Cepat, Stabil,
                    <span
                        class="block bg-gradient-to-br from-blue-600 to-cyan-500 bg-clip-text text-transparent"
                    >
                        Tanpa Ribet.
                    </span>
                </h1>

                <p
                    class="mt-5 max-w-xl text-base leading-relaxed text-slate-600 sm:text-lg"
                >
                    Koneksi internet cepat, stabil, dan terjangkau untuk
                    kebutuhan rumah, keluarga, pelajar, gamer, hingga bisnis.
                </p>

                <div class="mt-7 flex flex-wrap gap-2.5 sm:gap-3">
                    <a
                        href="#paket"
                        class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 px-4 py-2.5 text-[13px] font-bold text-white shadow-md shadow-blue-600/25 transition-all hover:-translate-y-0.5 sm:flex-none sm:gap-2 sm:rounded-xl sm:px-6 sm:py-3 sm:text-sm sm:shadow-lg sm:shadow-blue-600/25 hover:sm:shadow-xl"
                    >
                        Lihat Paket
                        <svg
                            class="h-3.5 w-3.5 sm:h-4 sm:w-4"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </a>
                    <a
                        :href="contactHref"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-lg border border-slate-200 bg-white px-4 py-2.5 text-[13px] font-semibold text-slate-900 transition-colors hover:border-blue-600 hover:text-blue-600 sm:flex-none sm:gap-2 sm:rounded-xl sm:px-6 sm:py-3 sm:text-sm"
                    >
                        Hubungi Kami
                    </a>
                </div>

                <!-- Feature chips -->
                <ul class="mt-8 grid grid-cols-1 gap-3 sm:grid-cols-2">
                    <li
                        v-for="chip in chips"
                        :key="chip.label"
                        class="flex items-center gap-2.5 rounded-xl border border-slate-200/80 bg-white px-3.5 py-3 text-sm font-semibold text-slate-700 shadow-sm"
                    >
                        <span
                            class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600"
                        >
                            <!-- bolt -->
                            <svg
                                v-if="chip.icon === 'bolt'"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                            <!-- wifi -->
                            <svg
                                v-else-if="chip.icon === 'wifi'"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                            >
                                <path d="M5 12.55a11 11 0 0 1 14.08 0" />
                                <path d="M1.42 9a16 16 0 0 1 21.16 0" />
                                <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                                <circle
                                    cx="12"
                                    cy="20"
                                    r="1.5"
                                    fill="currentColor"
                                    stroke="none"
                                />
                            </svg>
                            <!-- home -->
                            <svg
                                v-else-if="chip.icon === 'home'"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 10.5 12 3l9 7.5" />
                                <path
                                    d="M5 9.5V20a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V9.5"
                                />
                                <path d="M10 21v-6h4v6" />
                            </svg>
                            <!-- headset -->
                            <svg
                                v-else
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M4 14v-2a8 8 0 0 1 16 0v2" />
                                <path
                                    d="M4 14h3v5H5a1 1 0 0 1-1-1v-4zM20 14h-3v5h2a1 1 0 0 0 1-1v-4z"
                                />
                            </svg>
                        </span>
                        {{ chip.label }}
                    </li>
                </ul>
            </div>

            <!-- Speed panel (kartu gelap — sama seperti referensi) -->
            <div class="relative">
                <div
                    class="rounded-[30px] bg-gradient-to-br from-slate-900 to-blue-900 p-6 text-white shadow-2xl shadow-slate-900/25 sm:p-8"
                >
                    <p
                        class="text-xs font-semibold tracking-widest text-blue-300 uppercase"
                    >
                        Kecepatan Hingga
                    </p>
                    <p
                        class="mt-2 text-5xl font-extrabold tracking-tight text-white sm:text-6xl"
                    >
                        100
                        <span
                            class="text-2xl font-bold text-slate-400 sm:text-3xl"
                            >Mbps</span
                        >
                    </p>
                    <p class="mt-3 text-sm leading-relaxed text-slate-300">
                        Koneksi cepat untuk streaming, gaming, meeting dan
                        kebutuhan bisnis.
                    </p>

                    <!-- Progress bar -->
                    <div
                        class="mt-6 h-2 overflow-hidden rounded-full bg-white/10"
                    >
                        <span
                            class="block h-full w-[90%] rounded-full bg-gradient-to-r from-sky-400 to-cyan-400"
                        ></span>
                    </div>

                    <div
                        class="mt-5 flex flex-col gap-3 rounded-2xl bg-white p-4 text-slate-900 shadow-lg sm:flex-row sm:items-center sm:justify-between"
                    >
                        <div class="flex items-center gap-3">
                            <span
                                class="flex h-9 w-9 items-center justify-center rounded-lg bg-blue-50 text-blue-600"
                            >
                                <svg
                                    class="h-5 w-5"
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
                                <p class="text-sm font-bold text-slate-900">
                                    Network Operational
                                </p>
                                <p class="text-xs text-slate-500">
                                    Sistem berjalan normal
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-600"
                        >
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                            ></span>
                            Online
                        </span>
                    </div>

                    <!-- Stats -->
                    <dl class="mt-5 grid grid-cols-2 gap-3">
                        <div
                            v-for="stat in stats"
                            :key="stat.label"
                            class="rounded-2xl bg-white/10 p-4"
                        >
                            <dd
                                class="text-xl font-bold text-white sm:text-2xl"
                            >
                                {{ stat.value }}
                            </dd>
                            <dt class="mt-1 text-xs text-slate-400">
                                {{ stat.label }}
                            </dt>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </section>
</template>
