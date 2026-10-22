<script setup lang="ts">
import { waLink } from './wa';

const benefits = [
    'Instalasi mudah',
    'Proses cepat',
    'Support teknisi',
    'Paket unlimited',
];

const claimHref = waLink('Halo DFiber, saya ingin klaim promo pasang baru');
</script>

<template>
    <section class="bg-slate-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div
                class="relative overflow-hidden rounded-3xl border border-blue-100 bg-gradient-to-br from-blue-50 to-cyan-50 p-6 sm:p-10 lg:p-12"
            >
                <div
                    class="pointer-events-none absolute inset-0"
                    aria-hidden="true"
                >
                    <div
                        class="absolute -top-20 -right-20 h-64 w-64 rounded-full bg-blue-100/70 blur-3xl"
                    ></div>
                    <div
                        class="absolute -bottom-24 -left-16 h-72 w-72 rounded-full bg-cyan-100/70 blur-3xl"
                    ></div>
                </div>

                <div class="relative grid items-center gap-8 lg:grid-cols-2">
                    <div>
                        <span
                            class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1.5 text-xs font-bold text-blue-600 shadow-sm"
                        >
                            🚀 Promo Spesial
                        </span>
                        <h2
                            class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                        >
                            Pasang Baru DFiber
                        </h2>
                        <p
                            class="mt-3 text-sm leading-relaxed text-slate-600 sm:text-base"
                        >
                            Nikmati penawaran khusus untuk pelanggan baru.
                        </p>

                        <ul class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <li
                                v-for="item in benefits"
                                :key="item"
                                class="flex items-center gap-2.5 rounded-xl border border-white bg-white/80 px-3.5 py-3 text-sm font-semibold text-slate-700 shadow-sm"
                            >
                                <svg
                                    class="h-4 w-4 shrink-0 text-emerald-500"
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
                    </div>

                    <div
                        class="rounded-3xl border border-white bg-white p-6 shadow-xl shadow-blue-100 sm:p-8 lg:max-w-sm lg:justify-self-end"
                    >
                        <p
                            class="text-xs font-semibold tracking-widest text-blue-600 uppercase"
                        >
                            Mulai dari
                        </p>
                        <div class="mt-2 flex items-end gap-1">
                            <span
                                class="mb-2 text-sm font-medium text-slate-500"
                                >Rp</span
                            >
                            <span
                                class="text-4xl font-extrabold tracking-tight text-slate-900 sm:text-5xl"
                                >100.000</span
                            >
                            <span
                                class="mb-1.5 text-sm font-medium text-slate-500"
                                >/bulan</span
                            >
                        </div>

                        <!-- Biaya PSB -->
                        <div
                            class="mt-4 flex items-center justify-between gap-3 rounded-xl border border-blue-100 bg-blue-50 px-4 py-3"
                        >
                            <span class="text-sm font-medium text-slate-600">
                                Biaya Pemasangan Baru (PSB)
                            </span>
                            <span class="text-sm font-bold text-blue-600">
                                Rp150.000
                            </span>
                        </div>

                        <a
                            :href="claimHref"
                            target="_blank"
                            rel="noopener"
                            class="mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5"
                        >
                            Klaim Promo
                        </a>
                        <p class="mt-4 text-center text-xs text-slate-500">
                            Syarat dan ketentuan berlaku.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
