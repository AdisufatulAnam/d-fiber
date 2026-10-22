<script setup lang="ts">
import { waLink } from './wa';

const year = new Date().getFullYear();

const navLinks = [
    { label: 'Beranda', href: '#beranda' },
    { label: 'Paket Internet', href: '#paket' },
    { label: 'Coverage', href: '#coverage' },
    { label: 'FAQ', href: '#faq' },
    { label: 'Kontak', href: '#kontak' },
];

const serviceLinks = [
    { label: 'Internet Rumah', href: '#paket' },
    { label: 'Internet Bisnis', href: '#paket' },
    { label: 'Upgrade Paket', href: '#paket' },
    {
        label: 'Customer Support',
        href: waLink('Halo DFiber, saya ingin menanyakan layanan internet'),
    },
];

const supportHref = waLink(
    'Halo DFiber, saya ingin menanyakan layanan internet',
);
</script>

<template>
    <footer
        id="kontak"
        class="scroll-mt-20 border-t border-slate-800 bg-slate-900 text-slate-300"
    >
        <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 gap-10 md:grid-cols-4">
                <!-- Brand -->
                <div class="col-span-2 md:col-span-1">
                    <div class="flex items-center gap-2.5">
                        <span
                            class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500"
                        >
                            <svg
                                class="h-5 w-5 text-white"
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
                        </span>
                        <span class="text-lg font-bold text-white">DFiber</span>
                    </div>
                    <p class="mt-4 text-sm leading-relaxed text-slate-400">
                        Internet Cepat. Stabil. Terjangkau. Layanan internet
                        untuk rumah, keluarga, pelajar, gamer dan bisnis.
                    </p>
                    <div class="mt-5 flex gap-3">
                        <a
                            :href="supportHref"
                            target="_blank"
                            rel="noopener"
                            aria-label="WhatsApp"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-400 transition-colors hover:border-blue-500 hover:text-blue-400"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="currentColor"
                            >
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 0 1 6.988 2.896 9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.885-9.885 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.892c0 2.096.549 4.142 1.595 5.945L0 24l6.305-1.654a11.882 11.882 0 0 0 5.74 1.46h.005c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411"
                                />
                            </svg>
                        </a>
                        <a
                            href="mailto:halo@dfiber.id"
                            aria-label="Email"
                            class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 text-slate-400 transition-colors hover:border-blue-500 hover:text-blue-400"
                        >
                            <svg
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="2.5"
                                />
                                <path d="m3.5 7 8.5 6 8.5-6" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Navigasi -->
                <div>
                    <h3
                        class="text-sm font-semibold tracking-wider text-white uppercase"
                    >
                        Navigasi
                    </h3>
                    <ul class="mt-4 space-y-3">
                        <li v-for="link in navLinks" :key="link.label">
                            <a
                                :href="link.href"
                                class="text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                {{ link.label }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Layanan -->
                <div>
                    <h3
                        class="text-sm font-semibold tracking-wider text-white uppercase"
                    >
                        Layanan
                    </h3>
                    <ul class="mt-4 space-y-3">
                        <li v-for="link in serviceLinks" :key="link.label">
                            <a
                                :href="link.href"
                                :target="
                                    link.href.startsWith('http')
                                        ? '_blank'
                                        : undefined
                                "
                                rel="noopener"
                                class="text-sm text-slate-400 transition-colors hover:text-blue-400"
                            >
                                {{ link.label }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div class="col-span-2 md:col-span-1">
                    <h3
                        class="text-sm font-semibold tracking-wider text-white uppercase"
                    >
                        Kontak
                    </h3>
                    <ul class="mt-4 space-y-3 text-sm text-slate-400">
                        <li class="flex items-start gap-2.5">
                            <svg
                                class="mt-0.5 h-4 w-4 shrink-0 text-blue-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.4 1.8.7 2.7a2 2 0 0 1-.5 2.1L8.1 9.7a16 16 0 0 0 6 6l1.2-1.2a2 2 0 0 1 2.1-.5c.9.3 1.8.6 2.7.7a2 2 0 0 1 1.7 2z"
                                />
                            </svg>
                            <a
                                :href="supportHref"
                                target="_blank"
                                rel="noopener"
                                class="transition-colors hover:text-blue-400"
                            >
                                0831-8468-3492
                            </a>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg
                                class="mt-0.5 h-4 w-4 shrink-0 text-blue-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <rect
                                    x="3"
                                    y="5"
                                    width="18"
                                    height="14"
                                    rx="2.5"
                                />
                                <path d="m3.5 7 8.5 6 8.5-6" />
                            </svg>
                            <a
                                href="mailto:halo@dfiber.id"
                                class="transition-colors hover:text-blue-400"
                            >
                                halo@dfiber.id
                            </a>
                        </li>
                        <li class="flex items-start gap-2.5">
                            <svg
                                class="mt-0.5 h-4 w-4 shrink-0 text-blue-400"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path
                                    d="M21 10c0 6-9 12-9 12s-9-6-9-12a9 9 0 0 1 18 0z"
                                />
                                <circle cx="12" cy="10" r="3" />
                            </svg>
                            Alamat Operasional
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-800">
            <div
                class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-3 px-4 py-6 text-xs text-slate-500 sm:flex-row sm:px-6 lg:px-8"
            >
                <p>&copy; {{ year }} DFiber. All Rights Reserved.</p>
                <div class="flex gap-5">
                    <a href="#" class="transition-colors hover:text-blue-400"
                        >Terms &amp; Conditions</a
                    >
                    <a href="#" class="transition-colors hover:text-blue-400"
                        >Privacy Policy</a
                    >
                </div>
            </div>
        </div>
    </footer>
</template>
