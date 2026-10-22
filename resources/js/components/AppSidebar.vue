<template>
    <Sidebar>
        <!-- Header -->
        <SidebarHeader>
            <div class="flex items-center gap-2 px-2 py-1.5">
                <div
                    class="flex h-7 w-7 items-center justify-center rounded-lg bg-blue-600 text-white shadow-sm"
                >
                    <span class="text-xs font-bold">D</span>
                </div>
                <span class="text-sm font-bold text-sidebar-foreground"
                    >DFiber</span
                >
            </div>
        </SidebarHeader>

        <!-- Content -->
        <SidebarContent>
            <SidebarGroup>
                <SidebarGroupLabel>Menu Utama</SidebarGroupLabel>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            :active="isActive('/admin/dashboard')"
                            @click="navigate('/admin/dashboard')"
                            :class="
                                isActive('/admin/dashboard')
                                    ? 'bg-blue-50 text-blue-700 font-medium'
                                    : 'text-gray-700 hover:bg-gray-50'
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                            <span>Dashboard</span>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            :active="isActive('/admin/payments')"
                            @click="navigate('/admin/payments')"
                            :class="
                                isActive('/admin/payments')
                                    ? 'bg-blue-50 text-blue-700 font-medium'
                                    : 'text-gray-700 hover:bg-gray-50'
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/></svg>
                            <span>Pembayaran</span>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                    <SidebarMenuItem>
                        <SidebarMenuButton
                            :active="isActive('/admin/customers')"
                            @click="navigate('/admin/customers')"
                            :class="
                                isActive('/admin/customers')
                                    ? 'bg-blue-50 text-blue-700 font-medium'
                                    : 'text-gray-700 hover:bg-gray-50'
                            "
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            <span>Pelanggan</span>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarGroup>
        </SidebarContent>

        <!-- Footer -->
        <SidebarFooter>
            <div class="border-t border-sidebar-border pt-2">
                <SidebarGroup>
                    <SidebarGroupLabel>Pengaturan</SidebarGroupLabel>
                    <SidebarMenu>
                        <SidebarMenuItem>
                            <SidebarMenuButton
                                :active="isActive('/admin/settings')"
                                @click="navigate('/admin/settings')"
                                class="text-gray-600 hover:bg-gray-50"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="18"
                                    height="18"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                >
                                    <path
                                        d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"
                                    />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                <span>Pengaturan</span>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    </SidebarMenu>
                </SidebarGroup>

                <!-- User Info -->
                <div
                    class="flex items-center gap-2.5 px-2 py-2 rounded-lg hover:bg-sidebar-accent transition-colors cursor-pointer"
                    @click="showLogout = true"
                >
                    <div
                        class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-blue-600 text-xs font-bold text-white"
                    >
                        {{ adminInitials }}
                    </div>
                    <div class="flex flex-col overflow-hidden">
                        <span
                            class="text-sm font-medium text-sidebar-foreground truncate"
                            >{{ admin?.name }}</span
                        >
                        <span
                            class="text-xs text-sidebar-foreground/60 truncate"
                            >{{ admin?.email }}</span
                        >
                    </div>
                </div>
            </div>
        </SidebarFooter>

        <!-- Logout Dialog -->
        <Teleport to="body">
            <div
                v-if="showLogout"
                class="fixed inset-0 z-[999] flex items-center justify-center bg-black/50"
                @click.self="showLogout = false"
            >
                <div
                    class="bg-white rounded-xl shadow-xl p-6 w-full max-w-sm mx-4 space-y-4"
                >
                    <h3 class="text-lg font-semibold text-gray-900">
                        Keluar dari akun?
                    </h3>
                    <p class="text-sm text-gray-500">
                        Anda akan keluar dari panel admin DFiber.
                    </p>
                    <div class="flex justify-end gap-2">
                        <button
                            @click="showLogout = false"
                            class="px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            @click="logout"
                            class="px-4 py-2 text-sm font-medium bg-blue-600 text-white hover:bg-blue-700 rounded-lg transition-colors"
                        >
                            Keluar
                        </button>
                    </div>
                </div>
            </div>
        </Teleport>
    </Sidebar>

    <!-- Mobile Bottom Nav -->
    <MobileNav />
</template>

<script setup lang="ts">
import { router, usePage } from '@inertiajs/vue3';
import { computed, ref, inject } from 'vue';
import Sidebar from './ui/sidebar/Sidebar.vue';
import SidebarHeader from './ui/sidebar/SidebarHeader.vue';
import SidebarContent from './ui/sidebar/SidebarContent.vue';
import SidebarFooter from './ui/sidebar/SidebarFooter.vue';
import SidebarGroup from './ui/sidebar/SidebarGroup.vue';
import SidebarGroupLabel from './ui/sidebar/SidebarGroupLabel.vue';
import SidebarMenu from './ui/sidebar/SidebarMenu.vue';
import SidebarMenuItem from './ui/sidebar/SidebarMenuItem.vue';
import SidebarMenuButton from './ui/sidebar/SidebarMenuButton.vue';
import MobileNav from './MobileNav.vue';

defineProps<{
    admin?: { name: string; email: string };
}>();

const page = usePage();
const showLogout = ref(false);
const isMobile = inject('is-mobile', ref(false));
const collapsed = inject('sidebar-collapsed', ref(true));
const toggle = inject('sidebar-toggle', () => {});

const navigate = (url: string) => {
    router.visit(url);
    // Auto-close sidebar on mobile after navigation
    if (isMobile.value) {
        collapsed.value = true;
    }
};

const adminInitials = computed(() => {
    const name = (page.props as any).admin?.name || 'A';
    return name
        .split(' ')
        .map((n: string) => n[0])
        .join('')
        .toUpperCase()
        .slice(0, 2);
});

const isActive = (url: string) => {
    const current = page.url.split('?')[0];
    return current === url || current === url + '/';
};

const logout = () => {
    showLogout.value = false;
    router.post('/logout', {}, { preserveScroll: true });
};
</script>