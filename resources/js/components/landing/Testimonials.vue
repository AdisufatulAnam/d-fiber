<script setup lang="ts">
type Testimonial = {
    name: string;
    role: string;
    initial: string;
    color: string;
    text: string;
};

const topRow: Testimonial[] = [
    {
        name: 'Andi Pratama',
        role: 'Pelanggan • Kelurahan Sukamaju',
        initial: 'A',
        color: 'from-blue-600 to-blue-400',
        text: 'Sejak pasang DFiber, internet rumah jadi stabil seharian. Nonton YouTube 4K nggak buffering lagi.',
    },
    {
        name: 'Rina Wulandari',
        role: 'Pelanggan • Kec. Cibinong',
        initial: 'R',
        color: 'from-cyan-600 to-cyan-400',
        text: 'Meeting online tiap hari jadi lancar, latensi rendah dan ping stabil. Kerjaan dari rumah jadi tenang.',
    },
    {
        name: 'Budi Santoso',
        role: 'Pelanggan • RT 03 / RW 07',
        initial: 'B',
        color: 'from-indigo-600 to-indigo-400',
        text: 'Harganya terjangkau, pemasangan cepat, dan teknisinya ramah. Baru daftar 2 hari sudah jalan.',
    },
    {
        name: 'Siti Nurhaliza',
        role: 'Pelanggan • Mahasiswi',
        initial: 'S',
        color: 'from-emerald-600 to-emerald-400',
        text: 'Kuliah online dan buka banyak tab bareng-bareng tetap lancar, nggak ada putus-putus.',
    },
    {
        name: 'Dedi Kurniawan',
        role: 'Pelanggan • Gamers',
        initial: 'D',
        color: 'from-violet-600 to-violet-400',
        text: 'Ping game online cuma 12 ms, jadi menang terus hehe. Support WhatsApp juga cepat balas.',
    },
    {
        name: 'Maya Lestari',
        role: 'Pelanggan • Ibu Rumah Tangga',
        initial: 'M',
        color: 'from-rose-600 to-rose-400',
        text: 'Anak-anak bisa sekolah online dan saya bisa streaming sekaligus, koneksi tetap kencang.',
    },
    {
        name: 'Agus Setiawan',
        role: 'Pelanggan • Pemilik Warung',
        initial: 'A',
        color: 'from-amber-500 to-amber-400',
        text: 'Warung saya pakai WiFi DFiber untuk pembayaran digital, selalu online dan lancar.',
    },
    {
        name: 'Fajar Nugroho',
        role: 'Pelanggan • Work From Home',
        initial: 'F',
        color: 'from-sky-600 to-sky-400',
        text: 'Upload file besar ke cloud cepat sekali. Sebelumnya pakai provider lain sering gagal.',
    },
];

const bottomRow: Testimonial[] = [
    {
        name: 'Dewi Anggraini',
        role: 'Pelanggan • Kec. Gunung Putri',
        initial: 'D',
        color: 'from-pink-600 to-pink-400',
        text: 'Admin responsif, gangguan langsung ditangani di hari yang sama. Puas banget sama servisnya.',
    },
    {
        name: 'Rizky Ramadhan',
        role: 'Pelanggan • Streamer',
        initial: 'R',
        color: 'from-blue-700 to-cyan-500',
        text: 'Live streaming di Twitch tanpa drop sama sekali. Kencang dari pagi sampai malam.',
    },
    {
        name: 'Putri Handayani',
        role: 'Pelanggan • Guru Les',
        initial: 'P',
        color: 'from-teal-600 to-teal-400',
        text: 'Les online dengan banyak murid sekaligus tetap jernih, video nggak pecah.',
    },
    {
        name: 'Hendra Wijaya',
        role: 'Pelanggan • UMKM Kopi',
        initial: 'H',
        color: 'from-orange-600 to-orange-400',
        text: 'Pelanggan wifi gratis di kedai saya betah berlama-lama, omzet naik karena sering kedatangan.',
    },
    {
        name: 'Lina Kartika',
        role: 'Pelanggan • RT 11 / RW 02',
        initial: 'L',
        color: 'from-fuchsia-600 to-fuchsia-400',
        text: 'Bayar tagihan bisa lewat portal, praktis. Tak perlu antre lagi tiap bulan.',
    },
    {
        name: 'Yusuf Maulana',
        role: 'Pelanggan • Pelajar',
        initial: 'Y',
        color: 'from-lime-600 to-lime-400',
        text: 'Belajar dari rumah dan akses e-learning jadi cepat, tugas nggak telat lagi.',
    },
    {
        name: 'Nia Permata',
        role: 'Pelanggan • Desa Sukarasa',
        initial: 'N',
        color: 'from-purple-600 to-purple-400',
        text: 'Di desa kami akhirnya ada internet cepat. DFiber beneran ngebantu banget.',
    },
    {
        name: 'Tony Saputra',
        role: 'Pelanggan • CCTV Online',
        initial: 'T',
        color: 'from-slate-700 to-slate-500',
        text: 'CCTV toko bisa dipantau dari HP tanpa buffering, jadi lebih aman dan tenang.',
    },
];

const rows = [
    { items: topRow, toRight: true, key: 'top' },
    { items: bottomRow, toRight: false, key: 'bottom' },
];
</script>

<template>
    <section class="overflow-hidden bg-slate-50 py-16 sm:py-24">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl text-center">
                <span
                    class="inline-flex rounded-full bg-blue-50 px-4 py-1.5 text-xs font-bold tracking-wider text-blue-600 uppercase"
                >
                    Testimonial
                </span>
                <h2
                    class="mt-3 text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl"
                >
                    Apa Kata Pelanggan?
                </h2>
                <p class="mt-4 text-base text-slate-600">
                    Ratusan pelanggan RT/RW Net sudah membuktikan koneksi
                    DFiber.
                </p>
            </div>
        </div>

        <!-- Dua baris testimoni berjalan: atas ke kanan, bawah ke kiri -->
        <div class="mt-12 space-y-5">
            <div v-for="row in rows" :key="row.key" class="marquee relative">
                <!-- Gradasi tepi kiri/kanan -->
                <div
                    class="pointer-events-none absolute inset-y-0 left-0 z-10 w-12 bg-gradient-to-r from-slate-50 to-transparent sm:w-24"
                    aria-hidden="true"
                ></div>
                <div
                    class="pointer-events-none absolute inset-y-0 right-0 z-10 w-12 bg-gradient-to-l from-slate-50 to-transparent sm:w-24"
                    aria-hidden="true"
                ></div>

                <div
                    class="marquee-track"
                    :class="row.toRight ? 'to-right' : ''"
                >
                    <div
                        v-for="copy in 2"
                        :key="copy"
                        class="flex shrink-0 gap-5 pr-5"
                        :aria-hidden="copy === 2 ? 'true' : undefined"
                    >
                        <figure
                            v-for="item in row.items"
                            :key="row.key + item.name"
                            class="flex w-[280px] shrink-0 flex-col rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:w-[330px] sm:p-6"
                        >
                            <div
                                class="flex gap-0.5 text-amber-400"
                                aria-label="5 dari 5 bintang"
                            >
                                <svg
                                    v-for="n in 5"
                                    :key="n"
                                    class="h-4 w-4"
                                    viewBox="0 0 24 24"
                                    fill="currentColor"
                                >
                                    <path
                                        d="M12 2l2.9 6.26 6.6.72-4.9 4.5 1.35 6.52L12 16.9l-5.95 3.1 1.35-6.52-4.9-4.5 6.6-.72L12 2z"
                                    />
                                </svg>
                            </div>

                            <blockquote
                                class="mt-4 flex-1 text-sm leading-relaxed text-slate-700"
                            >
                                “{{ item.text }}”
                            </blockquote>

                            <figcaption
                                class="mt-5 flex items-center gap-3 border-t border-slate-100 pt-4"
                            >
                                <span
                                    class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-gradient-to-br text-sm font-bold text-white"
                                    :class="item.color"
                                >
                                    {{ item.initial }}
                                </span>
                                <span class="min-w-0">
                                    <span
                                        class="block truncate text-sm font-semibold text-slate-900"
                                    >
                                        {{ item.name }}
                                    </span>
                                    <span
                                        class="block truncate text-xs text-slate-500"
                                    >
                                        {{ item.role }}
                                    </span>
                                </span>
                            </figcaption>
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
