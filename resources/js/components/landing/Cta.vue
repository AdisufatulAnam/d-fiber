<script setup lang="ts">
import { waLink } from './wa';

const subscribeHref = waLink('Halo DFiber, saya ingin berlangganan');
</script>

<template>
    <section id="cta" class="bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div
                class="relative overflow-hidden rounded-[30px] bg-gradient-to-br from-blue-700 to-cyan-600 px-6 py-14 text-center text-white shadow-2xl shadow-blue-600/25 sm:px-10 sm:py-16"
            >
                <!-- Lingkaran dekoratif (seperti referensi) -->
                <div
                    class="pointer-events-none absolute inset-0"
                    aria-hidden="true"
                >
                    <div
                        class="absolute -top-40 -left-40 h-[350px] w-[350px] rounded-full bg-white/10"
                    ></div>
                    <div
                        class="absolute -right-32 -bottom-40 h-[300px] w-[300px] rounded-full bg-white/10"
                    ></div>
                </div>

                <div class="relative">
                    <h2
                        class="text-3xl font-bold tracking-tight sm:text-4xl lg:text-5xl"
                    >
                        Siap Beralih ke Internet yang Lebih Nyaman?
                    </h2>
                    <p
                        class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-white/85 sm:text-lg"
                    >
                        Dapatkan koneksi cepat dan stabil bersama DFiber.
                    </p>

                    <div
                        class="mt-9 flex flex-col items-center justify-center gap-3 sm:flex-row"
                    >
                        <a
                            href="#coverage"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-white px-7 py-3.5 text-sm font-bold text-blue-700 shadow-lg transition-transform hover:-translate-y-0.5 sm:w-auto"
                        >
                            Cek Coverage
                        </a>
                        <a
                            :href="subscribeHref"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-[#25d366] px-7 py-3.5 text-sm font-bold text-white shadow-lg transition-transform hover:-translate-y-0.5 sm:w-auto"
                        >
                            Berlangganan Sekarang
                        </a>
                    </div>

                    <p class="mt-6 text-xs text-white/75">
                        📱 WhatsApp: 0831-8468-3492 &bull; Balas chat
                        07.00–21.00 WIB
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>
