<script setup lang="ts">
const useCases = [
    {
        emoji: '🏠',
        title: 'Rumah',
        desc: 'Streaming, TikTok, Instagram, YouTube dan video call.',
    },
    {
        emoji: '👨‍💻',
        title: 'Work From Home',
        desc: 'Meeting online, remote server dan cloud storage.',
    },
    {
        emoji: '🎮',
        title: 'Gamer',
        desc: 'Koneksi stabil untuk game online dan multiplayer.',
    },
    {
        emoji: '🏪',
        title: 'Bisnis',
        desc: 'Kasir, CCTV, marketplace, cloud dan operasional.',
    },
    {
        emoji: '🎓',
        title: 'Pelajar',
        desc: 'Zoom, Google Meet, riset dan pembelajaran online.',
    },
];
</script>

<template>
    <section class="bg-white py-16 sm:py-24 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Untuk Semua
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Satu Koneksi untuk Berbagai Kebutuhan
                </h2>
            </div>

            <div
                class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5"
            >
                <div
                    v-for="item in useCases"
                    :key="item.title"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 text-center transition-all hover:-translate-y-1 hover:border-blue-300 hover:bg-white hover:shadow-lg dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-blue-700 dark:hover:bg-slate-800"
                >
                    <span class="block text-4xl">{{ item.emoji }}</span>
                    <h3
                        class="mt-4 text-base font-semibold text-slate-900 dark:text-white"
                    >
                        {{ item.title }}
                    </h3>
                    <p
                        class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                    >
                        {{ item.desc }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
