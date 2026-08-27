<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { Pencil, Search, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import Pagination from '@/components/Pagination.vue';
import { Input } from '@/components/ui/input';
import { index, create, edit, destroy } from '@/routes/topics';
import type { Topic } from '@/types';

interface Props {
    topics: {
        data: Topic[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
    filters: {
        search?: string;
    };
}

const props = defineProps<Props>();

const search = ref(props.filters.search ?? '');

watchDebounced(
    search,
    (value) => {
        router.get(
            index.url({
                query: {
                    search: value || undefined,
                },
            }),
            {},
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            },
        );
    },
    { debounce: 300 },
);

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'frontend_user.email', label: 'User Email' },
    { key: 'front_user_id', label: 'User ID' },
];

const deleteTopic = (topic: Topic) => {
    if (confirm(`Are you sure you want to delete "${topic.name}"?`)) {
        router.delete(destroy(topic.uuid));
    }
};
</script>

<template>
    <Head title="Topics" />
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Topics</h1>
            <Link
                :href="create()"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Create Topic
            </Link>
        </div>

        <div class="relative mb-4 max-w-sm">
            <Search
                class="pointer-events-none absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
            />
            <Input
                v-model="search"
                type="search"
                placeholder="Search topics..."
                class="pl-9"
            />
        </div>

        <DataTable :columns="columns" :rows="props.topics.data" row-key="uuid">
            <template #actions="{ row }">
                <Link
                    :href="edit(row.uuid)"
                    class="text-indigo-600 hover:text-indigo-900"
                >
                    <Pencil class="h-4 w-4" />
                </Link>
                <button
                    @click="deleteTopic(row)"
                    class="cursor-pointer text-red-600 hover:text-red-900"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <Pagination
            :current-page="props.topics.current_page"
            :last-page="props.topics.last_page"
            :total="props.topics.total"
            :count="props.topics.data.length"
            :base-url="
                index.url({
                    query: { search: props.filters.search || undefined },
                })
            "
        />
    </div>
</template>
