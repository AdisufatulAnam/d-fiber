<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import SidebarProvider from '../../components/ui/sidebar/SidebarProvider.vue';
import SidebarInset from '../../components/ui/sidebar/SidebarInset.vue';
import SidebarTrigger from '../../components/ui/sidebar/SidebarTrigger.vue';
import Separator from '../../components/ui/separator/Separator.vue';
import AppSidebar from '../../components/AppSidebar.vue';

type Admin = {
    id: number;
    name: string;
    email: string;
    role: string;
};

defineProps<{ admin: Admin }>();
</script>

<template>
    <Head title="Pengaturan" />

    <SidebarProvider>
        <AppSidebar :admin="admin" />
        <SidebarInset>
            <header
                class="flex h-12 shrink-0 items-center gap-2 border-b bg-background px-4"
            >
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <span class="text-sm font-medium">Pengaturan</span>
            </header>

            <div class="@container/main flex flex-1 flex-col gap-4">
                <div
                    class="flex flex-1 flex-col gap-4 px-4 py-4 md:gap-6 md:py-6"
                >
                    <div>
                        <h1
                            class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl"
                        >
                            Pengaturan Akun
                        </h1>
                        <p class="mt-1 text-muted-foreground">
                            Kelola informasi profil dan preferensi akun Anda.
                        </p>
                    </div>

                    <!-- Profil Card -->
                    <div class="rounded-xl border bg-card shadow-sm">
                        <div class="p-6 border-b">
                            <h3 class="text-base font-semibold">Profil</h3>
                            <p class="text-sm text-muted-foreground mt-1">
                                Informasi dasar akun Anda.
                            </p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-medium leading-none"
                                        >Nama</label
                                    >
                                    <div
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm"
                                    >
                                        {{ admin.name }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-medium leading-none"
                                        >Email</label
                                    >
                                    <div
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm"
                                    >
                                        {{ admin.email }}
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label
                                        class="text-sm font-medium leading-none"
                                        >Role</label
                                    >
                                    <div
                                        class="flex h-9 w-full rounded-md border border-input bg-white px-3 py-1 text-sm shadow-sm"
                                    >
                                        <span
                                            class="inline-flex items-center rounded-md bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20"
                                        >
                                            {{ admin.role }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Preferensi Card -->
                    <div class="rounded-xl border bg-card shadow-sm">
                        <div class="p-6 border-b">
                            <h3 class="text-base font-semibold">Preferensi</h3>
                            <p class="text-sm text-muted-foreground mt-1">
                                Pengaturan tampilan dan notifikasi.
                            </p>
                        </div>
                        <div class="p-6 space-y-4">
                            <div
                                class="flex items-center justify-between py-2"
                            >
                                <div>
                                    <p class="text-sm font-medium">
                                        Notifikasi Email
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Terima notifikasi via email.
                                    </p>
                                </div>
                                <div
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-blue-600 transition-colors"
                                >
                                    <span
                                        class="pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform translate-x-5"
                                    ></span>
                                </div>
                            </div>
                            <div
                                class="flex items-center justify-between py-2 border-t"
                            >
                                <div>
                                    <p class="text-sm font-medium">
                                        Mode Gelap
                                    </p>
                                    <p class="text-xs text-muted-foreground">
                                        Gunakan tema gelap.
                                    </p>
                                </div>
                                <div
                                    class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-gray-200 transition-colors"
                                >
                                    <span
                                        class="pointer-events-none block h-5 w-5 rounded-full bg-white shadow-lg ring-0 transition-transform translate-x-0"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
