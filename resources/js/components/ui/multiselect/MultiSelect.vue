<script setup lang="ts">
import { onClickOutside } from '@vueuse/core';
import { computed, ref } from 'vue';
import { Check, ChevronsUpDown, X } from 'lucide-vue-next';
import { cn } from '@/lib/utils';

interface Option {
    value: string;
    label: string;
}

const props = defineProps<{
    options: Option[];
    modelValue: string[];
    placeholder?: string;
    class?: string;
}>();

const emit = defineEmits<{
    'update:modelValue': [value: string[]];
}>();

const open = ref(false);
const triggerRef = ref<HTMLElement | null>(null);

const selectedLabels = computed(() =>
    props.modelValue
        .map((v) => props.options.find((o) => o.value === v)?.label)
        .filter(Boolean)
        .join(', '),
);

function toggle(value: string) {
    const next = props.modelValue.includes(value)
        ? props.modelValue.filter((v) => v !== value)
        : [...props.modelValue, value];
    emit('update:modelValue', next);
}

function remove(value: string, e: MouseEvent) {
    e.stopPropagation();
    emit('update:modelValue', props.modelValue.filter((v) => v !== value));
}

onClickOutside(triggerRef, () => {
    open.value = false;
});
</script>

<template>
    <div
        ref="triggerRef"
        :class="cn('relative', props.class)"
    >
        <button
            type="button"
            class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus:ring-ring flex min-h-9 w-full flex-wrap items-center gap-1 rounded-md border px-3 py-1.5 text-sm focus:ring-2 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-50"
            @click="open = !open"
        >
            <span
                v-if="modelValue.length === 0"
                class="text-muted-foreground"
                >{{ placeholder ?? 'Select...' }}</span
            >
            <span
                v-for="val in modelValue"
                :key="val"
                class="bg-secondary text-secondary-foreground flex items-center gap-1 rounded px-1.5 py-0.5 text-xs"
            >
                {{ options.find((o) => o.value === val)?.label }}
                <X class="h-3 w-3 cursor-pointer" @click="remove(val, $event)" />
            </span>
            <ChevronsUpDown class="text-muted-foreground ml-auto h-4 w-4 shrink-0" />
        </button>

        <div
            v-if="open"
            class="bg-popover text-popover-foreground absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-md border shadow-md"
        >
            <div
                v-for="option in options"
                :key="option.value"
                class="hover:bg-accent flex cursor-pointer items-center gap-2 px-3 py-2 text-sm"
                @click="toggle(option.value)"
            >
                <Check
                    class="h-4 w-4"
                    :class="modelValue.includes(option.value) ? 'opacity-100' : 'opacity-0'"
                />
                {{ option.label }}
            </div>
        </div>
    </div>
</template>
