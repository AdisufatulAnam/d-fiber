<script setup lang="ts">
import { waLink } from './wa';

const plans = [
    {
        name: 'DFiber Basic',
        price: '100.000',
        speed: 'Hingga 10 Mbps',
        featured: false,
        cta: 'Pilih Paket',
        features: [
            'Unlimited Internet',
            'Cocok untuk 1–3 perangkat',
            'Support WhatsApp',
            'Monitoring jaringan',
        ],
    },
    {
        name: 'DFiber Family',
        price: '150.000',
        speed: 'Hingga 20 Mbps',
        featured: true,
        cta: 'Pilih Paket',
        features: [
            'Unlimited Internet',
            'Cocok untuk 3–7 perangkat',
            'Streaming & Social Media',
            'Support WhatsApp',
            'Monitoring jaringan',
        ],
    },
    {
        name: 'DFiber Pro',
        price: '200.000',
        speed: 'Hingga 30 Mbps',
        featured: false,
        cta: 'Pilih Paket',
        features: [
            'Unlimited Internet',
            'Cocok untuk keluarga',
            'Streaming HD',
            'Gaming',
            'Work From Home',
            'Prioritas support',
        ],
    },
    {
        name: 'DFiber Business',
        price: '300.000',
        speed: 'Hingga 50 Mbps',
        featured: false,
        cta: 'Hubungi Kami',
        features: [
            'Unlimited Internet',
            'Cocok untuk usaha',
            'CCTV & Cloud System',
            'Meeting Online',
            'Support Prioritas',
        ],
    },
];
</script>

<template>
    <section id="paket" class="scroll-mt-20 bg-slate-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Paket Internet
                </span>
                <h2
                    class="mt-4 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                >
                    Pilih Paket Sesuai Kebutuhan
                </h2>
                <p class="mt-4 text-base text-slate-600">
                    Mulai dari kebutuhan ringan hingga keluarga dan bisnis.
                </p>
            </div>

            <div
                class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 xl:grid-cols-4"
            >
                <div
                    v-for="plan in plans"
                    :key="plan.name"
                    class="relative flex flex-col rounded-3xl border bg-white p-6 transition-all sm:p-7"
                    :class="
                        plan.featured
                            ? 'border-blue-600 shadow-2xl ring-1 shadow-blue-600/15 ring-blue-600 sm:-translate-y-2'
                            : 'border-slate-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-300/60'
                    "
                >
                    <span
                        v-if="plan.featured"
                        class="absolute -top-3 left-1/2 -translate-x-1/2 rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold whitespace-nowrap text-white shadow-lg"
                    >
                        ⭐ Paling Populer
                    </span>

                    <span
                        v-if="plan.featured"
                        class="mb-4 inline-flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 text-lg"
                        aria-hidden="true"
                        >⭐</span
                    >

                    <h3 class="text-lg font-semibold text-slate-900">
                        {{ plan.name }}
                    </h3>

                    <div class="mt-4 flex items-end gap-1">
                        <span class="mb-2 text-sm font-medium text-slate-500">
                            Rp
                        </span>
                        <span
                            class="text-3xl font-extrabold tracking-tight text-slate-900"
                        >
                            {{ plan.price }}
                        </span>
                        <span class="mb-1.5 text-sm font-medium text-slate-500">
                            /bulan
                        </span>
                    </div>

                    <p class="mt-2 text-sm font-semibold text-blue-600">
                        {{ plan.speed }}
                    </p>

                    <ul
                        class="mt-6 flex-1 space-y-3 border-t border-slate-100 pt-5"
                    >
                        <li
                            v-for="feature in plan.features"
                            :key="feature"
                            class="flex items-start gap-2.5 text-sm text-slate-700"
                        >
                            <svg
                                class="mt-0.5 h-4 w-4 shrink-0 text-emerald-500"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2.5"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M20 6L9 17l-5-5" />
                            </svg>
                            {{ feature }}
                        </li>
                    </ul>

                    <a
                        :href="
                            waLink(
                                `Halo DFiber, saya ingin berlangganan paket ${plan.name}`,
                            )
                        "
                        target="_blank"
                        rel="noopener"
                        class="mt-7 inline-flex items-center justify-center rounded-xl px-5 py-3 text-sm font-bold transition-all"
                        :class="
                            plan.featured
                                ? 'bg-gradient-to-br from-blue-600 to-cyan-500 text-white shadow-lg shadow-blue-600/25 hover:-translate-y-0.5'
                                : 'border border-slate-300 text-slate-900 hover:border-blue-600 hover:text-blue-600'
                        "
                    >
                        {{ plan.cta }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>
