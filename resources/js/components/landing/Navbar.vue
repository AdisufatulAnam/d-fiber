<script setup lang="ts">
import { ref } from 'vue';
import { waLink } from './wa';

const mobileOpen = ref(false);

const links = [
    { label: 'Beranda', href: '#beranda' },
    { label: 'Paket Internet', href: '#paket' },
    { label: 'Keunggulan', href: '#keunggulan' },
    { label: 'Coverage', href: '#coverage' },
    { label: 'FAQ', href: '#faq' },
    { label: 'Kontak', href: '#kontak' },
];

const subscribeHref = waLink('Halo DFiber, saya ingin berlangganan.');
</script>

<template>
    <header
        class="sticky top-0 z-50 border-b border-slate-200/70 bg-white/85 backdrop-blur-md dark:border-slate-800 dark:bg-slate-950/85"
    >
        <nav
            class="mx-auto flex h-16 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8"
        >
            <!-- Brand -->
            <a href="#beranda" class="flex shrink-0 items-center gap-2.5">
                <span
                    class="flex h-9 w-9 items-center justify-center rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 shadow-lg shadow-blue-600/25"
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
                <span class="leading-tight">
                    <span
                        class="block text-lg font-bold tracking-tight text-slate-900 dark:text-white"
                    >
                        DFiber
                    </span>
                    <span
                        class="block text-[11px] font-medium tracking-wide text-slate-500 dark:text-slate-400"
                    >
                        Internet Cepat. Stabil.
                    </span>
                </span>
            </a>

            <!-- Desktop links -->
            <div class="hidden items-center gap-1 xl:flex">
                <a
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    class="rounded-lg px-3 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-100 hover:text-slate-900 dark:text-slate-300 dark:hover:bg-slate-800 dark:hover:text-white"
                >
                    {{ link.label }}
                </a>
            </div>

            <!-- Desktop CTA -->
            <div class="hidden items-center gap-3 xl:flex">
                <a
                    :href="subscribeHref"
                    target="_blank"
                    rel="noopener"
                    class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/25 transition-all hover:-translate-y-0.5"
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
                    Berlangganan Sekarang
                </a>
            </div>

            <!-- Mobile / tablet toggle -->
            <button
                type="button"
                class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-700 xl:hidden dark:border-slate-700 dark:text-slate-200"
                :aria-expanded="mobileOpen"
                aria-label="Buka menu navigasi"
                @click="mobileOpen = !mobileOpen"
            >
                <svg
                    v-if="!mobileOpen"
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                >
                    <path d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg
                    v-else
                    class="h-5 w-5"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                >
                    <path d="M6 6l12 12M18 6L6 18" />
                </svg>
            </button>
        </nav>

        <!-- Mobile menu -->
        <Transition name="menu">
            <div
                v-if="mobileOpen"
                class="border-t border-slate-200 bg-white px-4 pt-2 pb-4 xl:hidden dark:border-slate-800 dark:bg-slate-950"
            >
                <a
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-200 dark:hover:bg-slate-800"
                    @click="mobileOpen = false"
                >
                    {{ link.label }}
                </a>
                <a
                    :href="subscribeHref"
                    target="_blank"
                    rel="noopener"
                    class="mt-2 flex items-center justify-center gap-2 rounded-lg bg-gradient-to-br from-blue-600 to-cyan-500 px-4 py-2.5 text-sm font-bold text-white shadow-md shadow-blue-600/25"
                    @click="mobileOpen = false"
                >
                    Berlangganan Sekarang
                </a>
            </div>
        </Transition>
    </header>
</template>
