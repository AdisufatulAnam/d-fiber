<script setup lang="ts">
const features = [
    {
        title: 'Cepat',
        desc: 'Nikmati koneksi internet dengan kecepatan tinggi untuk berbagai aktivitas online.',
        icon: 'bolt',
        color: 'text-blue-600 bg-blue-50 dark:bg-blue-950/50',
    },
    {
        title: 'Stabil',
        desc: 'Jaringan dipantau secara berkala untuk menjaga kualitas koneksi.',
        icon: 'signal',
        color: 'text-cyan-600 bg-cyan-50 dark:bg-cyan-950/50',
    },
    {
        title: 'Terjangkau',
        desc: 'Pilihan paket fleksibel sesuai kebutuhan dan budget Anda.',
        icon: 'wallet',
        color: 'text-emerald-600 bg-emerald-50 dark:bg-emerald-950/50',
    },
    {
        title: 'Monitoring 24/7',
        desc: 'Infrastruktur jaringan dipantau untuk membantu menjaga koneksi tetap aktif.',
        icon: 'chart',
        color: 'text-violet-600 bg-violet-50 dark:bg-violet-950/50',
    },
    {
        title: 'Teknisi Lokal',
        desc: 'Dukungan teknisi yang memahami kondisi jaringan di area Anda.',
        icon: 'tools',
        color: 'text-amber-600 bg-amber-50 dark:bg-amber-950/50',
    },
    {
        title: 'Customer Support',
        desc: 'Tim kami siap membantu melalui WhatsApp ketika Anda membutuhkan bantuan.',
        icon: 'headset',
        color: 'text-rose-600 bg-rose-50 dark:bg-rose-950/50',
    },
];
</script>

<template>
    <section id="keunggulan" class="scroll-mt-20 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Keunggulan
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Lebih dari Sekadar Internet
                </h2>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-400">
                    DFiber hadir dengan koneksi dan layanan yang dirancang untuk
                    kebutuhan Anda.
                </p>
            </div>

            <div
                class="mt-14 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3"
            >
                <div
                    v-for="feature in features"
                    :key="feature.title"
                    class="rounded-2xl border border-slate-200 bg-white p-6 transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-100/60 dark:border-slate-800 dark:bg-slate-900 dark:hover:border-blue-800 dark:hover:shadow-none"
                >
                    <div
                        class="flex h-11 w-11 items-center justify-center rounded-xl"
                        :class="feature.color"
                    >
                        <!-- bolt -->
                        <svg
                            v-if="feature.icon === 'bolt'"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                        </svg>
                        <!-- signal -->
                        <svg
                            v-else-if="feature.icon === 'signal'"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M4 20v-4M9 20v-8M14 20v-12M19 20V4" />
                        </svg>
                        <!-- wallet -->
                        <svg
                            v-else-if="feature.icon === 'wallet'"
                            class="h-5 w-5"
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
                        <!-- chart -->
                        <svg
                            v-else-if="feature.icon === 'chart'"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path d="M3 3v18h18" />
                            <path d="M7 15l4-5 3 3 5-7" />
                        </svg>
                        <!-- tools -->
                        <svg
                            v-else-if="feature.icon === 'tools'"
                            class="h-5 w-5"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        >
                            <path
                                d="M14.7 6.3a4 4 0 0 0-5.4 5.4L3 18l3 3 6.3-6.3a4 4 0 0 0 5.4-5.4l-2.6 2.6-2.1-2.1 2.7-2.5z"
                            />
                        </svg>
                        <!-- headset -->
                        <svg
                            v-else
                            class="h-5 w-5"
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
                    </div>

                    <h3
                        class="mt-5 text-base font-semibold text-slate-900 dark:text-white"
                    >
                        {{ feature.title }}
                    </h3>
                    <p
                        class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                    >
                        {{ feature.desc }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
