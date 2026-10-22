<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AuthLayout from '../../Layouts/AuthLayout.vue';
import Button from '../../components/ui/button/Button.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '../../components/ui/card';
import Input from '../../components/ui/input/Input.vue';
import Label from '../../components/ui/label/Label.vue';

defineProps<{ status?: string }>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="Login Admin" />

    <AuthLayout>
        <Card>
            <CardHeader>
                <CardTitle>Masuk</CardTitle>
                <CardDescription>
                    Gunakan akun admin operasional Anda.
                </CardDescription>
            </CardHeader>

            <CardContent>
                <div
                    v-if="status"
                    class="mb-4 rounded-md bg-secondary px-3 py-2 text-sm text-secondary-foreground"
                >
                    {{ status }}
                </div>

                <form class="space-y-4" @submit.prevent="submit">
                    <div
                        v-if="form.errors.email && form.errors.password"
                        class="rounded-md bg-destructive/10 px-3 py-2 text-sm text-destructive"
                    >
                        Email atau password salah.
                    </div>

                    <div class="space-y-2">
                        <Label for="email">Email</Label>
                        <Input
                            id="email"
                            v-model="form.email"
                            type="email"
                            name="email"
                            autocomplete="username"
                            placeholder="admin@dfiber.id"
                            required
                            :class="form.errors.email && 'border-destructive'"
                        />
                        <p
                            v-if="form.errors.email"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input
                            id="password"
                            v-model="form.password"
                            type="password"
                            name="password"
                            autocomplete="current-password"
                            placeholder="••••••••"
                            required
                            :class="
                                form.errors.password && 'border-destructive'
                            "
                        />
                        <p
                            v-if="form.errors.password"
                            class="text-sm text-destructive"
                        >
                            {{ form.errors.password }}
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <input
                            id="remember"
                            v-model="form.remember"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 rounded border-input accent-blue-600"
                        />
                        <Label
                            for="remember"
                            class="font-normal text-muted-foreground"
                        >
                            Ingat saya
                        </Label>
                    </div>

                    <Button
                        type="submit"
                        class="w-full"
                        :disabled="form.processing"
                    >
                        <svg
                            v-if="form.processing"
                            class="h-4 w-4 animate-spin"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                        >
                            <circle cx="12" cy="12" r="10" class="opacity-25" />
                            <path
                                d="M12 2a10 10 0 0 1 10 10"
                                class="opacity-90"
                            />
                        </svg>
                        {{ form.processing ? 'Memproses…' : 'Masuk' }}
                    </Button>
                </form>
            </CardContent>
        </Card>
    </AuthLayout>
</template>
