<script setup lang="ts">
import { waLink } from './wa';

const steps = [
    {
        no: '01',
        title: 'Pilih Paket',
        desc: 'Pilih paket internet yang sesuai dengan kebutuhan Anda.',
    },
    {
        no: '02',
        title: 'Cek Coverage',
        desc: 'Tim kami memastikan jaringan DFiber tersedia di lokasi Anda.',
    },
    {
        no: '03',
        title: 'Jadwalkan Pemasangan',
        desc: 'Tentukan waktu pemasangan yang nyaman untuk Anda.',
    },
    {
        no: '04',
        title: 'Internet Aktif',
        desc: 'Teknisi melakukan instalasi dan koneksi siap digunakan.',
    },
];

const registerHref = waLink('Halo DFiber, saya ingin mendaftar.');
</script>

<template>
    <section class="bg-white py-16 sm:py-24 dark:bg-slate-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <p
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Cara Berlangganan
                </p>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                >
                    Berlangganan DFiber Itu Mudah
                </h2>
            </div>

            <ol
                class="mt-12 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4"
            >
                <li
                    v-for="step in steps"
                    :key="step.no"
                    class="rounded-2xl border border-slate-200 bg-slate-50 p-6 transition-all hover:-translate-y-1 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-100/50 dark:border-slate-700 dark:bg-slate-800/50 dark:hover:border-blue-700 dark:hover:shadow-none"
                >
                    <span
                        class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 text-sm font-bold text-white shadow-lg shadow-blue-600/25"
                    >
                        {{ step.no }}
                    </span>
                    <h3
                        class="mt-5 text-base font-semibold text-slate-900 dark:text-white"
                    >
                        {{ step.title }}
                    </h3>
                    <p
                        class="mt-2 text-sm leading-relaxed text-slate-600 dark:text-slate-400"
                    >
                        {{ step.desc }}
                    </p>
                </li>
            </ol>

            <div class="mt-10 text-center">
                <a
                    :href="registerHref"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 sm:w-auto"
                >
                    Daftar Sekarang
                </a>
            </div>
        </div>
    </section>
</template>
