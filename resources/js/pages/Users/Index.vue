<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SidebarProvider from '../../components/ui/sidebar/SidebarProvider.vue';
import SidebarInset from '../../components/ui/sidebar/SidebarInset.vue';
import SidebarTrigger from '../../components/ui/sidebar/SidebarTrigger.vue';
import Separator from '../../components/ui/separator/Separator.vue';
import AppSidebar from '../../components/AppSidebar.vue';

type User = {
    id: number;
    name: string;
    email: string;
    role: string;
    created_at: string;
    updated_at: string | null;
};

const props = defineProps<{
    users: {
        data: User[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        prev_page_url: string | null;
        next_page_url: string | null;
        links: any;
    };
    filters: {
        search?: string;
    };
}>();

const search = ref(props.filters.search || '');
const detail = ref<User | null>(null);
const showFilter = ref(false);
const perPage = ref(props.users.per_page || 15);

const openDetail = (u: User) => {
    detail.value = u;
};

const handleSearch = () => {
    router.get('/admin/users', { search: search.value, per_page: perPage.value }, { preserveState: true, replace: true });
};

const handleRefresh = () => {
    router.reload({ only: ['users'] });
};

const handlePerPage = () => {
    router.get('/admin/users', { search: search.value, per_page: perPage.value }, { preserveState: true, replace: true });
};
</script>

<template>
    <Head title="Users Management" />

    <SidebarProvider>
        <AppSidebar />
        <SidebarInset>
            <header class="flex h-10 shrink-0 items-center gap-2 border-b border-gray-300 bg-background px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <span class="text-sm font-medium">Pengguna</span>
            </header>

            <div class="@container/main flex flex-1 flex-col">
                <div class="flex flex-1 flex-col gap-2 px-4 py-2">

                    <!-- Header Card -->
                    <div class="flex items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
                        <div class="flex items-center gap-2">
                            <div class="flex h-7 w-7 items-center justify-center rounded-md bg-blue-100 text-blue-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            </div>
                            <h2 class="text-sm font-semibold text-gray-900">Pengguna</h2>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <button @click="showFilter = !showFilter" class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
                            </button>
                            <button @click="handleRefresh" class="inline-flex h-7 w-7 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Bar -->
                    <Transition enter-active-class="transition-all duration-200" leave-active-class="transition-all duration-200" enter-from-class="opacity-0 -translate-y-1" leave-to-class="opacity-0 -translate-y-1">
                        <div v-if="showFilter" class="flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 shadow-sm">
                            <input type="text" v-model="search" @keyup.enter="handleSearch" placeholder="Cari nama atau email..."
                                class="flex h-8 w-full max-w-sm rounded-md border border-gray-300 bg-white px-3 text-sm shadow-sm focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-blue-500" />
                            <button @click="handleSearch" title="Terapkan Filter"
                                class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white shadow-sm hover:bg-blue-700 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8"/><path d="M21 3v5h-5"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16"/><path d="M8 16H3v5"/></svg>
                            </button>
                            <button @click="showFilter = false" title="Tutup Filter"
                                class="ml-auto inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-300 bg-white text-gray-400 hover:bg-gray-50 hover:text-gray-600 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                            </button>
                        </div>
                    </Transition>

                    <!-- Table -->
                    <div class="flex flex-1 flex-col overflow-hidden rounded-xl border border-gray-300 bg-card shadow-sm">
                        <div class="flex-1 overflow-x-auto overflow-y-auto">
                            <table class="w-full caption-bottom text-sm border-collapse">
                                <thead class="sticky top-0 z-10 bg-gray-100 backdrop-blur-sm">
                                    <tr>
                                        <th class="h-9 px-2 text-left align-middle font-medium text-gray-700 border border-gray-300 pl-4">Name</th>
                                        <th class="h-9 px-2 text-left align-middle font-medium text-gray-700 border border-gray-300">Email</th>
                                        <th class="h-9 px-2 text-left align-middle font-medium text-gray-700 border border-gray-300">Role</th>
                                        <th class="h-9 px-2 text-left align-middle font-medium text-gray-700 border border-gray-300">Created At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <template v-if="users.data.length === 0">
                                        <tr>
                                            <td colspan="4" class="p-4 text-center text-sm text-muted-foreground py-12 border border-gray-300">
                                                No users found in database.
                                            </td>
                                        </tr>
                                    </template>
                                    <template v-else>
                                        <tr v-for="user in users.data" :key="user.id"
                                            @click="openDetail(user)"
                                            class="hover:bg-muted/50 transition-colors cursor-pointer">
                                            <td class="p-2 pl-4 align-middle font-medium border border-gray-300">{{ user.name }}</td>
                                            <td class="p-2 align-middle text-muted-foreground border border-gray-300">{{ user.email }}</td>
                                            <td class="p-2 align-middle border border-gray-300">
                                                <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset"
                                                    :class="user.role === 'admin' ? 'bg-blue-50 text-blue-700 ring-blue-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20'">
                                                    {{ user.role === 'admin' ? 'Admin' : 'User' }}
                                                </span>
                                            </td>
                                            <td class="p-2 align-middle text-muted-foreground border border-gray-300">
                                                {{ new Date(user.created_at).toLocaleDateString() }}
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
                                <span class="text-xs text-muted-foreground">dari {{ users.total }} data</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <span class="mr-2 text-xs text-muted-foreground">Hal. {{ users.current_page }}/{{ users.last_page }}</span>
                                <Link v-if="users.prev_page_url" :href="users.prev_page_url"
                                    class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                </Link>
                                <span v-else class="inline-flex h-7 w-7 items-center justify-center rounded-md border border-gray-200 bg-gray-50 text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
                                </span>
                                <Link v-if="users.next_page_url" :href="users.next_page_url"
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

            <!-- Detail Modal -->
            <Teleport to="body">
                <Transition enter-active-class="transition-opacity duration-200" leave-active-class="transition-opacity duration-200" enter-from-class="opacity-0" leave-to-class="opacity-0">
                    <div v-if="detail" class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50 p-4" @click.self="detail = null">
                        <div class="bg-white rounded-xl shadow-xl p-6 w-full max-w-md space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 text-blue-600 font-semibold">
                                    {{ detail.name.charAt(0).toUpperCase() }}
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900">Detail Pengguna</h3>
                            </div>
                            <div class="space-y-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Nama</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm font-semibold text-gray-900">{{ detail.name }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Email</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm text-gray-900">{{ detail.email }}</p></div>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500 mb-1">Role</p>
                                    <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-sm text-gray-900">{{ detail.role === 'admin' ? 'Admin' : 'User' }}</p></div>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-1">Dibuat</p>
                                        <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-xs text-gray-900">{{ new Date(detail.created_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) }}</p></div>
                                    </div>
                                    <div>
                                        <p class="text-xs font-medium text-gray-500 mb-1">Diperbarui</p>
                                        <div class="rounded-lg bg-gray-100 px-3 py-2.5"><p class="text-xs text-gray-900">{{ detail.updated_at ? new Date(detail.updated_at).toLocaleString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' }) : '-' }}</p></div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-4">
                                <button @click="detail = null"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">Tutup</button>
                            </div>
                        </div>
                    </div>
                </Transition>
            </Teleport>
        </SidebarInset>
    </SidebarProvider>
</template>