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

type Stat = {
    label: string;
    value: string;
    hint: string;
    tone: 'blue' | 'cyan' | 'amber' | 'emerald';
};

type Activity = {
    time: string;
    text: string;
};

defineProps<{
    admin: Admin;
    stats: Stat[];
    activities: Activity[];
}>();

const toneClasses: Record<Stat['tone'], string> = {
    blue: 'bg-blue-50 text-blue-600',
    cyan: 'bg-cyan-50 text-cyan-600',
    amber: 'bg-amber-50 text-amber-600',
    emerald: 'bg-emerald-50 text-emerald-600',
};
</script>

<template>
    <Head title="Dashboard" />

    <SidebarProvider>
        <AppSidebar :admin="admin" />
        <SidebarInset>
            <!-- Header -->
            <header
                class="flex h-12 shrink-0 items-center gap-2 border-b bg-background px-4"
            >
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <div class="flex items-center gap-2">
                    <span class="text-sm font-medium">Dashboard</span>
                </div>
            </header>

            <!-- Konten -->
            <div class="@container/main flex flex-1 flex-col gap-4">
                <div
                    class="flex flex-1 flex-col gap-4 px-4 py-4 md:gap-6 md:py-6"
                >
                    <!-- Sapaan -->
                    <div>
                        <h1
                            class="text-2xl font-bold tracking-tight text-foreground sm:text-3xl"
                        >
                            Selamat datang, {{ admin.name }} 👋
                        </h1>
                        <p class="mt-1 text-muted-foreground">
                            Ringkasan operasional DFiber hari ini — Piyungan,
                            Bantul, D.I. Yogyakarta.
                        </p>
                    </div>

                    <!-- Kartu Statistik (SectionCards) -->
                    <div class="grid grid-cols-4 gap-1.5 sm:gap-4">
                        <div
                            v-for="stat in stats"
                            :key="stat.label"
                            class="rounded-lg sm:rounded-xl border bg-card p-2 sm:p-6 text-card-foreground shadow-sm"
                        >
                            <div
                                class="flex items-center justify-between gap-1 sm:gap-3"
                            >
                                <p
                                    class="text-[10px] sm:text-sm font-medium text-muted-foreground leading-tight"
                                >
                                    {{ stat.label }}
                                </p>
                                <span
                                    class="hidden sm:grid h-9 w-9 shrink-0 place-items-center rounded-lg"
                                    :class="toneClasses[stat.tone]"
                                >
                                    <span
                                        class="h-2.5 w-2.5 rounded-full bg-current"
                                    ></span>
                                </span>
                            </div>
                            <p
                                class="mt-1.5 sm:mt-3 text-base sm:text-3xl font-bold tracking-tight text-foreground"
                            >
                                {{ stat.value }}
                            </p>
                            <p class="mt-0.5 sm:mt-1 text-[9px] sm:text-xs text-muted-foreground leading-tight line-clamp-2">
                                {{ stat.hint }}
                            </p>
                        </div>
                    </div>

                    <!-- Aktivitas Terbaru -->
                    <div class="grid gap-4 lg:grid-cols-3">
                        <div
                            class="rounded-xl border bg-card text-card-foreground shadow-sm lg:col-span-2"
                        >
                            <div class="p-6 pt-6">
                                <h3 class="text-lg font-semibold leading-none tracking-tight">
                                    Aktivitas Terbaru
                                </h3>
                                <p class="text-sm text-muted-foreground mt-1.5">
                                    Update operasional terakhir dari lapangan.
                                </p>
                            </div>
                            <div class="p-6 pt-0">
                                <ul class="divide-y divide-border">
                                    <li
                                        v-for="(item, index) in activities"
                                        :key="index"
                                        class="flex items-start gap-3 py-3 first:pt-0 last:pb-0"
                                    >
                                        <span
                                            class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-primary"
                                        ></span>
                                        <div>
                                            <p class="text-sm text-foreground">
                                                {{ item.text }}
                                            </p>
                                            <p
                                                class="text-xs text-muted-foreground"
                                            >
                                                {{ item.time }}
                                            </p>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
