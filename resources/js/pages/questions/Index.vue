<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Trash2 } from 'lucide-vue-next';
import DataTable from '@/components/DataTable.vue';
import Pagination from '@/components/Pagination.vue';
import { index, create, edit, destroy } from '@/routes/questions';
import type { Question } from '@/types';

interface Props {
    questions: {
        data: Question[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const columns = [
    { key: 'name', label: 'Name' },
    { key: 'quiz_id', label: 'Quiz ID' },
];

const deleteQuestion = (question: Question) => {
    if (confirm(`Are you sure you want to delete "${question.name}"?`)) {
        router.delete(destroy(question.uuid));
    }
};
</script>

<template>
    <Head title="Questions" />
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Questions</h1>
            <Link :href="create()" class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
                Create Question
            </Link>
        </div>

        <DataTable :columns="columns" :rows="props.questions.data" row-key="uuid">
            <template #actions="{ row }">
                <Link :href="edit(row.uuid)" class="text-indigo-600 hover:text-indigo-900">
                    <Pencil class="h-4 w-4" />
                </Link>
                <button @click="deleteQuestion(row)" class="cursor-pointer text-red-600 hover:text-red-900">
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <Pagination
            :current-page="props.questions.current_page"
            :last-page="props.questions.last_page"
            :total="props.questions.total"
            :count="props.questions.data.length"
            :base-url="index()"
        />
    </div>
</template>
