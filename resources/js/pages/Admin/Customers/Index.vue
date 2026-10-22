<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed, watch, onUnmounted } from 'vue';
import AdminLayout from '../../../components/admin/AdminLayout.vue';
import PageHeaderCard from '../../../components/admin/PageHeaderCard.vue';
import ListCard from '../../../components/admin/ListCard.vue';
import Pagination from '../../../components/admin/Pagination.vue';
import DetailDrawer from '../../../components/admin/DetailDrawer.vue';
import DetailField from '../../../components/admin/DetailField.vue';

type Pkg = {
    id: number;
    name: string;
    price: string;
    speed: string | null;
};

type Customer = {
    id: number;
    name: string;
    phone: string | null;
    address: string | null;
    ip_address: string | null;
    is_active: boolean;
    qr_code: string;
    package: Pkg | null;
    created_at: string;
    updated_at: string | null;
};

type Payment = {
    id: number;
    invoice_number?: string | null;
    billing_month: string | null;
    amount: string;
    status: 'pending' | 'confirmed' | 'cancelled';
    payment_method: string;
    notes: string | null;
    created_at: string;
};

const props = defineProps<{
    admin: { name: string; email: string; role: string };
    customers: {
        data: Customer[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
    packages: Pkg[];
    stats: { total: number; active: number; inactive: number };
    filters: { search?: string; status?: string };
}>();

const packages = computed(() => props.packages || []);

// ——— Filter ———
const search = ref(props.filters.search || '');
const statusFilter = ref(props.filters.status ?? '');
const showFilter = ref(false);
const perPage = ref(props.customers.per_page || 15);

const handleRefresh = () => {
    router.reload({ only: ['customers', 'stats'] });
};

const handlePerPage = (value: number) => {
    perPage.value = value;
    router.get('/admin/customers', { search: search.value, status: statusFilter.value, per_page: value }, { preserveState: true, replace: true });
};

const applyFilters = () => {
    router.get('/admin/customers', { search: search.value, status: statusFilter.value, per_page: perPage.value }, { preserveState: true, replace: true });
};

// ——— Auto-cari (debounce) ———
let searchTimer: ReturnType<typeof setTimeout> | undefined;
watch([search, statusFilter], () => {
    clearTimeout(searchTimer);
    searchTimer = setTimeout(() => {
        router.get('/admin/customers', { search: search.value, status: statusFilter.value, per_page: perPage.value }, { preserveState: true, replace: true });
    }, 400);
});
onUnmounted(() => clearTimeout(searchTimer));

// ——— Detail drawer ———
const detail = ref<Customer | null>(null);
const showDetail = ref(false);
const history = ref<Payment[]>([]);
const historyLoading = ref(false);

const openDetail = async (c: Customer) => {
    detail.value = c;
    showDetail.value = true;
    history.value = [];
    historyLoading.value = true;

    try {
        const res = await fetch(`/admin/payments/customer/${c.id}/bills`, {
            headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        });
        if (res.ok) {
            const data = await res.json();
            history.value = data.payments || [];
        }
    } catch {
        history.value = [];
    } finally {
        historyLoading.value = false;
    }
};

const closeDetail = () => {
    showDetail.value = false;
    setTimeout(() => { detail.value = null; history.value = []; }, 200);
};

// ——— Modal add/edit ———
const showModal = ref(false);
const editing = ref<Customer | null>(null);

const form = ref({
    name: '',
    phone: '',
    address: '',
    ip_address: '',
    is_active: true,
    package_id: '' as string | number,
});
const formErrors = ref<Record<string, string>>({});
const submitting = ref(false);

const formatRupiah = (val: string | number) => {
    const num = typeof val === 'string' ? parseFloat(val) : val;
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
};

const formatDateTime = (val: string | null | undefined) => {
    if (!val) return '-';
    return new Date(val).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const formatMonth = (val: string | null) => {
    if (!val) return '-';
    const [y, m] = val.split('-');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${months[parseInt(m) - 1]} ${y}`;
};

const statusLabel: Record<string, string> = { pending: 'Belum Bayar', confirmed: 'Lunas', cancelled: 'Dibatalkan' };
const statusClass: Record<string, string> = {
    pending: 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
    confirmed: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    cancelled: 'bg-red-50 text-red-700 ring-red-600/20',
};

const historyPaid = computed(() => history.value.filter((p) => p.status === 'confirmed'));
const historyUnpaid = computed(() => history.value.filter((p) => p.status === 'pending'));
const historyTotalPaid = computed(() => historyPaid.value.reduce((s, p) => s + parseFloat(p.amount), 0));

const openCreate = () => {
    showDetail.value = false;
    detail.value = null;
    history.value = [];
    editing.value = null;
    form.value = { name: '', phone: '', address: '', ip_address: '', is_active: true, package_id: '' };
    formErrors.value = {};
    showModal.value = true;
};

const openEdit = (c: Customer) => {
    editing.value = c;
    form.value = {
        name: c.name,
        phone: c.phone || '',
        address: c.address || '',
        ip_address: c.ip_address || '',
        is_active: c.is_active,
        package_id: c.package?.id || '',
    };
    formErrors.value = {};
    showModal.value = true;
};

const submitForm = () => {
    formErrors.value = {};
    submitting.value = true;

    const payload = {
        name: form.value.name,
        phone: form.value.phone,
        address: form.value.address,
        ip_address: form.value.ip_address,
        is_active: form.value.is_active,
        package_id: form.value.package_id || null,
    };

    const options = {
        preserveScroll: true,
        onSuccess: () => { showModal.value = false; },
        onError: (errors: any) => { formErrors.value = errors; },
        onFinish: () => { submitting.value = false; },
    };

    if (editing.value) {
        router.patch(`/admin/customers/${editing.value.id}`, payload, options);
    } else {
        router.post('/admin/customers', payload, options);
    }
};

// ——— Kolom daftar ———
const columns = [
    { label: 'Nama Pelanggan', class: 'flex-1 min-w-0' },
    { label: 'Telepon', class: 'hidden md:block shrink-0 w-28' },
    { label: 'IP Address', class: 'hidden lg:block shrink-0 w-32' },
    { label: 'Paket', class: 'hidden lg:block shrink-0 w-36' },
    { label: 'Alamat', class: 'hidden xl:block shrink-0 w-40' },
    { label: 'QR Code', class: 'hidden xl:block shrink-0 w-32' },
];
</script>

<template>
    <Head title="Data Pelanggan" />

    <AdminLayout title="Data Master / Pelanggan" :admin="admin">
        <div class="flex flex-1 flex-col gap-2 px-4 py-2 overflow-hidden">

            <!-- ══ Bagian atas (fixed, tidak ikut scroll) ══ -->
            <div class="shrink-0 flex flex-col gap-2">
            <!-- Header Card -->
            <PageHeaderCard title="Pelanggan">
                <template #icon>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </template>
                <template #actions>
                    <button @click="showFilter = !showFilter" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                    </button>
                    <button @click="handleRefresh" class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                    </button>
                    <button @click="openCreate" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                    </button>
                </template>
            </PageHeaderCard>

            <!-- Filter Bar -->
            <Transition enter-active-class="transition-all duration-200" leave-active-class="transition-all duration-200" enter-from-class="opacity-0 -translate-y-1" leave-to-class="opacity-0 -translate-y-1">
                <div v-if="showFilter" class="flex flex-wrap items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
                    <input type="text" v-model="search" placeholder="Cari nama, telepon, IP, atau QR code..."
                        class="flex h-8 w-full max-w-sm rounded-md border border-gray-300 bg-white px-3 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                    <select v-model="statusFilter"
                        class="h-8 rounded-md border border-gray-300 bg-white px-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500">
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Tidak Aktif</option>
                    </select>
                    <button @click="showFilter = false" title="Tutup Filter"
                        class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                    </button>
                </div>
            </Transition>

            <!-- Stats Card -->
            <div class="grid grid-cols-3 gap-2">
                <div class="flex items-center gap-2 sm:gap-3 rounded-lg border border-gray-300 bg-white px-2.5 sm:px-4 py-2.5 sm:py-3 shadow-sm">
                    <span class="inline-flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:hidden"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden sm:block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 truncate">Total</p>
                        <p class="text-lg sm:text-xl font-semibold text-gray-900 leading-tight">{{ stats.total }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 rounded-lg border border-gray-300 bg-white px-2.5 sm:px-4 py-2.5 sm:py-3 shadow-sm">
                    <span class="inline-flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:hidden"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden sm:block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><polyline points="16 11 18 13 22 9"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 truncate">Aktif</p>
                        <p class="text-lg sm:text-xl font-semibold text-emerald-600 leading-tight">{{ stats.active }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3 rounded-lg border border-gray-300 bg-white px-2.5 sm:px-4 py-2.5 sm:py-3 shadow-sm">
                    <span class="inline-flex h-8 w-8 sm:h-10 sm:w-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="sm:hidden"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" y1="8" x2="22" y2="13"/><line x1="22" y1="8" x2="17" y2="13"/></svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="hidden sm:block"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="17" y1="8" x2="22" y2="13"/><line x1="22" y1="8" x2="17" y2="13"/></svg>
                    </span>
                    <div class="min-w-0">
                        <p class="text-[10px] sm:text-xs text-gray-500 truncate">Nonaktif</p>
                        <p class="text-lg sm:text-xl font-semibold text-red-600 leading-tight">{{ stats.inactive }}</p>
                    </div>
                </div>
            </div>
            </div>
            <!-- ══ End bagian atas ══ -->

            <!-- List Card -->
            <ListCard
                :columns="columns"
                :is-empty="customers.data.length === 0"
                empty-text="Belum ada data pelanggan."
                :empty-icon="false">
                <div v-for="(c, i) in customers.data" :key="c.id"
                    @click="openDetail(c)"
                    class="group cursor-pointer rounded-lg border border-gray-200 bg-white shadow-sm transition-all hover:shadow-md hover:border-blue-300">
                    <div class="flex items-center gap-3 px-4 py-3">
                        <!-- Nomor -->
                        <span class="shrink-0 w-8 text-center rounded-md bg-gray-100 px-1.5 py-0.5 text-xs font-medium text-gray-500">
                            {{ (customers.current_page - 1) * perPage + i + 1 }}
                        </span>

                        <!-- Nama -->
                        <div class="flex-1 min-w-0 flex items-center gap-2">
                            <span class="h-2 w-2 shrink-0 rounded-full" :class="c.is_active ? 'bg-emerald-500' : 'bg-red-400'" :title="c.is_active ? 'Aktif' : 'Tidak Aktif'"></span>
                            <span class="inline-flex items-center max-w-full rounded-md bg-gray-100 px-2 py-1 text-sm font-semibold text-gray-900 truncate">
                                {{ c.name }}
                            </span>
                        </div>

                        <!-- Telepon -->
                        <div class="hidden md:block shrink-0 w-28">
                            <span v-if="c.phone" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                {{ c.phone }}
                            </span>
                            <span v-else class="text-xs text-gray-400">-</span>
                        </div>

                        <!-- IP Address -->
                        <div class="hidden lg:block shrink-0 w-32">
                            <span v-if="c.ip_address" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-mono font-medium text-gray-700">
                                {{ c.ip_address }}
                            </span>
                            <span v-else class="text-xs text-gray-400">-</span>
                        </div>

                        <!-- Paket -->
                        <div class="hidden lg:block shrink-0 w-36">
                            <span v-if="c.package" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">
                                {{ c.package.name }}
                            </span>
                            <span v-else class="text-xs text-gray-400">-</span>
                        </div>

                        <!-- Alamat -->
                        <div class="hidden xl:block shrink-0 w-40">
                            <span v-if="c.address" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700 truncate max-w-full">
                                {{ c.address }}
                            </span>
                            <span v-else class="text-xs text-gray-400">-</span>
                        </div>

                        <!-- QR Code -->
                        <div class="hidden xl:block shrink-0 w-32">
                            <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-mono font-medium text-gray-700">
                                {{ c.qr_code }}
                            </span>
                        </div>

                        <!-- Arrow -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="shrink-0 text-gray-300 transition-colors group-hover:text-blue-500"><path d="m9 18 6-6-6-6"/></svg>
                    </div>
                </div>
            </ListCard>

            <!-- Pagination (fixed di bawah) -->
            <div class="shrink-0">
                <Pagination :pagination="customers" :per-page="perPage" @update:per-page="handlePerPage" />
            </div>
        </div>

        <!-- ═══ DETAIL PELANGGAN (drawer dari kanan) ═══ -->
        <DetailDrawer
            :show="showDetail && !!detail"
            title="Detail Pelanggan"
            :subtitle="detail ? `#${detail.id} · ${detail.qr_code}` : ''"
            @close="closeDetail">
            <template #icon>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </template>

            <template v-if="detail">
                <!-- Nama -->
                <DetailField label="Nama">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></template>
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-semibold text-sm">
                            {{ detail.name.charAt(0).toUpperCase() }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-gray-900">{{ detail.name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ detail.phone || 'Tanpa telepon' }}</p>
                        </div>
                    </div>
                </DetailField>

                <!-- Telepon -->
                <DetailField label="Telepon">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg></template>
                    <p class="text-sm text-gray-900">{{ detail.phone || '-' }}</p>
                </DetailField>

                <!-- Status -->
                <DetailField label="Status">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg></template>
                    <span class="inline-flex items-center gap-1.5 rounded-md px-2 py-1 text-xs font-medium ring-1"
                        :class="detail.is_active ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' : 'bg-red-50 text-red-700 ring-red-600/20'">
                        <span class="h-1.5 w-1.5 rounded-full" :class="detail.is_active ? 'bg-emerald-500' : 'bg-red-500'"></span>
                        {{ detail.is_active ? 'Aktif' : 'Tidak Aktif' }}
                    </span>
                </DetailField>

                <!-- Alamat -->
                <DetailField label="Alamat">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg></template>
                    <p class="text-sm text-gray-900">{{ detail.address || '-' }}</p>
                </DetailField>

                <!-- IP Address -->
                <DetailField label="IP Address">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg></template>
                    <p class="text-sm font-mono text-gray-900">{{ detail.ip_address || '-' }}</p>
                </DetailField>

                <!-- Paket -->
                <DetailField label="Paket Langganan">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg></template>
                    <p v-if="detail.package" class="text-sm font-semibold text-gray-900">
                        {{ detail.package.name }}
                        <span class="ml-1 text-xs font-normal text-gray-500">· {{ formatRupiah(detail.package.price) }}</span>
                    </p>
                    <p v-else class="text-sm text-gray-400">Tidak ada paket</p>
                </DetailField>

                <!-- QR Code -->
                <DetailField label="QR Code">
                    <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="5" height="5" x="3" y="3" rx="1"/><rect width="5" height="5" x="16" y="3" rx="1"/><rect width="5" height="5" x="3" y="16" rx="1"/><path d="M21 16h-3a2 2 0 0 0-2 2v3"/><path d="M21 21v.01"/><path d="M12 7v3a2 2 0 0 1-2 2H7"/><path d="M3 12h.01"/><path d="M12 3h.01"/><path d="M12 16v.01"/><path d="M16 12h1"/><path d="M21 12v.01"/><path d="M12 21v-1"/></svg></template>
                    <code class="text-xs font-mono text-gray-900">{{ detail.qr_code }}</code>
                </DetailField>

                <!-- Dibuat & Diubah -->
                <div class="grid grid-cols-2 gap-3">
                    <DetailField label="Dibuat">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/></svg></template>
                        <p class="text-xs text-gray-900">{{ formatDateTime(detail.created_at) }}</p>
                    </DetailField>
                    <DetailField label="Diperbarui">
                        <template #icon><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg></template>
                        <p class="text-xs text-gray-900">{{ formatDateTime(detail.updated_at) }}</p>
                    </DetailField>
                </div>

                <!-- ═══ RIWAYAT PEMBAYARAN ═══ -->
                <div class="border-t border-gray-200 pt-4 space-y-3">
                    <div class="flex items-center justify-between">
                        <h4 class="flex items-center gap-1.5 text-sm font-semibold text-gray-900">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            Riwayat Pembayaran
                        </h4>
                        <div v-if="history.length > 0" class="flex items-center gap-1.5">
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">
                                {{ historyPaid.length }} Lunas
                            </span>
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-2 py-0.5 text-[10px] font-bold text-yellow-700">
                                {{ historyUnpaid.length }} Belum
                            </span>
                        </div>
                    </div>

                    <!-- Ringkas total -->
                    <div v-if="history.length > 0" class="rounded-lg bg-blue-50 border border-blue-200 px-3 py-2">
                        <p class="text-xs text-blue-700">
                            Total sudah dibayar
                            <span class="ml-1 font-bold text-blue-900">{{ formatRupiah(historyTotalPaid) }}</span>
                        </p>
                    </div>

                    <!-- Loading -->
                    <div v-if="historyLoading" class="flex items-center justify-center py-6">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="animate-spin text-blue-600"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg>
                        <span class="ml-2 text-xs text-gray-500">Memuat riwayat...</span>
                    </div>

                    <!-- Kosong -->
                    <div v-else-if="history.length === 0" class="rounded-lg border border-dashed border-gray-300 bg-gray-50 p-5 text-center">
                        <p class="text-sm text-gray-400">Belum ada riwayat pembayaran.</p>
                    </div>

                    <!-- Daftar riwayat -->
                    <div v-else class="space-y-1.5">
                        <div v-for="p in history" :key="p.id"
                            class="flex items-center gap-2.5 rounded-lg border px-3 py-2"
                            :class="p.status === 'confirmed' ? 'border-emerald-200 bg-emerald-50' : p.status === 'pending' ? 'border-yellow-200 bg-yellow-50' : 'border-red-200 bg-red-50'">
                            <div class="flex h-7 w-7 shrink-0 items-center justify-center rounded-full"
                                :class="p.status === 'confirmed' ? 'bg-emerald-100 text-emerald-600' : p.status === 'pending' ? 'bg-yellow-100 text-yellow-600' : 'bg-red-100 text-red-600'">
                                <svg v-if="p.status === 'confirmed'" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                <svg v-else-if="p.status === 'pending'" xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                                <svg v-else xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900">{{ formatMonth(p.billing_month) }}</p>
                                <p v-if="p.invoice_number" class="text-[11px] font-mono font-semibold text-blue-700">
                                    {{ p.invoice_number }}
                                </p>
                                <p class="text-[11px] text-gray-500">
                                    {{ p.payment_method === 'qrcode' ? 'QR Code' : 'Manual' }}
                                    <span v-if="p.notes"> · {{ p.notes }}</span>
                                </p>
                            </div>
                            <div class="text-right shrink-0">
                                <p class="text-sm font-bold text-gray-900">{{ formatRupiah(p.amount) }}</p>
                                <span class="inline-flex items-center rounded-md px-1.5 py-0.5 text-[10px] font-medium" :class="statusClass[p.status]">
                                    {{ statusLabel[p.status] }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Aksi (footer) -->
            <template #footer>
                <div v-if="detail" class="flex items-center justify-between gap-2">
                    <button @click="closeDetail"
                        class="inline-flex h-9 items-center gap-1.5 rounded-md bg-gray-100 px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                        Exit
                    </button>
                    <button @click="openEdit(detail); closeDetail()"
                        class="inline-flex h-9 items-center gap-1.5 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                        Edit
                    </button>
                    <button @click="closeDetail"
                        class="inline-flex h-9 items-center gap-1.5 rounded-md bg-yellow-400 px-3 py-2 text-sm font-medium text-yellow-900 shadow-sm hover:bg-yellow-500 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                        Kembali
                    </button>
                </div>
            </template>
        </DetailDrawer>

        <!-- ═══ MODAL TAMBAH / EDIT ═══ -->
        <Teleport to="body">
            <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                <div v-if="showModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="showModal = false">
                    <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                        <div class="flex items-center justify-between p-5 border-b border-gray-200">
                            <div class="flex items-center gap-2.5">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-lg" :class="editing ? 'bg-amber-50 text-amber-600' : 'bg-blue-50 text-blue-600'">
                                    <svg v-if="editing" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/></svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                </span>
                                <h3 class="text-lg font-semibold text-gray-900">{{ editing ? 'Edit Pelanggan' : 'Tambah Pelanggan' }}</h3>
                            </div>
                            <button @click="showModal = false" class="rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                        <form @submit.prevent="submitForm" class="p-5 space-y-4">
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    Nama *
                                </label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                                    <input type="text" v-model="form.name" placeholder="Nama pelanggan"
                                        class="flex h-9 w-full rounded-md border border-gray-300 bg-white pl-9 pr-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                </div>
                                <p v-if="formErrors.name" class="text-xs text-red-600">{{ formErrors.name }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    Telepon
                                </label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                                    <input type="text" v-model="form.phone" placeholder="08xxxxxxxxxx"
                                        class="flex h-9 w-full rounded-md border border-gray-300 bg-white pl-9 pr-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                </div>
                                <p v-if="formErrors.phone" class="text-xs text-red-600">{{ formErrors.phone }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    Alamat
                                </label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-3 top-2.5 text-gray-400"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <textarea v-model="form.address" rows="2" placeholder="Alamat pelanggan"
                                        class="flex w-full rounded-md border border-gray-300 bg-white pl-9 pr-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500"></textarea>
                                </div>
                                <p v-if="formErrors.address" class="text-xs text-red-600">{{ formErrors.address }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>
                                    IP Address
                                </label>
                                <div class="relative">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"><rect width="20" height="8" x="2" y="2" rx="2" ry="2"/><rect width="20" height="8" x="2" y="14" rx="2" ry="2"/><line x1="6" x2="6.01" y1="6" y2="6"/><line x1="6" x2="6.01" y1="18" y2="18"/></svg>
                                    <input type="text" v-model="form.ip_address" placeholder="Contoh: 192.168.1.10"
                                        class="flex h-9 w-full rounded-md border border-gray-300 bg-white pl-9 pr-3 py-1 text-sm font-mono shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                </div>
                                <p v-if="formErrors.ip_address" class="text-xs text-red-600">{{ formErrors.ip_address }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/></svg>
                                    Status Pelanggan
                                </label>
                                <div class="flex items-center gap-3 rounded-md border border-gray-300 bg-white px-3 py-2">
                                    <span class="text-sm" :class="form.is_active ? 'text-emerald-600' : 'text-gray-400'">
                                        {{ form.is_active ? 'Aktif' : 'Tidak Aktif' }}
                                    </span>
                                    <button type="button" @click="form.is_active = !form.is_active" role="switch" :aria-checked="form.is_active"
                                        class="ml-auto relative inline-flex h-6 w-11 shrink-0 items-center rounded-full transition-colors"
                                        :class="form.is_active ? 'bg-emerald-500' : 'bg-gray-300'">
                                        <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform"
                                            :class="form.is_active ? 'translate-x-5' : 'translate-x-0.5'"></span>
                                    </button>
                                </div>
                                <p v-if="formErrors.is_active" class="text-xs text-red-600">{{ formErrors.is_active }}</p>
                            </div>
                            <div class="space-y-1.5">
                                <label class="inline-flex items-center gap-1.5 text-sm font-medium text-gray-900">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-gray-400"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                                    Paket Langganan
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" @click="form.package_id = ''"
                                        class="rounded-md border px-3 py-2 text-left transition-colors"
                                        :class="form.package_id === '' ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-300 bg-white hover:bg-gray-50'">
                                        <p class="text-sm font-medium" :class="form.package_id === '' ? 'text-blue-700' : 'text-gray-900'">— Tanpa Paket —</p>
                                    </button>
                                    <button v-for="p in packages" :key="p.id" type="button" @click="form.package_id = p.id"
                                        class="rounded-md border px-3 py-2 text-left transition-colors"
                                        :class="form.package_id === p.id ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-300 bg-white hover:bg-gray-50'">
                                        <p class="text-sm font-medium truncate" :class="form.package_id === p.id ? 'text-blue-700' : 'text-gray-900'">{{ p.name }}</p>
                                        <p class="text-xs" :class="form.package_id === p.id ? 'text-blue-600' : 'text-gray-500'">
                                            {{ formatRupiah(p.price) }}<span v-if="p.speed"> · {{ p.speed }}</span>
                                        </p>
                                    </button>
                                </div>
                                <p v-if="formErrors.package_id" class="text-xs text-red-600">{{ formErrors.package_id }}</p>
                            </div>

                            <div class="flex justify-end gap-2 pt-2">
                                <button type="button" @click="showModal = false"
                                    class="inline-flex h-9 items-center gap-1.5 rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    Batal
                                </button>
                                <button type="submit" :disabled="submitting"
                                    class="inline-flex h-9 items-center gap-1.5 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors disabled:opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                                    {{ submitting ? 'Menyimpan...' : editing ? 'Simpan Perubahan' : 'Simpan' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </Transition>
        </Teleport>

    </AdminLayout>
</template>
