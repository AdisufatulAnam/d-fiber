<script setup lang="ts">
import type { ClassValue } from 'clsx';
import { computed } from 'vue';
import { cn } from '../../../lib/utils';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        class?: ClassValue;
        type?: string;
        placeholder?: string;
        id?: string;
        name?: string;
        autocomplete?: string;
        required?: boolean;
        disabled?: boolean;
    }>(),
    {
        modelValue: '',
        class: () => [],
        type: 'text',
        required: false,
        disabled: false,
    },
);

const emit = defineEmits<{ 'update:modelValue': [value: string] }>();

const classes = computed(() =>
    cn(
        'flex h-9 w-full rounded-md border border-input bg-background px-3 py-1 text-base shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50 md:text-sm',
        props.class,
    ),
);
</script>

<template>
    <input
        :type="type"
        :value="modelValue"
        :id="id"
        :name="name"
        :placeholder="placeholder"
        :autocomplete="autocomplete"
        :required="required"
        :disabled="disabled"
        :class="classes"
        @input="emit('update:modelValue', ($event.target as HTMLInputElement).value)"
    />
</template>
