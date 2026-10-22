<script setup lang="ts">
import SidebarProvider from '../ui/sidebar/SidebarProvider.vue';
import SidebarInset from '../ui/sidebar/SidebarInset.vue';
import SidebarTrigger from '../ui/sidebar/SidebarTrigger.vue';
import Separator from '../ui/separator/Separator.vue';
import AppSidebar from '../AppSidebar.vue';

defineProps<{
    title: string;
    admin: { name: string; email: string; role?: string };
}>();
</script>

<template>
    <SidebarProvider>
        <AppSidebar :admin="admin" />
        <SidebarInset>
            <header class="flex h-10 shrink-0 items-center gap-2 border-b border-gray-300 bg-background px-4">
                <SidebarTrigger />
                <Separator orientation="vertical" class="mr-2 h-4" />
                <span class="text-sm font-medium">{{ title }}</span>
            </header>

            <div class="@container/main flex flex-1 min-h-0 flex-col overflow-hidden">
                <slot />
            </div>
        </SidebarInset>
    </SidebarProvider>
</template>
