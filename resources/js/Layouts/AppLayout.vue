<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import UserMenu from '../Components/UserMenu.vue';
import Sidebar from '../Components/Sidebar.vue';
import Header from '../Components/Header.vue';

const { auth } = usePage().props;
</script>

<template>
    <Head title="Dashboard" />
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
        <div class="lg:pl-64">
            <!-- Header -->
            <Header />

            <!-- Page Content -->
            <main class="py-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-7xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>

        <!-- User Dropdown -->
        <UserMenu v-if="auth" :user="auth.user" />
    </div>
</template>