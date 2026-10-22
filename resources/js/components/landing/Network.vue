<script setup lang="ts">
const services = [
    { name: 'Internet', type: 'Public Network', status: 'Operational' },
    { name: 'Core Network', type: 'Infrastructure', status: 'Operational' },
    { name: 'Customer Network', type: 'Access Network', status: 'Operational' },
];
</script>

<template>
    <section class="bg-white py-16 sm:py-24 dark:bg-slate-900">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Network Monitoring
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Jaringan DFiber Dipantau Secara Berkala
                </h2>
                <p class="mt-4 text-base text-slate-600 dark:text-slate-400">
                    Kami menggunakan sistem monitoring jaringan untuk membantu
                    memastikan layanan berjalan dengan baik.
                </p>
            </div>

            <ul class="mt-10 space-y-4">
                <li
                    v-for="service in services"
                    :key="service.name"
                    class="flex flex-col gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:flex-row sm:items-center sm:justify-between dark:border-slate-700 dark:bg-slate-800/50"
                >
                    <div class="flex items-center gap-4">
                        <span
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-50 text-blue-600 dark:bg-blue-950/60 dark:text-blue-400"
                        >
                            <svg
                                class="h-5 w-5"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <circle cx="12" cy="12" r="9" />
                                <path
                                    d="M3.6 9h16.8M3.6 15h16.8M12 3a15 15 0 0 1 0 18M12 3a15 15 0 0 0 0 18"
                                />
                            </svg>
                        </span>
                        <div>
                            <p
                                class="text-sm font-semibold text-slate-900 dark:text-white"
                            >
                                {{ service.name }}
                                <span
                                    class="ml-1 font-normal text-slate-500 dark:text-slate-400"
                                >
                                    {{ service.type }}
                                </span>
                            </p>
                            <p
                                class="text-xs text-slate-500 dark:text-slate-400"
                            >
                                Status layanan terpantau normal
                            </p>
                        </div>
                    </div>

                    <span
                        class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-500/15 px-3 py-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400"
                    >
                        <span
                            class="h-1.5 w-1.5 rounded-full bg-emerald-500"
                        ></span>
                        {{ service.status }}
                    </span>
                </li>
            </ul>

            <div
                class="mt-6 flex flex-col gap-3 rounded-2xl border border-blue-200 bg-blue-50 p-5 sm:flex-row sm:items-center dark:border-blue-900 dark:bg-blue-950/40"
            >
                <span class="text-2xl" aria-hidden="true">📢</span>
                <div>
                    <p
                        class="text-sm font-semibold text-blue-900 dark:text-blue-200"
                    >
                        Informasi Gangguan
                    </p>
                    <p class="text-sm text-blue-800/80 dark:text-blue-300/80">
                        Tidak ada gangguan besar yang sedang berlangsung.
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
