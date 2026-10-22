<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarProvider from '../../../components/ui/sidebar/SidebarProvider.vue';
import SidebarInset from '../../../components/ui/sidebar/SidebarInset.vue';
import SidebarTrigger from '../../../components/ui/sidebar/SidebarTrigger.vue';
import Separator from '../../../components/ui/separator/Separator.vue';
import AppSidebar from '../../../components/AppSidebar.vue';

type Pkg = {
    id: number;
    name: string;
    speed: string | null;
    price: string;
    description: string | null;
    customers_count: number;
    created_at: string;
    updated_at: string | null;
};

const props = defineProps<{
    admin: { name: string; email: string; role: string };
    packages: {
        data: Pkg[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
    };
}>();

const showModal = ref(false);
const editing = ref<Pkg | null>(null);
const deleting = ref<Pkg | null>(null);
const detail = ref<Pkg | null>(null);
const perPage = ref(props.packages.per_page || 15);

const openDetail = (p: Pkg) => {
    detail.value = p;
};

const formatDateTime = (val: string | null | undefined) => {
    if (!val) return '-';
    return new Date(val).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

const form = ref({
    name: '',
    speed: '',
    price: '',
    description: '',
});
const formErrors = ref<Record<string, string>>({});
const submitting = ref(false);

const formatRupiah = (val: string | number) => {
    const num = typeof val === 'string' ? parseFloat(val) : val;
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);
};

const handlePerPage = () => {
    router.get('/admin/packages', { per_page: perPage.value }, { preserveState: true, replace: true });
};

const handleRefresh = () => {
    router.reload({ only: ['packages'] });
};

const openCreate = () => {
    editing.value = null;
    form.value = { name: '', speed: '', price: '', description: '' };
    formErrors.value = {};
    showModal.value = true;
};

const openEdit = (p: Pkg) => {
    editing.value = p;
    form.value = {
        name: p.name,
        speed: p.speed || '',
        price: p.price,
        description: p.description || '',
    };
    formErrors.value = {};
    showModal.value = true;
};

const submitForm = () => {
    formErrors.value = {};
    submitting.value = true;

    const options = {
        preserveScroll: true,
        onSuccess: () => { showModal.value = false; },
        onError: (errors: any) => { formErrors.value = errors; },
        onFinish: () => { submitting.value = false; },
    };

    if (editing.value) {
        router.patch(`/admin/packages/${editing.value.id}`, form.value, options);
    } else {
        router.post('/admin/packages', form.value, options);
    }
};

const confirmDelete = () => {
    if (!deleting.value) return;
    router.delete(`/admin/packages/${deleting.value.id}`, {
        preserveScroll: true,
        onSuccess: () => { deleting.value = null; },
    });
};
</script>

<template>
    <Head title="Paket Langganan" />

    <SidebarProvider>
        <AppSidebar :admin="admin" />
        <SidebarInset>
            <header class="flex h-10 shrink-0 items-center gap-2 border-b border-gray-300 bg-background px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <span class="text-sm font-medium">Data Master / Paket Langganan</span>
            </header>

            <div class="@container/main flex flex-1 flex-col">
                <div class="flex flex-1 flex-col gap-2 px-4 py-2">

                    <!-- Header Card -->
                    <div class="flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/></svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-900">Paket Langganan</h2>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button @click="handleRefresh" class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                            </button>
                            <button @click="openCreate" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-blue-600 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="flex flex-1 flex-col overflow-hidden rounded-xl border border-gray-300 bg-card shadow-sm">
                        <div class="flex-1 overflow-x-auto overflow-y-auto">
                            <table class="w-full text-sm border-collapse">
                                <thead class="sticky top-0 z-10 bg-gray-100 backdrop-blur-sm">
                                    <tr>
                                        <th class="h-9 px-3 text-left font-medium text-gray-700 border border-gray-300 pl-4">#</th>
                                        <th class="h-9 px-3 text-left font-medium text-gray-700 border border-gray-300">Nama Paket</th>
                                        <th class="h-9 px-3 text-left font-medium text-gray-700 border border-gray-300">Kecepatan</th>
                                        <th class="h-9 px-3 text-left font-medium text-gray-700 border border-gray-300">Harga / Bulan</th>
                                        <th class="h-9 px-3 text-left font-medium text-gray-700 border border-gray-300">Pelanggan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="packages.data.length === 0">
                                        <tr><td colspan="5" class="py-12 text-center text-sm text-muted-foreground border border-gray-300">Belum ada data paket.</td></tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="(p, i) in packages.data" :key="p.id"
                                            @click="openDetail(p)"
                                            class="hover:bg-muted/50 transition-colors cursor-pointer">
                                            <td class="p-3 pl-4 text-muted-foreground border border-gray-300">{{ (packages.current_page - 1) * perPage + i + 1 }}</td>
                                            <td class="p-3 border border-gray-300">
                                                <div class="font-medium">{{ p.name }}</div>
                                                <div v-if="p.description" class="text-xs text-muted-foreground line-clamp-1">{{ p.description }}</div>
                                            </td>
                                            <td class="p-3 border border-gray-300">
                                                <span v-if="p.speed" class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-700">{{ p.speed }}</span>
                                                <span v-else class="text-xs text-muted-foreground">-</span>
                                            </td>
                                            <td class="p-3 font-semibold text-blue-600 border border-gray-300">{{ formatRupiah(p.price) }}</td>
                                            <td class="p-3 border border-gray-300">
                                                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700">
                                                    {{ p.customers_count }} pelanggan
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-auto flex items-center justify-between border-t border-gray-300 bg-muted/20 px-4 py-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-muted-foreground">Tampilkan</span>
                                <select v-model="perPage" @change="handlePerPage"
                                    class="h-7 rounded-md border border-gray-300 bg-white px-2 text-xs shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500">
                                    <option :value="10">10</option>
                                    <option :value="15">15</option>
                                    <option :value="25">25</option>
                                    <option :value="50">50</option>
                                </select>
                                <span class="text-xs text-muted-foreground">dari {{ packages.total }} data</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="mr-2 text-xs text-muted-foreground">Hal. {{ packages.current_page }}/{{ packages.last_page }}</span>
                                <Link v-if="packages.prev_page_url" :href="packages.prev_page_url"
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                </Link>
                                <span v-else class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                </span>
                                <Link v-if="packages.next_page_url" :href="packages.next_page_url"
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </Link>
                                <span v-else class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal Add/Edit -->
            <Teleport to="body">
                <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                    <div v-if="showModal" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="showModal = false">
                        <div class="bg-white rounded-xl shadow-xl w-full max-w-md max-h-[90vh] overflow-y-auto">
                            <div class="flex items-center justify-between p-5 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">{{ editing ? 'Edit Paket' : 'Tambah Paket' }}</h3>
                                <button @click="showModal = false" class="rounded-md p-1 text-gray-400 hover:text-gray-600 hover:bg-gray-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                </button>
                            </div>
                            <form @submit.prevent="submitForm" class="p-5 space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Nama Paket *</label>
                                    <input type="text" v-model="form.name" placeholder="Contoh: Fiber 10 Mbps"
                                        class="flex h-9 w-full rounded-md border border-gray-300 bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                    <p v-if="formErrors.name" class="text-xs text-red-600">{{ formErrors.name }}</p>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium text-gray-900">Kecepatan</label>
                                        <input type="text" v-model="form.speed" placeholder="Contoh: 10 Mbps"
                                            class="flex h-9 w-full rounded-md border border-gray-300 bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                        <p v-if="formErrors.speed" class="text-xs text-red-600">{{ formErrors.speed }}</p>
                                    </div>
                                    <div class="space-y-1.5">
                                        <label class="text-sm font-medium text-gray-900">Harga / Bulan (Rp) *</label>
                                        <input type="number" v-model="form.price" placeholder="150000" min="0"
                                            class="flex h-9 w-full rounded-md border border-gray-300 bg-white px-3 py-1 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                                        <p v-if="formErrors.price" class="text-xs text-red-600">{{ formErrors.price }}</p>
                                    </div>
                                </div>
                                <div class="space-y-1.5">
                                    <label class="text-sm font-medium text-gray-900">Deskripsi</label>
                                    <textarea v-model="form.description" rows="3" placeholder="Deskripsi paket layanan..."
                                        class="flex w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500"></textarea>
                                    <p v-if="formErrors.description" class="text-xs text-red-600">{{ formErrors.description }}</p>
                                </div>

                                <div class="flex justify-end gap-2 pt-2">
                                    <button type="button" @click="showModal = false"
                                        class="inline-flex h-9 items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">Batal</button>
                                    <button type="submit" :disabled="submitting"
                                        class="inline-flex h-9 items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 transition-colors disabled:opacity-50">
                                        {{ submitting ? 'Menyimpan...' : editing ? 'Simpan Perubahan' : 'Simpan' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Detail Modal -->
            <Teleport to="body">
                <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                    <div v-if="detail" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="detail = null">
                        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Detail Paket</h3>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Nama</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm font-semibold text-gray-900">{{ detail.name }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Kecepatan</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm text-gray-900">{{ detail.speed || '-' }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Harga</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm font-semibold text-gray-900">{{ formatRupiah(detail.price) }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Deskripsi</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm text-gray-900">{{ detail.description || '-' }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Jumlah Pelanggan</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm text-gray-900">{{ detail.customers_count }}</p></div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-1">Dibuat</p>
                                        <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-xs text-gray-900">{{ formatDateTime(detail.created_at) }}</p></div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-1">Diperbarui</p>
                                        <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-xs text-gray-900">{{ formatDateTime(detail.updated_at) }}</p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 pt-4">
                                <button @click="detail = null"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Tutup</button>
                                <button @click="openEdit(detail); detail = null"
                                    class="px-4 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors">Edit</button>
                                <button @click="deleting = detail; detail = null"
                                    class="px-4 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors">Hapus</button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>

            <!-- Delete Confirm -->
            <Teleport to="body">
                <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                    <div v-if="deleting" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="deleting = null">
                        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm space-y-4">
                            <h3 class="text-lg font-semibold text-gray-900">Hapus Paket?</h3>
                            <p class="text-sm text-gray-500">
                                Paket <strong>{{ deleting.name }}</strong> akan dihapus. Pelanggan yang menggunakan paket ini akan kehilangan paket mereka.
                            </p>
                            <div class="flex justify-end gap-2">
                                <button @click="deleting = null"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Batal</button>
                                <button @click="confirmDelete"
                                    class="px-4 py-2 text-sm font-medium bg-red-600 text-white hover:bg-red-700 rounded-lg transition-colors">Hapus</button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>
        </SidebarInset>
    </SidebarProvider>
</template>