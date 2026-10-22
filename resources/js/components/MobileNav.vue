<template>
    <nav
        class="fixed bottom-0 left-0 right-0 z-50 flex h-16 items-center justify-around border-t border-gray-200 bg-white/95 backdrop-blur-sm md:hidden"
    >
        <!-- Dashboard -->
        <button
            @click="router.visit('/admin/dashboard')"
            :class="[
                'flex flex-col items-center gap-1 px-2 py-2 text-[10px] font-medium rounded-lg transition-colors min-w-[52px]',
                isActive('/admin/dashboard')
                    ? 'text-blue-600'
                    : 'text-gray-500 hover:text-gray-700',
            ]"
        >
            <span
                :class="[
                    'flex h-7 w-7 items-center justify-center rounded-full transition-colors',
                    isActive('/admin/dashboard') ? 'bg-blue-50' : '',
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
            </span>
            <span>Dashboard</span>
        </button>

        <!-- Pembayaran -->
        <button
            @click="router.visit('/admin/payments')"
            :class="[
                'flex flex-col items-center gap-1 px-2 py-2 text-[10px] font-medium rounded-lg transition-colors min-w-[52px]',
                isActive('/admin/payments')
                    ? 'text-blue-600'
                    : 'text-gray-500 hover:text-gray-700',
            ]"
        >
            <span
                :class="[
                    'flex h-7 w-7 items-center justify-center rounded-full transition-colors',
                    isActive('/admin/payments') ? 'bg-blue-50' : '',
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
            </span>
            <span>Pembayaran</span>
        </button>

        <!-- Pelanggan -->
        <button
            @click="router.visit('/admin/customers')"
            :class="[
                'flex flex-col items-center gap-1 px-2 py-2 text-[10px] font-medium rounded-lg transition-colors min-w-[52px]',
                isActive('/admin/customers')
                    ? 'text-blue-600'
                    : 'text-gray-500 hover:text-gray-700',
            ]"
        >
            <span
                :class="[
                    'flex h-7 w-7 items-center justify-center rounded-full transition-colors',
                    isActive('/admin/customers') ? 'bg-blue-50' : '',
                ]"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </span>
            <span>Pelanggan</span>
        </button>
    </nav>
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';

const page = usePage();

const isActive = (url: string) => {
    const current = page.url.split('?')[0];
    return current === url || current === url + '/';
};
</script>
