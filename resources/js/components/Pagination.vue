<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    currentPage: number;
    lastPage: number;
    total: number;
    count: number;
    baseUrl: string;
}>();

function pageUrl(page: number): string {
    return `${props.baseUrl}?page=${page}`;
}
</script>

<template>
    <div
        v-if="lastPage > 1"
        class="mt-4 flex items-center justify-between"
    >
        <div class="text-sm text-gray-700">
            Showing {{ count }} of {{ total }} results
        </div>
        <div class="flex gap-2">
            <Link
                v-if="currentPage > 1"
                :href="pageUrl(currentPage - 1)"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
            >
                &laquo;
            </Link>
            <Link
                v-if="currentPage < lastPage"
                :href="pageUrl(currentPage + 1)"
                class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 hover:bg-gray-50"
            >
                &raquo;
            </Link>
        </div>
    </div>
</template>
