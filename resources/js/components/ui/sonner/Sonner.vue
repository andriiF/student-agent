<script setup lang="ts">
import { computed } from 'vue';
import { useAppearance } from '@/composables/useAppearance';
import { Toaster as SonnerPrimitive } from 'vue-sonner';
import 'vue-sonner/style.css';

const { appearance, resolvedAppearance } = useAppearance();

const isDark = computed(() => resolvedAppearance.value === 'dark');

const toastStyle = computed(() => ({
    '--normal-bg': 'var(--popover)',
    '--normal-text': 'var(--popover-foreground)',
    '--normal-border': 'var(--border)',
    '--success-bg': isDark.value ? 'hsl(142 60% 40%)' : 'hsl(142 76% 36%)',
    '--success-text': 'hsl(0 0% 98%)',
    '--success-border': isDark.value ? 'hsl(142 60% 32%)' : 'hsl(142 76% 30%)',
    '--error-bg': isDark.value ? 'hsl(0 72% 50%)' : 'var(--destructive)',
    '--error-text': 'hsl(0 0% 98%)',
    '--error-border': isDark.value ? 'hsl(0 72% 40%)' : 'hsl(0 84% 50%)',
}));
</script>

<template>
    <SonnerPrimitive
        :theme="appearance"
        rich-colors
        class="toaster group"
        position="bottom-right"
        :style="toastStyle"
    />
</template>
