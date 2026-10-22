<script setup lang="ts">
const faqs = [
    {
        q: 'Apakah DFiber unlimited?',
        a: 'Ya, paket DFiber dapat menggunakan internet tanpa batas kuota sesuai ketentuan layanan.',
    },
    {
        q: 'Berapa lama proses pemasangan?',
        a: 'Pemasangan dilakukan setelah coverage dan jadwal teknisi dikonfirmasi.',
    },
    {
        q: 'Apakah tersedia untuk bisnis?',
        a: 'Ya. DFiber menyediakan paket untuk kebutuhan rumah maupun bisnis.',
    },
    {
        q: 'Apakah bisa upgrade paket?',
        a: 'Bisa. Pelanggan dapat mengajukan perubahan paket kepada customer service.',
    },
    {
        q: 'Bagaimana jika internet mengalami gangguan?',
        a: 'Hubungi customer service DFiber melalui WhatsApp. Tim kami akan membantu melakukan pengecekan.',
    },
    {
        q: 'Apakah tersedia WiFi router?',
        a: 'Perangkat yang digunakan mengikuti paket dan ketentuan pemasangan yang berlaku.',
    },
];
</script>

<template>
    <section id="faq" class="scroll-mt-20 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    FAQ
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Pertanyaan yang Sering Ditanyakan
                </h2>
            </div>

            <div class="mt-12 space-y-3">
                <details
                    v-for="(faq, index) in faqs"
                    :key="faq.q"
                    class="group rounded-2xl border border-slate-200 bg-white open:border-blue-300 open:shadow-lg open:shadow-blue-100/50 dark:border-slate-800 dark:bg-slate-900 dark:open:border-blue-800 dark:open:shadow-none"
                    :open="index === 0"
                >
                    <summary
                        class="flex cursor-pointer list-none items-center justify-between gap-4 rounded-2xl px-5 py-4 text-sm font-semibold text-slate-900 sm:px-6 sm:py-5 sm:text-base dark:text-white [&::-webkit-details-marker]:hidden"
                    >
                        {{ faq.q }}
                        <svg
                            class="h-5 w-5 shrink-0 text-blue-600 transition-transform group-open:rotate-45 dark:text-blue-400"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                        >
                            <path d="M12 5v14M5 12h14" />
                        </svg>
                    </summary>
                    <p
                        class="px-5 pb-4 text-sm leading-relaxed text-slate-600 sm:px-6 sm:pb-5 dark:text-slate-400"
                    >
                        {{ faq.a }}
                    </p>
                </details>
            </div>
        </div>
    </section>
</template>
