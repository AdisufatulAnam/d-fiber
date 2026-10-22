<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, onBeforeUnmount } from 'vue';
import AdminLayout from '../../../components/admin/AdminLayout.vue';
import PageHeaderCard from '../../../components/admin/PageHeaderCard.vue';
import ListCard from '../../../components/admin/ListCard.vue';
import Pagination from '../../../components/admin/Pagination.vue';
import DetailDrawer from '../../../components/admin/DetailDrawer.vue';
import DetailField from '../../../components/admin/DetailField.vue';

type Pkg = { id: number; name: string; price: string; speed: string | null };
type Customer = {
    id: number;
    name: string;
    phone: string | null;
    address: string | null;
    qr_code: string;
    package: Pkg | null;
};

type Bill = {
    id: number;
    invoice_number?: string | null;
    customer_name: string;
    billing_month: string | null;
    amount: string;
    status: 'pending' | 'confirmed' | 'cancelled';
    payment_method: string;
    notes: string | null;
    created_at: string;
    updated_at?: string | null;
    deleted_at?: string | null;
    customer?: Customer | null;
    whatsapp_logs?: WaLog[];
};

type WaLog = {
    id: number;
    phone: string;
    status: 'pending' | 'sent' | 'failed';
    attempts: number;
    error: string | null;
    sent_at: string | null;
    created_at: string;
};

const props = defineProps<{
    admin: { name: string; email: string; role: string };
    payments: {
        data: Bill[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    customers: Customer[];
    filters: { search?: string; status?: string; month?: string; trashed?: string };
}>();

// ——— Filter ———
const search = ref(props.filters.search || '');
const filterStatus = ref(props.filters.status || '');
const filterMonth = ref(props.filters.month || '');
const filterTrashed = ref(props.filters.trashed || '');
const showFilter = ref(false);
const perPage = ref(props.payments.per_page || 15);
const monthInput = ref<HTMLInputElement | null>(null);

const openMonthPicker = () => {
    monthInput.value?.showPicker?.();
};

const handleFilter = () => {
    router.get('/admin/payments', { search: search.value, status: filterStatus.value, month: filterMonth.value, trashed: filterTrashed.value, per_page: perPage.value }, { preserveState: true, replace: true });
};

const handleRefresh = () => {
    router.reload({ only: ['payments'] });
};

const handlePerPage = (value: number) => {
    perPage.value = value;
    router.get('/admin/payments', { search: search.value, status: filterStatus.value, month: filterMonth.value, trashed: filterTrashed.value, per_page: perPage.value }, { preserveState: true, replace: true });
};

// ——— Detail drawer ———
const selectedBill = ref<Bill | null>(null);
const showDetail = ref(false);
const cancellingId = ref<number | null>(null);
const restoringId = ref<number | null>(null);
const resendingId = ref<number | null>(null);
const confirmCancelBill = ref<Bill | null>(null);
const confirmResendBill = ref<Bill | null>(null);

const openDetail = (bill: Bill) => {
    selectedBill.value = bill;
    showDetail.value = true;
};

const closeDetail = () => {
    showDetail.value = false;
    setTimeout(() => { selectedBill.value = null; }, 200);
};

const askCancel = (bill: Bill) => {
    confirmCancelBill.value = bill;
};

const confirmCancel = async () => {
    const bill = confirmCancelBill.value;
    if (!bill) return;
    cancellingId.value = bill.id;
    try {
        await router.delete(`/admin/payments/${bill.id}`, { preserveScroll: true });
        confirmCancelBill.value = null;
        closeDetail();
        handleRefresh();
    } finally {
        cancellingId.value = null;
    }
};

const restorePayment = async (bill: Bill) => {
    restoringId.value = bill.id;
    try {
        await router.post(`/admin/payments/${bill.id}/restore`, {}, { preserveScroll: true });
        closeDetail();
        handleRefresh();
    } finally {
        restoringId.value = null;
    }
};

const resendWa = async (bill: Bill) => {
    resendingId.value = bill.id;
    try {
        await router.post(`/admin/payments/${bill.id}/resend-wa`, {}, { preserveScroll: true });
        confirmResendBill.value = null;
        handleRefresh();
    } finally {
        resendingId.value = null;
    }
};

const askResend = (bill: Bill) => {
    confirmResendBill.value = bill;
};

// Riwayat WA terbaru (untuk tombol kirim ulang).
const latestWaLog = (bill: Bill): WaLog | undefined => bill.whatsapp_logs?.[0];
const waNeedsResend = (bill: Bill): boolean => {
    const log = latestWaLog(bill);
    return !!log && (log.status === 'failed' || log.attempts >= 3);
};

const formatDateTime = (val: string | null | undefined) => {
    if (!val) return '-';
    return new Date(val).toLocaleString('id-ID', {
        day: '2-digit', month: 'short', year: 'numeric',
        hour: '2-digit', minute: '2-digit',
    });
};

// ——— Cetak bukti pembayaran (nota thermal 58mm) ———
const printReceipt = (bill: Bill) => {
    const name = bill.customer?.name || bill.customer_name || '-';
    const phone = bill.customer?.phone || '-';
    const pkg = bill.customer?.package?.name || '-';
    const addr = bill.customer?.address || '-';
    const month = formatMonth(bill.billing_month);
    const amount = formatRupiah(bill.amount);
    const method = bill.payment_method === 'qrcode' ? 'QR Code' : 'Manual';
    const now = new Date().toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    const inv = bill.invoice_number || `#${bill.id}`;

    const html = `<!doctype html><html><head><meta charset="utf-8"><title>Bukti Bayar #${bill.id}</title>
<style>
  @page { size: 58mm auto; margin: 0; }
  * { margin:0; padding:0; box-sizing:border-box; }
  body { width:56mm; font-family:'Courier New',Courier,monospace; font-size:10px; line-height:1.35; color:#000; padding:1mm; }
  .c { text-align:center; }
  .b { font-weight:bold; }
  .hr { border-top:1px dashed #000; margin:3px 0; }
  .row { display:flex; justify-content:space-between; gap:4px; }
  .row span:last-child { text-align:right; }
  .big { font-size:12px; font-weight:bold; }
</style></head><body>
  <div class="c b" style="font-size:13px">DFIBER</div>
  <div class="c">Internet Service</div>
  <div class="hr"></div>
  <div class="row"><span>Invoice</span><span class="b">${inv}</span></div>
  <div class="row"><span>Tanggal</span><span>${now}</span></div>
  <div class="hr"></div>
  <div class="b">${name}</div>
  <div>${phone}</div>
  <div style="word-break:break-all">${addr}</div>
  <div class="hr"></div>
  <div class="row"><span>Paket</span><span>${pkg}</span></div>
  <div class="row"><span>Bulan</span><span>${month}</span></div>
  <div class="row"><span>Metode</span><span>${method}</span></div>
  <div class="row"><span>Status</span><span>LUNAS</span></div>
  <div class="hr"></div>
  <div class="row big"><span>TOTAL</span><span>${amount}</span></div>
  <div class="hr"></div>
  <div class="c" style="margin-top:4px">Terima kasih</div>
  <div class="c">atas pembayaran Anda</div>
  <div class="c b" style="margin-top:6px">*** LUNAS ***</div>
</body></html>`;

    const w = window.open('', '_blank', 'width=300,height=600');
    if (!w) return;
    w.document.write(html);
    w.document.close();
    w.focus();
    setTimeout(() => { w.print(); w.close(); }, 250);
};

// ——— Panel bayar ———
const showModal = ref(false);
const addMode = ref<'scan' | 'search'>('scan');

const qrInput = ref('');
const scanning = ref(false);
const scanError = ref('');
const scanResult = ref<{
    customer: Customer;
    unpaidBills: Bill[];
    paidBills: Bill[];
} | null>(null);
const payingBillId = ref<number | null>(null);
const payingBulk = ref(false);
const selectedBillIds = ref<number[]>([]);

// Camera
const cameraActive = ref(false);
const cameraError = ref('');
let videoEl: HTMLVideoElement | null = null;
let stream: MediaStream | null = null;
let detector: any = null;
let scanLoop: number | null = null;

// ——— Cari pelanggan ———
const customerQuery = ref('');
const searchLoading = ref(false);

const filteredCustomers = computed(() => {
    if (!customerQuery.value.trim()) return [];
    const q = customerQuery.value.toLowerCase();
    return props.customers.filter((c) =>
        c.name.toLowerCase().includes(q) ||
        (c.phone && c.phone.includes(q)) ||
        c.qr_code.toLowerCase().includes(q)
    ).slice(0, 8);
});

// ——— Helpers ———
const formatRupiah = (val: string | number) => {
    const num = typeof val === 'string' ? parseFloat(val) : val;
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
};

const formatMonth = (val: string | null) => {
    if (!val) return '-';
    const [y, m] = val.split('-');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${months[parseInt(m) - 1]} ${y}`;
};

const unpaidTotal = computed(() => {
    if (!scanResult.value) return 0;
    return scanResult.value.unpaidBills.reduce((s, b) => s + parseFloat(b.amount), 0);
});

const selectedBills = computed(() => {
    if (!scanResult.value) return [];
    return scanResult.value.unpaidBills.filter((b) => selectedBillIds.value.includes(b.id));
});

const selectedTotal = computed(() => selectedBills.value.reduce((s, b) => s + parseFloat(b.amount), 0));

const allUnpaidSelected = computed(() => {
    if (!scanResult.value || scanResult.value.unpaidBills.length === 0) return false;
    return selectedBillIds.value.length === scanResult.value.unpaidBills.length;
});

const statusLabel: Record<string, string> = { pending: 'Belum Bayar', confirmed: 'Lunas', cancelled: 'Dibatalkan' };
const statusClass: Record<string, string> = {
    pending: 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
    confirmed: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    cancelled: 'bg-red-50 text-red-700 ring-red-600/20',
};

// ——— Kolom daftar ———
const columns = [
    { label: 'Nama Pelanggan', class: 'flex-1 min-w-0' },
    { label: 'Invoice', class: 'hidden md:block shrink-0 w-36' },
    { label: 'Telepon', class: 'hidden md:block shrink-0 w-28' },
    { label: 'Paket', class: 'hidden lg:block shrink-0 w-36' },
    { label: 'Alamat', class: 'hidden xl:block shrink-0 w-40' },
    { label: 'Bulan', class: 'hidden sm:block shrink-0 w-20' },
    { label: 'Jumlah', class: 'hidden sm:block shrink-0 w-24' },
    { label: 'Metode', class: 'hidden lg:block shrink-0 w-20' },
    { label: 'Status', class: 'shrink-0 w-20' },
];

// ——— Reset / buka panel ———
const resetModal = () => {
    qrInput.value = '';
    scanError.value = '';
    scanResult.value = null;
    cameraError.value = '';
    customerQuery.value = '';
    selectedBillIds.value = [];
    payingBulk.value = false;
    stopCamera();
};

const openPayPanel = (mode: 'scan' | 'search') => {
    resetModal();
    addMode.value = mode;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    stopCamera();
};

// ——— Fetch customer by QR ———
const fetchCustomerByQr = async (qr: string) => {
    scanError.value = '';
    scanning.value = true;

    try {
        const res = await fetch('/admin/payments/find-customer', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
                Accept: 'application/json',
            },
            body: JSON.stringify({ qr_code: qr }),
        });

        if (!res.ok) {
            scanError.value = 'QR Code tidak ditemukan. Pastikan kode benar.';
            scanResult.value = null;
            return;
        }

        const data = await res.json();
        if (data.found && data.customer) {
            const bills: Bill[] = data.payments || [];
            scanResult.value = {
                customer: data.customer,
                unpaidBills: bills.filter((b) => b.status === 'pending'),
                paidBills: bills.filter((b) => b.status !== 'pending'),
            };
            selectedBillIds.value = [];
            stopCamera();
        }
    } catch {
        scanError.value = 'Terjadi kesalahan saat memindai.';
    } finally {
        scanning.value = false;
    }
};

const scanQr = () => {
    if (qrInput.value.trim()) fetchCustomerByQr(qrInput.value.trim());
};

// ——— Fetch customer bills (mode cari) ———
const selectSearchCustomer = async (c: Customer) => {
    customerQuery.value = '';
    scanError.value = '';
    searchLoading.value = true;

    try {
        const res = await fetch(`/admin/payments/customer/${c.id}/bills`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (!res.ok) throw new Error();
        const data = await res.json();
        const bills: Bill[] = data.payments || [];
        scanResult.value = {
            customer: data.customer,
            unpaidBills: bills.filter((b) => b.status === 'pending'),
            paidBills: bills.filter((b) => b.status !== 'pending'),
        };
        selectedBillIds.value = [];
    } catch {
        scanError.value = 'Gagal memuat tagihan pelanggan.';
    } finally {
        searchLoading.value = false;
    }
};

// ——— Camera scanner ———
const startCamera = async () => {
    cameraError.value = '';
    scanResult.value = null;

    if (!('BarcodeDetector' in window)) {
        cameraError.value = 'Browser tidak mendukung scanner kamera. Gunakan input manual.';
        return;
    }

    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'environment' },
        });

        const video = document.createElement('video');
        video.setAttribute('playsinline', 'true');
        video.setAttribute('autoplay', 'true');
        video.srcObject = stream;
        video.className = 'w-full h-full object-cover rounded-lg';
        await video.play();
        videoEl = video;

        const container = document.getElementById('qr-camera-box');
        if (container) {
            container.innerHTML = '';
            container.appendChild(video);
        }

        // @ts-ignore
        detector = new window.BarcodeDetector({ formats: ['qr_code'] });
        cameraActive.value = true;

        const tick = async () => {
            if (!cameraActive.value || !videoEl) return;
            try {
                const codes = await detector.detect(videoEl);
                if (codes.length > 0) {
                    const text = codes[0]?.rawValue || '';
                    if (text) {
                        fetchCustomerByQr(text);
                        return;
                    }
                }
            } catch { /* ignore */ }
            scanLoop = requestAnimationFrame(tick);
        };
        scanLoop = requestAnimationFrame(tick);
    } catch (err: any) {
        cameraError.value = err?.name === 'NotAllowedError'
            ? 'Akses kamera ditolak. Izinkan kamera di browser.'
            : 'Tidak dapat mengakses kamera.';
    }
};

const stopCamera = () => {
    cameraActive.value = false;
    if (scanLoop) { cancelAnimationFrame(scanLoop); scanLoop = null; }
    if (stream) { stream.getTracks().forEach((t) => t.stop()); stream = null; }
    videoEl = null;
    const container = document.getElementById('qr-camera-box');
    if (container) container.innerHTML = '';
};

// ——— Pilih tagihan (multi bulan) ———
const toggleBill = (id: number) => {
    const i = selectedBillIds.value.indexOf(id);
    if (i >= 0) selectedBillIds.value.splice(i, 1);
    else selectedBillIds.value.push(id);
};

const toggleSelectAll = () => {
    if (!scanResult.value) return;
    if (allUnpaidSelected.value) selectedBillIds.value = [];
    else selectedBillIds.value = scanResult.value.unpaidBills.map((b) => b.id);
};

// ——— Bayar tagihan ———
const payBill = (bill: Bill) => {
    if (!scanResult.value) return;
    payingBillId.value = bill.id;

    router.patch(`/admin/payments/${bill.id}`, { status: 'confirmed' }, {
        preserveScroll: true,
        onSuccess: () => {
            scanResult.value!.unpaidBills = scanResult.value!.unpaidBills.filter((b) => b.id !== bill.id);
            scanResult.value!.paidBills.unshift({ ...bill, status: 'confirmed', payment_method: addMode.value === 'scan' ? 'qrcode' : 'manual' });
            selectedBillIds.value = selectedBillIds.value.filter((id) => id !== bill.id);
        },
        onFinish: () => { payingBillId.value = null; },
    });
};

const paySelected = () => {
    if (!scanResult.value || selectedBillIds.value.length === 0) return;
    const ids = [...selectedBillIds.value];
    const method = addMode.value === 'scan' ? 'qrcode' : 'manual';
    scanError.value = '';
    payingBulk.value = true;

    fetch('/admin/payments/bulk', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.content || '',
            Accept: 'application/json',
        },
        body: JSON.stringify({ ids, payment_method: method }),
    })
        .then((res) => {
            if (!res.ok) throw new Error();
            return res.json();
        })
        .then(() => {
            const paid = scanResult.value!.unpaidBills.filter((b) => ids.includes(b.id));
            scanResult.value!.unpaidBills = scanResult.value!.unpaidBills.filter((b) => !ids.includes(b.id));
            scanResult.value!.paidBills = [
                ...paid.map((b) => ({ ...b, status: 'confirmed' as const, payment_method: method })),
                ...scanResult.value!.paidBills,
            ];
            selectedBillIds.value = [];
        })
        .catch(() => { scanError.value = 'Gagal memproses pembayaran. Coba lagi.'; })
        .finally(() => { payingBulk.value = false; });
};

onBeforeUnmount(() => stopCamera());
</script>

<template>
    <Head title="Pembayaran" />

    <AdminLayout title="Pembayaran" :admin="admin">
        <div class="flex flex-1 flex-col gap-2 px-4 py-2 overflow-hidden">

            <!-- Header Card -->
            <PageHeaderCard title="Pembayaran">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/></svg>
                </template>
                <template #actions>
                    <button @click="showFilter = !showFilter" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    </button>
                    <button @click="handleRefresh" class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    </button>
                </template>
            </PageHeaderCard>

            <!-- Dua Card Aksi Pembayaran -->
            <div class="grid grid-cols-2 gap-2 sm:gap-3">
                <!-- Card 1: Scan QR Code -->
                <button @click="openPayPanel('scan')"
                    class="group flex items-center gap-2 sm:gap-3 rounded-lg border-2 border-blue-200 bg-blue-50 px-2.5 sm:px-4 py-2.5 sm:py-3 text-left shadow-sm transition-all hover:border-blue-400 hover:bg-blue-100 hover:shadow-md active:scale-[0.98]">
                    <span class="flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm transition-transform group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:hidden"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden sm:block"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs sm:text-sm font-bold text-blue-900 truncate">Scan QR Code</span>
                        <span class="block text-[10px] sm:text-xs text-blue-600 truncate">Pindai QR pelanggan</span>
                    </span>
                </button>

                <!-- Card 2: Cari Pelanggan -->
                <button @click="openPayPanel('search')"
                    class="group flex items-center gap-2 sm:gap-3 rounded-lg border-2 border-emerald-200 bg-emerald-50 px-2.5 sm:px-4 py-2.5 sm:py-3 text-left shadow-sm transition-all hover:border-emerald-400 hover:bg-emerald-100 hover:shadow-md active:scale-[0.98]">
                    <span class="flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-600 text-white shadow-sm transition-transform group-hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:hidden"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden sm:block"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                    </span>
                    <span class="min-w-0">
                        <span class="block text-xs sm:text-sm font-bold text-emerald-900 truncate">Cari Pelanggan</span>
                        <span class="block text-[10px] sm:text-xs text-emerald-600 truncate">Bayar tagihan bulanan</span>
                    </span>
                </button>
            </div>

            <!-- Filter Bar -->
            <Transition enter-active-class="transition-all duration-200" leave-active-class="transition-all duration-200" enter-from-class="opacity-0 -translate-y-1" leave-to-class="opacity-0 -translate-y-1">
                <div v-if="showFilter" class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
                    <input type="text" v-model="search" @keyup.enter="handleFilter" placeholder="Cari nama pelanggan..."
                        class="flex h-8 w-full sm:w-64 sm:max-w-xs rounded-md border border-gray-300 bg-white px-3 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                    <select v-model="filterStatus" @change="handleFilter"
                        class="h-8 rounded-md border border-gray-300 bg-white px-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="pending">Belum Bayar</option>
                        <option value="confirmed">Lunas</option>
                        <option value="cancelled">Dibatalkan</option>
                    </select>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-2 top-1/2 -translate-y-1/2 text-gray-400"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg>
                        <input ref="monthInput" type="month" v-model="filterMonth" @change="handleFilter" @click="openMonthPicker"
                            class="h-8 rounded-md border border-gray-300 bg-white pl-7 pr-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                    </div>
                    <select v-model="filterTrashed" @change="handleFilter" title="Tampilan data"
                        class="h-8 rounded-md border border-gray-300 bg-white px-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500">
                        <option value="">Data Aktif</option>
                        <option value="only">Sudah Dihapus</option>
                        <option value="with">Aktif + Dihapus</option>
                    </select>
                    <button @click="handleFilter" title="Terapkan Filter"
                        class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    </button>
                    <button @click="showFilter = false" title="Tutup Filter"
                        class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
            </Transition>

            <!-- List Card -->
            <ListCard
                :columns="columns"
                :is-empty="payments.data.length === 0"
                empty-text="Belum ada data pembayaran."
                :empty-icon="true">
                <div v-for="(p, i) in payments.data" :key="p.id"
                    @click="openDetail(p)"
                    class="group cursor-pointer rounded-lg border shadow-sm transition-all hover:shadow-md"
                    :class="p.deleted_at ? 'border-red-200 bg-red-50/40 opacity-70 hover:border-red-300' : 'border-gray-200 bg-white hover:border-blue-300'">
                        <div class="flex items-center gap-3 px-4 py-3">
                            <span class="shrink-0 w-8 text-center rounded-md bg-gray-100 px-1.5 py-0.5 text-xs font-medium text-gray-500">
                                {{ (payments.current_page - 1) * perPage + i + 1 }}
                            </span>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1.5 max-w-full">
                                    <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-sm font-semibold text-gray-900 truncate">
                                        {{ (p as any).customer?.name || p.customer_name || '-' }}
                                    </span>
                                    <span v-if="p.deleted_at" class="inline-flex shrink-0 items-center gap-1 rounded-md bg-red-100 px-1.5 py-0.5 text-[10px] font-semibold text-red-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/></svg>
                                        Terhapus
                                    </span>
                                </div>
                            </div>

                            <div class="hidden md:block shrink-0 w-36">
                                <span v-if="p.invoice_number" class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-mono font-semibold text-blue-700">
                                    {{ p.invoice_number }}
                                </span>
                                <span v-else class="text-xs text-gray-400">-</span>
                            </div>

                            <div class="hidden md:block shrink-0 w-28">
                                <span v-if="(p as any).customer?.phone" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                    {{ (p as any).customer.phone }}
                                </span>
                                <span v-else class="text-xs text-gray-400">-</span>
                            </div>

                            <div class="hidden lg:block shrink-0 w-36">
                                <span v-if="(p as any).customer?.package" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                    {{ (p as any).customer.package.name }}
                                </span>
                                <span v-else class="text-xs text-gray-400">-</span>
                            </div>

                            <div class="hidden xl:block shrink-0 w-40">
                                <span v-if="(p as any).customer?.address" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 truncate max-w-full">
                                    {{ (p as any).customer.address }}
                                </span>
                                <span v-else class="text-xs text-gray-400">-</span>
                            </div>

                            <div class="hidden sm:block shrink-0 w-20">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                    {{ formatMonth(p.billing_month) }}
                                </span>
                            </div>

                            <div class="hidden sm:block shrink-0 w-24">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-900">
                                    {{ formatRupiah(p.amount) }}
                                </span>
                            </div>

                            <div class="hidden lg:block shrink-0 w-20">
                                <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                    {{ (p as any).payment_method === 'qrcode' ? 'QR Code' : 'Manual' }}
                                </span>
                            </div>

                            <div class="shrink-0 w-20">
                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium" :class="statusClass[p.status]">
                                    {{ statusLabel[p.status] }}
                                </span>
                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-gray-300 transition-colors group-hover:text-blue-500"><path d="m9 18 6-6-6-6"/></svg>
                        </div>
                    </div>
            </ListCard>

            <!-- Pagination -->
            <Pagination :pagination="payments" :per-page="perPage" @update:per-page="handlePerPage" />
        </div>

        <!-- ═══ DETAIL PEMBAYARAN (drawer dari kanan) ═══ -->
        <DetailDrawer
            :show="showDetail && !!selectedBill"
            title="Detail Pembayaran"
            :subtitle="selectedBill ? (selectedBill.invoice_number || `Invoice #${selectedBill.id}`) : ''"
            @close="closeDetail">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </template>

            <template v-if="selectedBill">
                <DetailField label="No. Invoice">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg></template>
                    <p v-if="selectedBill.invoice_number" class="text-sm font-mono font-semibold text-blue-700">{{ selectedBill.invoice_number }}</p>
                    <p v-else class="text-sm text-gray-400">Belum ada invoice</p>
                </DetailField>

                <DetailField label="Nama Pelanggan">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></template>
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                            {{ (selectedBill.customer?.name || selectedBill.customer_name || '-').charAt(0).toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-900">{{ selectedBill.customer?.name || selectedBill.customer_name || '-' }}</p>
                            <p class="truncate text-xs text-gray-500">{{ selectedBill.customer?.phone || 'Tanpa telepon' }}</p>
                        </div>
                    </div>
                </DetailField>

                <DetailField label="Paket">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg></template>
                    <p v-if="selectedBill.customer?.package" class="text-sm font-semibold text-gray-900">
                        {{ selectedBill.customer.package.name }}
                        <span class="ml-1 text-xs font-normal text-gray-500">· {{ formatRupiah(selectedBill.customer.package.price) }}</span>
                    </p>
                    <p v-else class="text-sm text-gray-400">Tidak ada paket</p>
                </DetailField>

                <DetailField label="Alamat">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></template>
                    <p class="text-sm text-gray-900">{{ selectedBill.customer?.address || '-' }}</p>
                </DetailField>

                <div class="grid grid-cols-2 gap-3">
                    <DetailField label="Bulan Tagihan">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg></template>
                        <p class="text-sm font-semibold text-gray-900">{{ formatMonth(selectedBill.billing_month) }}</p>
                    </DetailField>
                    <DetailField label="Jumlah">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="2" y2="22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></template>
                        <p class="text-sm font-semibold text-gray-900">{{ formatRupiah(selectedBill.amount) }}</p>
                    </DetailField>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <DetailField label="Metode">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg></template>
                        <p class="text-sm font-semibold text-gray-900">{{ selectedBill.payment_method === 'qrcode' ? 'QR Code' : 'Manual' }}</p>
                    </DetailField>
                    <DetailField label="Status">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg></template>
                        <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium" :class="statusClass[selectedBill.status]">
                            {{ statusLabel[selectedBill.status] }}
                        </span>
                    </DetailField>
                </div>

                <DetailField v-if="selectedBill.notes" label="Catatan">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/></svg></template>
                    <p class="text-sm text-gray-900">{{ selectedBill.notes }}</p>
                </DetailField>

                <DetailField label="Dibuat">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg></template>
                    <p class="text-xs text-gray-900">{{ formatDateTime(selectedBill.created_at) }}</p>
                </DetailField>

                <!-- Riwayat kirim WhatsApp -->
                <div v-if="selectedBill.whatsapp_logs && selectedBill.whatsapp_logs.length" class="space-y-2 pt-1">
                    <h4 class="flex items-center gap-1.5 text-xs font-semibold text-gray-900">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-600"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        Riwayat Kirim WhatsApp
                    </h4>
                    <div v-for="log in selectedBill.whatsapp_logs" :key="log.id"
                        class="rounded-lg border px-3 py-2 text-xs"
                        :class="log.status === 'sent' ? 'border-emerald-200 bg-emerald-50' : log.status === 'failed' ? 'border-red-200 bg-red-50' : 'border-yellow-200 bg-yellow-50'">
                        <div class="flex items-center justify-between gap-2">
                            <span class="font-mono font-medium text-gray-700">{{ log.phone }}</span>
                            <span class="inline-flex items-center gap-1 rounded-md px-1.5 py-0.5 font-semibold"
                                :class="log.status === 'sent' ? 'bg-emerald-100 text-emerald-700' : log.status === 'failed' ? 'bg-red-100 text-red-700' : 'bg-yellow-100 text-yellow-700'">
                                <svg v-if="log.status === 'sent'" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                <svg v-else-if="log.status === 'failed'" xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                {{ log.status === 'sent' ? 'Terkirim' : log.status === 'failed' ? 'Gagal' : 'Menunggu' }}
                            </span>
                        </div>
                        <div class="mt-1 flex items-center gap-2 text-[10px] text-gray-500">
                            <span>Percobaan: {{ log.attempts }}</span>
                            <span v-if="log.sent_at">· {{ formatDateTime(log.sent_at) }}</span>
                            <span v-else-if="log.created_at">· {{ formatDateTime(log.created_at) }}</span>
                        </div>
                        <p v-if="log.error" class="mt-1 text-[10px] text-red-600">⚠ {{ log.error }}</p>
                    </div>
                </div>

                <!-- Aksi cetak bukti -->
                <div v-if="selectedBill.deleted_at" class="pt-1">
                    <button @click="restorePayment(selectedBill)" :disabled="restoringId === selectedBill.id"
                        class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md border border-emerald-300 bg-white px-4 text-sm font-medium text-emerald-700 shadow-sm hover:bg-emerald-50 transition-colors disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"/><path d="M3 3v5h5"/></svg>
                        {{ restoringId === selectedBill.id ? 'Memulihkan...' : 'Pulihkan Data' }}
                    </button>
                </div>
                <div v-else-if="selectedBill.status === 'confirmed'" class="pt-1 space-y-2">
                    <button @click="printReceipt(selectedBill)"
                        class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md bg-blue-600 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                        Cetak Bukti Pembayaran
                    </button>
                    <button v-if="waNeedsResend(selectedBill)" @click="askResend(selectedBill)" :disabled="resendingId === selectedBill.id"
                        class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md border border-emerald-300 bg-white px-4 text-sm font-medium text-emerald-700 shadow-sm hover:bg-emerald-50 transition-colors disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        {{ resendingId === selectedBill.id ? 'Mengirim...' : 'Kirim Ulang WhatsApp' }}
                    </button>
                    <button @click="askCancel(selectedBill)" :disabled="cancellingId === selectedBill.id"
                        class="inline-flex h-9 w-full items-center justify-center gap-1.5 rounded-md border border-red-300 bg-white px-4 text-sm font-medium text-red-600 shadow-sm hover:bg-red-50 transition-colors disabled:opacity-50">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        {{ cancellingId === selectedBill.id ? 'Membatalkan...' : 'Batal Pembayaran' }}
                    </button>
                </div>
            </template>
        </DetailDrawer>

        <!-- ═══ PANEL BAYAR (Scan QR / Cari Pelanggan) ═══ -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="closeModal">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-2xl max-h-[92vh] flex flex-col">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-5 py-4 border-b shrink-0">
                            <div class="flex items-center gap-2.5">
                                <span v-if="addMode === 'scan'" class="flex h-8 w-8 items-center justify-center rounded-lg bg-blue-100 text-blue-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"/><path d="M17 3h2a2 2 0 0 1 2 2v2"/><path d="M21 17v2a2 2 0 0 1-2 2h-2"/><path d="M7 21H5a2 2 0 0 1-2-2v-2"/><path d="M7 12h10"/></svg>
                                </span>
                                <span v-else class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100 text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/></svg>
                                </span>
                                <h3 class="text-base font-semibold text-gray-900">
                                    {{ addMode === 'scan' ? 'Bayar dengan Scan QR' : 'Bayar dengan Cari Pelanggan' }}
                                </h3>
                            </div>
                            <button @click="closeModal" class="rounded-md p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>

                        <!-- Body -->
                        <div class="flex-1 overflow-y-auto p-5 space-y-4">

                            <!-- ═══ SUDAH KETEMU PELANGGAN: tampil tagihan ═══ -->
                            <template v-if="scanResult">
                                <!-- Info Pelanggan (ringkas: nama + paket) -->
                                <div class="flex items-center gap-3 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2">
                                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-white text-sm font-bold">
                                        {{ scanResult.customer.name.charAt(0).toUpperCase() }}
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-gray-900">{{ scanResult.customer.name }}</p>
                                        <p class="truncate text-xs text-blue-700">
                                            <span v-if="scanResult.customer.package">{{ scanResult.customer.package.name }}</span>
                                            <span v-else class="text-gray-400">Tanpa paket</span>
                                        </p>
                                    </div>
                                    <button @click="scanResult = null; qrInput = ''; selectedBillIds = []"
                                        class="shrink-0 text-xs text-blue-600 hover:text-blue-800 underline">
                                        {{ addMode === 'scan' ? 'Scan ulang' : 'Ganti' }}
                                    </button>
                                </div>

                                <!-- Tagihan Belum Dibayar -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900">
                                            Tagihan Belum Dibayar
                                            <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-yellow-100 text-yellow-700 text-xs font-bold px-2 py-0.5">
                                                {{ scanResult.unpaidBills.length }}
                                            </span>
                                        </h4>
                                        <span v-if="scanResult.unpaidBills.length > 0" class="text-sm font-bold text-red-600">
                                            {{ formatRupiah(unpaidTotal) }}
                                        </span>
                                    </div>

                                    <div v-if="scanResult.unpaidBills.length === 0"
                                        class="rounded-lg bg-emerald-50 border border-emerald-200 p-4 text-center">
                                        <p class="text-sm font-medium text-emerald-700">Semua tagihan sudah lunas! ✅</p>
                                    </div>

                                    <div v-else class="space-y-2">
                                        <!-- Pilih semua -->
                                        <label class="flex items-center gap-2.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 cursor-pointer select-none">
                                            <input type="checkbox" :checked="allUnpaidSelected" @change="toggleSelectAll"
                                                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                            <span class="text-xs font-medium text-gray-700">Pilih semua bulan (multi-bulan)</span>
                                        </label>

                                        <!-- Daftar bulan -->
                                        <div v-for="bill in scanResult.unpaidBills" :key="bill.id"
                                            class="flex items-center gap-3 rounded-lg border p-3 transition-colors"
                                            :class="selectedBillIds.includes(bill.id) ? 'border-blue-300 bg-blue-50' : 'border-yellow-200 bg-yellow-50'">
                                            <input type="checkbox" :checked="selectedBillIds.includes(bill.id)" @change="toggleBill(bill.id)"
                                                class="h-4 w-4 shrink-0 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                            <div class="flex-1 min-w-0 cursor-pointer" @click="toggleBill(bill.id)">
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-sm text-gray-900">{{ formatMonth(bill.billing_month) }}</span>
                                                    <span class="inline-flex items-center rounded-md bg-yellow-100 px-1.5 py-0.5 text-[10px] font-semibold text-yellow-700">BELUM BAYAR</span>
                                                </div>
                                                <p v-if="bill.notes" class="text-xs text-gray-500 truncate">{{ bill.notes }}</p>
                                            </div>
                                            <span class="font-bold text-sm text-gray-900 shrink-0">{{ formatRupiah(bill.amount) }}</span>
                                            <button @click.stop="payBill(bill)" :disabled="payingBillId === bill.id || payingBulk"
                                                class="shrink-0 inline-flex h-8 items-center gap-1 rounded-md bg-emerald-600 px-3 text-xs font-medium text-white hover:bg-emerald-700 transition-colors disabled:opacity-50">
                                                <svg v-if="payingBillId !== bill.id" xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                {{ payingBillId === bill.id ? '...' : 'Bayar' }}
                                            </button>
                                        </div>

                                        <!-- Aksi bayar terpilih -->
                                        <div v-if="selectedBillIds.length > 0"
                                            class="sticky bottom-0 -mx-1 flex items-center justify-between gap-3 rounded-lg border border-emerald-300 bg-emerald-50 px-3 py-2.5 shadow-sm">
                                            <div class="text-xs text-emerald-800">
                                                <span class="font-bold">{{ selectedBillIds.length }} bulan dipilih</span>
                                                <span class="mx-1">·</span>
                                                <span class="font-bold">{{ formatRupiah(selectedTotal) }}</span>
                                            </div>
                                            <button @click="paySelected" :disabled="payingBulk"
                                                class="inline-flex h-9 items-center gap-1.5 rounded-md bg-emerald-600 px-4 text-sm font-semibold text-white shadow-sm hover:bg-emerald-700 transition-colors disabled:opacity-50">
                                                <svg v-if="!payingBulk" xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                                {{ payingBulk ? 'Memproses...' : `Bayar ${selectedBillIds.length} Bulan` }}
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tagihan Lunas -->
                                <div v-if="scanResult.paidBills.length > 0" class="space-y-2">
                                    <h4 class="text-sm font-semibold text-gray-900">
                                        Tagihan Lunas
                                        <span class="ml-1.5 inline-flex items-center justify-center rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold px-2 py-0.5">
                                            {{ scanResult.paidBills.length }}
                                        </span>
                                    </h4>
                                    <div class="max-h-40 overflow-y-auto space-y-1.5">
                                        <div v-for="bill in scanResult.paidBills" :key="bill.id"
                                            class="flex items-center justify-between rounded-md border bg-gray-50 px-3 py-2">
                                            <div class="flex items-center gap-2 text-xs">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-emerald-500"><path d="M20 6 9 17l-5-5"/></svg>
                                                <span class="font-medium text-gray-700">{{ formatMonth(bill.billing_month) }}</span>
                                                <span v-if="bill.invoice_number" class="font-mono font-semibold text-blue-700">{{ bill.invoice_number }}</span>
                                                <span class="text-gray-400">·</span>
                                                <span class="text-gray-500">{{ formatRupiah(bill.amount) }}</span>
                                            </div>
                                            <span class="text-[10px] font-medium" :class="bill.status === 'confirmed' ? 'text-emerald-600' : 'text-red-500'">
                                                {{ statusLabel[bill.status] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <p v-if="scanError" class="text-xs text-red-600">{{ scanError }}</p>
                            </template>

                            <!-- ═══ MODE SCAN: belum scan ═══ -->
                            <template v-else-if="addMode === 'scan'">
                                <!-- Manual input -->
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Masukkan QR Code</label>
                                    <div class="flex gap-2">
                                        <input type="text" v-model="qrInput" @keyup.enter="scanQr" placeholder="Contoh: DFB-A1B2C3D4"
                                            class="flex h-9 flex-1 rounded-md border border-input bg-white px-3 py-1 text-sm font-mono shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                        <button type="button" @click="scanQr" :disabled="scanning || !qrInput.trim()"
                                            class="inline-flex h-9 items-center rounded-md bg-blue-600 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors disabled:opacity-50">
                                            {{ scanning ? 'Mencari...' : 'Cari' }}
                                        </button>
                                    </div>
                                    <p v-if="scanError" class="text-xs text-red-600">{{ scanError }}</p>
                                </div>

                                <!-- Camera -->
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <label class="text-sm font-medium text-gray-900">Scanner Kamera</label>
                                        <button v-if="!cameraActive" type="button" @click="startCamera"
                                            class="inline-flex h-7 items-center gap-1 rounded-md bg-gray-900 px-2.5 text-xs font-medium text-white hover:bg-gray-700 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                            Nyalakan Kamera
                                        </button>
                                        <button v-else type="button" @click="stopCamera"
                                            class="inline-flex h-7 items-center gap-1 rounded-md bg-red-600 px-2.5 text-xs font-medium text-white hover:bg-red-700 transition-colors">
                                            Matikan
                                        </button>
                                    </div>

                                    <div id="qr-camera-box"
                                        class="w-full aspect-video rounded-lg overflow-hidden bg-gray-900 flex items-center justify-center">
                                        <div v-if="!cameraActive" class="text-center text-gray-500 p-4">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-600"><path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z"/><circle cx="12" cy="13" r="3"/></svg>
                                            <p class="mt-1 text-xs">Klik "Nyalakan Kamera" untuk memindai QR</p>
                                        </div>
                                    </div>
                                    <p v-if="cameraError" class="text-xs text-red-600">{{ cameraError }}</p>
                                </div>
                            </template>

                            <!-- ═══ MODE CARI: belum pilih pelanggan ═══ -->
                            <template v-else>
                                <div class="space-y-1.5 relative">
                                    <label class="text-sm font-medium text-gray-900">Cari Pelanggan</label>
                                    <input type="text" v-model="customerQuery" placeholder="Ketik nama, no. telepon, atau QR..."
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-emerald-500" />
                                    <div v-if="customerQuery && filteredCustomers.length > 0"
                                        class="absolute z-10 mt-1 w-full rounded-md border bg-white shadow-lg max-h-56 overflow-y-auto">
                                        <button v-for="c in filteredCustomers" :key="c.id" type="button" @click="selectSearchCustomer(c)"
                                            class="w-full px-3 py-2 text-left text-sm hover:bg-emerald-50 border-b last:border-b-0 flex items-center justify-between gap-2">
                                            <div class="min-w-0">
                                                <span class="font-medium truncate block">{{ c.name }}</span>
                                                <span v-if="c.package" class="text-xs text-gray-500">{{ c.package.name }}</span>
                                            </div>
                                            <code class="text-[10px] text-gray-400 shrink-0">{{ c.qr_code }}</code>
                                        </button>
                                    </div>
                                    <p v-if="customerQuery && filteredCustomers.length === 0 && !searchLoading" class="text-xs text-gray-400">
                                        Pelanggan tidak ditemukan.
                                    </p>
                                    <p v-if="searchLoading" class="text-xs text-gray-500">Memuat tagihan...</p>
                                    <p v-if="scanError" class="text-xs text-red-600">{{ scanError }}</p>
                                </div>

                                <div class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-6 text-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mx-auto text-gray-400"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                    <p class="mt-2 text-sm text-gray-500">Pilih pelanggan untuk melihat<br/>tagihan bulan yang belum dibayar.</p>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- ═══ MODAL VERIFIKASI BATAL PEMBAYARAN ═══ -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                <div v-if="confirmCancelBill" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/50 p-4" @click.self="confirmCancelBill = null">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><path d="M12 9v4"/><path d="M12 17h.01"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Batalkan Pembayaran?</h3>
                                <p class="text-xs text-gray-500">Tindakan ini tidak dapat diurungkan.</p>
                            </div>
                        </div>

                        <div class="px-5 py-4 space-y-2">
                            <div class="rounded-lg bg-gray-50 border border-gray-200 px-3 py-2 text-xs space-y-1">
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Invoice</span>
                                    <span class="font-mono font-semibold text-blue-700">{{ confirmCancelBill.invoice_number || '#' + confirmCancelBill.id }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Pelanggan</span>
                                    <span class="font-medium text-gray-900 truncate">{{ confirmCancelBill.customer?.name || confirmCancelBill.customer_name }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Bulan</span>
                                    <span class="font-medium text-gray-900">{{ formatMonth(confirmCancelBill.billing_month) }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Jumlah</span>
                                    <span class="font-semibold text-gray-900">{{ formatRupiah(confirmCancelBill.amount) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600">Tagihan bulan ini akan kembali menjadi <span class="font-medium text-yellow-700">belum dibayar</span>.</p>
                        </div>

                        <div class="flex gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50">
                            <button @click="confirmCancelBill = null"
                                class="flex-1 h-9 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                                Kembali
                            </button>
                            <button @click="confirmCancel" :disabled="!!cancellingId"
                                class="flex-1 h-9 rounded-md bg-red-600 text-sm font-medium text-white shadow-sm hover:bg-red-700 transition-colors disabled:opacity-50">
                                {{ cancellingId ? 'Membatalkan...' : 'Ya, Batalkan' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>

        <!-- ═══ MODAL VERIFIKASI KIRIM ULANG WA ═══ -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                <div v-if="confirmResendBill" class="fixed inset-0 z-[1000] flex items-center justify-center bg-black/50 p-4" @click.self="confirmResendBill = null">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-sm overflow-hidden">
                        <div class="flex items-center gap-3 px-5 py-4 border-b border-gray-100">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900">Kirim Ulang WhatsApp?</h3>
                                <p class="text-xs text-gray-500">Nota pembayaran akan dikirim ke pelanggan.</p>
                            </div>
                        </div>

                        <div class="px-5 py-4 space-y-2">
                            <div class="rounded-lg bg-gray-50 border border-gray-200 px-3 py-2 text-xs space-y-1">
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Invoice</span>
                                    <span class="font-mono font-semibold text-blue-700">{{ confirmResendBill.invoice_number || '#' + confirmResendBill.id }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Pelanggan</span>
                                    <span class="font-medium text-gray-900 truncate">{{ confirmResendBill.customer?.name || confirmResendBill.customer_name }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">No. WhatsApp</span>
                                    <span class="font-mono font-medium text-gray-900">{{ confirmResendBill.customer?.phone || '-' }}</span>
                                </div>
                                <div class="flex justify-between gap-2">
                                    <span class="text-gray-500">Periode</span>
                                    <span class="font-medium text-gray-900">{{ formatMonth(confirmResendBill.billing_month) }}</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600">Kirim pesan nota pembayaran (periode <span class="font-medium">{{ formatMonth(confirmResendBill.billing_month) }}</span>) ke nomor di atas?</p>
                        </div>

                        <div class="flex gap-2 px-5 py-4 border-t border-gray-100 bg-gray-50">
                            <button @click="confirmResendBill = null"
                                class="flex-1 h-9 rounded-md border border-gray-300 bg-white text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                                Kembali
                            </button>
                            <button @click="resendWa(confirmResendBill)" :disabled="!!resendingId"
                                class="flex-1 h-9 rounded-md bg-emerald-600 text-sm font-medium text-white shadow-sm hover:bg-emerald-700 transition-colors disabled:opacity-50">
                                {{ resendingId ? 'Mengirim...' : 'Ya, Kirim' }}
                            </button>
                        </div>
                    </div>
                </div>
            </Transition>
        </Teleport>
    </AdminLayout>
</template>
