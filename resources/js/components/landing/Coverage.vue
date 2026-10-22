<script setup lang="ts">
import { reactive, ref } from 'vue';
import { waLink } from './wa';

const form = reactive({ rtRw: '', dusun: '', desa: '' });
const locating = ref(false);

const COVERAGE_AREA = 'Piyungan, Bantul, D.I. Yogyakarta';

const fields = [
    {
        key: 'rtRw',
        label: 'RT / RW',
        placeholder: 'Contoh: RT 03 / RW 07',
    },
    {
        key: 'dusun',
        label: 'Dusun',
        placeholder: '📍 Nama Dusun',
    },
    {
        key: 'desa',
        label: 'Desa',
        placeholder: '📍 Nama Desa',
    },
] as const;

function buildMessage(
    location: string,
    coords?: { lat: number; lng: number },
): string {
    const base = location
        ? `Halo DFiber, saya ingin cek coverage di lokasi: ${location} (${COVERAGE_AREA}).`
        : `Halo DFiber, saya ingin menanyakan coverage area di ${COVERAGE_AREA}.`;

    if (!coords) {
        return base;
    }

    const mapsUrl = `https://www.google.com/maps?q=${coords.lat},${coords.lng}`;

    return [
        base,
        '',
        '📍 Share lokasi saya:',
        `Koordinat: ${coords.lat}, ${coords.lng}`,
        mapsUrl,
    ].join('\n');
}

function openWhatsApp(message: string) {
    window.open(waLink(message), '_blank', 'noopener');
}

function checkCoverage() {
    const location = Object.values(form)
        .map((value) => value.trim())
        .filter(Boolean)
        .join(', ');

    if (!('geolocation' in navigator)) {
        openWhatsApp(buildMessage(location));
        return;
    }

    locating.value = true;
    navigator.geolocation.getCurrentPosition(
        (pos) => {
            locating.value = false;
            openWhatsApp(
                buildMessage(location, {
                    lat: Number(pos.coords.latitude.toFixed(6)),
                    lng: Number(pos.coords.longitude.toFixed(6)),
                }),
            );
        },
        () => {
            // Izin ditolak / gagal → tetap kirim tanpa lokasi
            locating.value = false;
            openWhatsApp(buildMessage(location));
        },
        { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 },
    );
}
</script>

<template>
    <section id="coverage" class="scroll-mt-20 bg-white py-16 sm:py-24">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div
                class="rounded-3xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-200/60 sm:p-10 dark:border-slate-800 dark:bg-slate-900 dark:shadow-none"
            >
                <div class="mx-auto max-w-2xl text-center">
                    <p
                        class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                    >
                        Coverage Area
                    </p>
                    <h2
                        class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl dark:text-white"
                    >
                        Apakah DFiber Sudah Tersedia di Area Anda?
                    </h2>
                    <p
                        class="mt-4 text-base text-slate-600 dark:text-slate-400"
                    >
                        Kami terus mengembangkan jaringan untuk menjangkau lebih
                        banyak pelanggan.
                    </p>

                    <!-- Cakupan wilayah -->
                    <div
                        class="mt-6 inline-flex flex-col items-center gap-1 rounded-2xl border border-blue-100 bg-blue-50 px-5 py-3.5 sm:flex-row sm:gap-2"
                    >
                        <span class="text-sm font-bold text-blue-600">
                            📍 Cakupan wilayah:
                        </span>
                        <span class="text-sm font-medium text-slate-700">
                            {{ COVERAGE_AREA }}
                        </span>
                    </div>
                </div>

                <form
                    class="mt-9 grid grid-cols-1 gap-4 sm:grid-cols-3"
                    @submit.prevent="checkCoverage"
                >
                    <div v-for="field in fields" :key="field.key">
                        <label
                            :for="field.key"
                            class="mb-1.5 block text-sm font-medium text-slate-700 dark:text-slate-300"
                        >
                            {{ field.label }}
                        </label>
                        <input
                            :id="field.key"
                            v-model="form[field.key]"
                            type="text"
                            :placeholder="field.placeholder"
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm text-slate-900 placeholder-slate-400 transition-colors outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-slate-600 dark:bg-slate-800 dark:text-white"
                        />
                    </div>

                    <div class="sm:col-span-3">
                        <button
                            type="submit"
                            :disabled="locating"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-gradient-to-br from-blue-600 to-cyan-500 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/25 transition-all hover:-translate-y-0.5 disabled:cursor-wait disabled:opacity-70 disabled:hover:translate-y-0 sm:w-auto"
                        >
                            {{
                                locating
                                    ? '📍 Mengambil lokasi…'
                                    : 'Cek Coverage'
                            }}
                            <svg
                                v-if="!locating"
                                class="h-4 w-4"
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M5 12h14M13 6l6 6-6 6" />
                            </svg>
                        </button>
                        <p
                            class="mt-4 text-center text-xs text-slate-500 sm:text-left dark:text-slate-400"
                        >
                            📍 Lokasi GPS Anda otomatis disertakan (share
                            lokasi) saat diteruskan ke WhatsApp. Ketersediaan
                            jaringan dapat berbeda berdasarkan lokasi
                            pemasangan.
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>
</template>
