<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '../components/landing/Navbar.vue';
import Hero from '../components/landing/Hero.vue';
import Pricing from '../components/landing/Pricing.vue';
import Features from '../components/landing/Features.vue';
import UseCases from '../components/landing/UseCases.vue';
import Coverage from '../components/landing/Coverage.vue';
import Process from '../components/landing/Process.vue';
import Promo from '../components/landing/Promo.vue';
import Portal from '../components/landing/Portal.vue';
import Payments from '../components/landing/Payments.vue';
import Network from '../components/landing/Network.vue';
import Faq from '../components/landing/Faq.vue';
import Testimonials from '../components/landing/Testimonials.vue';
import Cta from '../components/landing/Cta.vue';
import Footer from '../components/landing/Footer.vue';
import { waLink } from '../components/landing/wa';

const floatingChatHref = waLink(
    'Halo DFiber, saya ingin menanyakan layanan internet',
);
</script>

<template>
    <Head title="DFiber - Internet Cepat, Stabil, Tanpa Ribet" />

    <div
        class="min-h-screen bg-white text-slate-900 selection:bg-blue-600 selection:text-white dark:bg-slate-950 dark:text-slate-100"
    >
        <Navbar />
        <main>
            <p class="text-center text-lg font-semibold">hallo</p>
            <Hero v-reveal />
            <Pricing v-reveal />
            <Features v-reveal />
            <UseCases v-reveal />
            <Coverage v-reveal />
            <Process v-reveal />
            <Promo v-reveal />
            <Portal v-reveal />
            <Payments v-reveal />
            <Network v-reveal />
            <Faq v-reveal />
            <Testimonials v-reveal />
            <Cta v-reveal />
        </main>
        <Footer v-reveal />

        <!-- Floating WhatsApp -->
        <a
            :href="floatingChatHref"
            target="_blank"
            rel="noopener"
            aria-label="Chat WhatsApp DFiber"
            class="fixed right-5 bottom-5 z-40 flex h-14 w-14 items-center justify-center rounded-full bg-emerald-500 text-white shadow-xl shadow-emerald-600/30 transition-transform hover:scale-110"
        >
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="currentColor">
                <path
                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.149-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.52.149-.174.198-.298.297-.497.1-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884a9.82 9.82 0 0 1 6.988 2.896 9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.885-9.885 9.885M20.52 3.449C18.24 1.245 15.24 0 12.045 0 5.463 0 .104 5.334.101 11.892c0 2.096.549 4.142 1.595 5.945L0 24l6.305-1.654a11.882 11.882 0 0 0 5.74 1.46h.005c6.585 0 11.946-5.336 11.949-11.896 0-3.176-1.24-6.165-3.495-8.411"
                />
            </svg>
        </a>
    </div>
</template>
