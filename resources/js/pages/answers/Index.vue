<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import DataTable from '@/components/DataTable.vue';
import Pagination from '@/components/Pagination.vue';
import { index, create, edit, destroy } from '@/routes/answers';
import type { Answer } from '@/types';

interface Props {
    answers: {
        data: Answer[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const columns = [
    { key: 'name', label: 'Answer' },
    { key: 'is_correct', label: 'Correct' },
    { key: 'is_active', label: 'Active' },
];

const deleteAnswer = (answer: Answer) => {
    if (confirm(`Are you sure you want to delete "${answer.name}"?`)) {
        router.delete(destroy(answer.uuid));
    }
};
</script>

<template>
    <Head title="Answers" />
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Answers</h1>
            <Link :href="create()" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Create Answer
            </Link>
        </div>

        <DataTable :columns="columns" :rows="props.answers.data" row-key="uuid">
            <template #actions="{ row }">
                <Link :href="edit(row.uuid)" class="text-indigo-600 hover:text-indigo-900">
                    <Pencil class="h-4 w-4" />
                </Link>
                <button @click="deleteAnswer(row)" class="cursor-pointer text-red-600 hover:text-red-900">
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <Pagination
            :current-page="props.answers.current_page"
            :last-page="props.answers.last_page"
            :total="props.answers.total"
            :count="props.answers.data.length"
            :base-url="index()"
        />
    </div>
</template>
