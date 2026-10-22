<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import SidebarProvider from '../../../components/ui/sidebar/SidebarProvider.vue';
import SidebarInset from '../../../components/ui/sidebar/SidebarInset.vue';
import SidebarTrigger from '../../../components/ui/sidebar/SidebarTrigger.vue';
import Separator from '../../../components/ui/separator/Separator.vue';
import AppSidebar from '../../../components/AppSidebar.vue';

type Payment = {
    id: number;
    customer_id: number | null;
    customer_name: string;
    billing_month: string | null;
    amount: string;
    payment_method: string;
    reference: string | null;
    status: 'pending' | 'confirmed' | 'cancelled';
    notes: string | null;
    created_at: string;
};

type Customer = {
    id: number;
    name: string;
    phone: string | null;
    address: string | null;
    qr_code: string;
    package: { id: number; name: string; price: string; speed: string | null } | null;
};

const props = defineProps<{
    admin: { name: string; email: string; role: string };
    customer: Customer;
    payments: Payment[];
}>();

// Add bill modal
const showBillModal = ref(false);
const billForm = ref({
    billing_month: '',
    amount: props.customer.package?.price || '',
    notes: '',
});
const billErrors = ref<Record<string, string>>({});
const submittingBill = ref(false);

// Status update
const updatingStatus = ref<number | null>(null);

const formatRupiah = (val: string | number) => {
    const num = typeof val === 'string' ? parseFloat(val) : val;
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(num);
};

const formatMonth = (val: string | null) => {
    if (!val) return '-';
    const [y, m] = val.split('-');
    const months = [
        'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
        'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des',
    ];
    return `${months[parseInt(m) - 1]} ${y}`;
};

const statusLabel: Record<string, string> = {
    pending: 'Menunggu',
    confirmed: 'Dikonfirmasi',
    cancelled: 'Dibatalkan',
};

const statusClass: Record<string, string> = {
    pending: 'bg-yellow-50 text-yellow-700 ring-yellow-600/20',
    confirmed: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
    cancelled: 'bg-red-50 text-red-700 ring-red-600/20',
};

const totalBills = computed(() =>
    props.payments.reduce((sum, p) => sum + parseFloat(p.amount), 0),
);

const totalConfirmed = computed(() =>
    props.payments
        .filter((p) => p.status === 'confirmed')
        .reduce((sum, p) => sum + parseFloat(p.amount), 0),
);

const totalPending = computed(() =>
    props.payments
        .filter((p) => p.status === 'pending')
        .reduce((sum, p) => sum + parseFloat(p.amount), 0),
);

const currentMonth = new Date().toISOString().slice(0, 7);

const submitBill = () => {
    billErrors.value = {};
    submittingBill.value = true;

    router.post(`/admin/payments/bill/${props.customer.id}`, billForm.value, {
        preserveScroll: true,
        onSuccess: () => {
            showBillModal.value = false;
            billForm.value = { billing_month: '', amount: '', notes: '' };
        },
        onError: (errors) => {
            billErrors.value = errors as Record<string, string>;
        },
        onFinish: () => {
            submittingBill.value = false;
        },
    });
};

const updateStatus = (payment: Payment, status: string) => {
    updatingStatus.value = payment.id;
    router.patch(
        `/admin/payments/${payment.id}`,
        { status },
        {
            preserveScroll: true,
            onFinish: () => {
                updatingStatus.value = null;
            },
        },
    );
};

const goBack = () => {
    router.visit('/admin/payments');
};
</script>

<template>
    <Head :title="`Tagihan - ${customer.name}`" />

    <SidebarProvider>
        <AppSidebar :admin="admin" />
        <SidebarInset>
            <!-- Header -->
            <header class="flex h-12 shrink-0 items-center gap-2 border-b bg-background px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <span class="text-sm font-medium">Tagihan Pelanggan</span>
            </header>

            <!-- Konten -->
            <div class="@container/main flex flex-1 flex-col gap-4">
                <div class="flex flex-1 flex-col gap-4 px-4 py-4 md:gap-6 md:py-6">
                    <!-- Back + Title -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <button
                                @click="goBack"
                                class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-input bg-background text-gray-600 shadow-sm hover:bg-gray-50 transition-colors"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
                            </button>
                            <div>
                                <h1 class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl">
                                    {{ customer.name }}
                                </h1>
                                <p class="mt-1 text-muted-foreground">
                                    QR: <span class="font-mono text-sm font-medium text-blue-600">{{ customer.qr_code }}</span>
                                    <span v-if="customer.package" class="ml-2">
                                        · <span class="inline-flex items-center rounded-md bg-blue-50 px-1.5 py-0.5 text-xs font-medium text-blue-700">{{ customer.package.name }}</span>
                                    </span>
                                    <span v-if="customer.address" class="ml-2">· {{ customer.address }}</span>
                                </p>
                            </div>
                        </div>
                        <button
                            @click="showBillModal = true"
                            class="inline-flex h-9 items-center gap-1.5 rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Tambah Tagihan
                        </button>
                    </div>

                    <!-- Summary Cards -->
                    <div class="grid grid-cols-4 gap-2 md:gap-4">
                        <div class="rounded-xl border bg-card p-3 md:p-4 shadow-sm">
                            <p class="text-[10px] md:text-xs font-medium text-muted-foreground uppercase">Total Tagihan</p>
                            <p class="mt-1 text-base md:text-xl font-bold text-foreground">{{ formatRupiah(totalBills) }}</p>
                        </div>
                        <div class="rounded-xl border bg-card p-3 md:p-4 shadow-sm">
                            <p class="text-[10px] md:text-xs font-medium text-muted-foreground uppercase">Dibayar</p>
                            <p class="mt-1 text-base md:text-xl font-bold text-emerald-600">{{ formatRupiah(totalConfirmed) }}</p>
                        </div>
                        <div class="rounded-xl border bg-card p-3 md:p-4 shadow-sm">
                            <p class="text-[10px] md:text-xs font-medium text-muted-foreground uppercase">Belum Bayar</p>
                            <p class="mt-1 text-base md:text-xl font-bold text-yellow-600">{{ formatRupiah(totalPending) }}</p>
                        </div>
                        <div class="rounded-xl border bg-card p-3 md:p-4 shadow-sm">
                            <p class="text-[10px] md:text-xs font-medium text-muted-foreground uppercase">Jumlah Tagihan</p>
                            <p class="mt-1 text-base md:text-xl font-bold text-blue-600">{{ payments.length }} bulan</p>
                        </div>
                    </div>

                    <!-- Bills Table -->
                    <div class="rounded-xl border bg-card shadow-sm overflow-hidden">
                        <div class="px-4 py-3 border-b bg-muted/50">
                            <h2 class="text-sm font-semibold">Riwayat Tagihan Bulanan</h2>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="border-b bg-muted/50">
                                    <tr>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground pl-4">#</th>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground">Bulan</th>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground">Jumlah</th>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground">Metode</th>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground">Status</th>
                                        <th class="h-10 px-3 text-left font-medium text-muted-foreground">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y">
                                    <template v-if="payments.length === 0">
                                        <tr>
                                            <td colspan="6" class="py-12 text-center text-sm text-muted-foreground">
                                                Belum ada tagihan untuk pelanggan ini.
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr
                                            v-for="(payment, i) in payments"
                                            :key="payment.id"
                                            class="hover:bg-muted/50 transition-colors"
                                            :class="payment.billing_month === currentMonth ? 'bg-blue-50/50' : ''"
                                        >
                                            <td class="p-3 pl-4 text-muted-foreground">{{ i + 1 }}</td>
                                            <td class="p-3 font-medium">
                                                {{ formatMonth(payment.billing_month) }}
                                                <span
                                                    v-if="payment.billing_month === currentMonth"
                                                    class="ml-1.5 inline-flex items-center rounded-md bg-blue-100 px-1.5 py-0.5 text-[10px] font-medium text-blue-700"
                                                >Bulan Ini</span>
                                            </td>
                                            <td class="p-3 font-semibold text-blue-600">{{ formatRupiah(payment.amount) }}</td>
                                            <td class="p-3">
                                                <span
                                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium"
                                                    :class="payment.payment_method === 'qrcode' ? 'bg-purple-50 text-purple-700' : 'bg-gray-100 text-gray-700'"
                                                >
                                                    {{ payment.payment_method === 'qrcode' ? 'QR Code' : 'Manual' }}
                                                </span>
                                            </td>
                                            <td class="p-3">
                                                <span
                                                    class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                                    :class="statusClass[payment.status]"
                                                >
                                                    {{ statusLabel[payment.status] }}
                                                </span>
                                            </td>
                                            <td class="p-3">
                                                <div class="flex items-center gap-1">
                                                    <button
                                                        v-if="payment.status !== 'confirmed'"
                                                        @click="updateStatus(payment, 'confirmed')"
                                                        :disabled="updatingStatus === payment.id"
                                                        class="inline-flex h-7 items-center rounded-md bg-emerald-600 px-2.5 text-xs font-medium text-white hover:bg-emerald-700 transition-colors disabled:opacity-50"
                                                    >
                                                        {{ updatingStatus === payment.id ? '...' : 'Bayar' }}
                                                    </button>
                                                    <button
                                                        v-if="payment.status === 'pending'"
                                                        @click="updateStatus(payment, 'cancelled')"
                                                        :disabled="updatingStatus === payment.id"
                                                        class="inline-flex h-7 items-center rounded-md border border-input bg-background px-2.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-50"
                                                    >
                                                        Batal
                                                    </button>
                                                    <button
                                                        v-if="payment.status === 'cancelled'"
                                                        @click="updateStatus(payment, 'pending')"
                                                        :disabled="updatingStatus === payment.id"
                                                        class="inline-flex h-7 items-center rounded-md border border-input bg-background px-2.5 text-xs font-medium text-gray-600 hover:bg-gray-50 transition-colors disabled:opacity-50"
                                                    >
                                                        Aktifkan
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Tambah Tagihan -->
            <Teleport to="body">
                <Transition
                    enter-active-class="transition-opacity duration-200"
                    leave-active-class="transition-opacity duration-200"
                    enter-from-class="opacity-0"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="showBillModal"
                        class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4"
                        @click.self="showBillModal = false"
                    >
                        <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                            <div class="flex items-center justify-between p-5 border-b">
                                <h3 class="text-lg font-semibold text-gray-900">Tambah Tagihan</h3>
                                <button
                                    @click="showBillModal = false"
                                    class="rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>

                            <form @submit.prevent="submitBill" class="p-5 space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Bulan Tagihan</label>
                                    <input
                                        type="month"
                                        v-model="billForm.billing_month"
                                        :min="'2024-01'"
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500"
                                    />
                                    <p v-if="billErrors.billing_month" class="text-xs text-red-600">{{ billErrors.billing_month }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Jumlah (Rp)</label>
                                    <input
                                        type="number"
                                        v-model="billForm.amount"
                                        placeholder="0"
                                        min="0"
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500"
                                    />
                                    <p v-if="billErrors.amount" class="text-xs text-red-600">{{ billErrors.amount }}</p>
                                </div>

                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Catatan (opsional)</label>
                                    <textarea
                                        v-model="billForm.notes"
                                        rows="2"
                                        placeholder="Contoh: Biaya internet bulanan..."
                                        class="flex w-full rounded-md border border-input bg-white px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500"
                                    ></textarea>
                                </div>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button
                                        type="button"
                                        @click="showBillModal = false"
                                        class="inline-flex h-9 items-center rounded-md border border-input bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors"
                                    >
                                        Batal
                                    </button>
                                    <button
                                        type="submit"
                                        :disabled="submittingBill"
                                        class="inline-flex h-9 items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors disabled:opacity-50"
                                    >
                                        {{ submittingBill ? 'Menyimpan...' : 'Simpan Tagihan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>
            </Teleport>
        </SidebarInset>
    </SidebarProvider>
</template>
