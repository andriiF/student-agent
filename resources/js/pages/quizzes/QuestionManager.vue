<script setup lang="ts">
import { Form, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    destroy as destroyQuestion,
    edit as editQuestion,
    store as storeQuestion,
} from '@/routes/questions';
import type { Question } from '@/types';

const props = defineProps<{ questions: Question[]; quizUuid: string }>();

const questionName = ref('');

const columns = [
    { key: 'name', label: 'Question' },
    { key: 'answers_count', label: 'Answers' },
];

const rows = computed(() =>
    props.questions.map((q) => ({
        uuid: q.uuid,
        name: q.name,
        answers_count: `${q.answers?.length ?? 0} answers`,
    })),
);

const deleteQuestion = (question: { uuid: string; name: string }) => {
    if (confirm(`Are you sure you want to delete "${question.name}"?`)) {
        router.delete(destroyQuestion(question.uuid));
    }
};
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-lg font-semibold">Questions</h2>

        <DataTable
            v-if="props.questions.length > 0"
            :columns="columns"
            :rows="rows"
            row-key="uuid"
        >
            <template #actions="{ row }">
                <a
                    :href="editQuestion(row.uuid).url"
                    class="text-indigo-600 hover:text-indigo-900"
                >
                    <Pencil class="h-4 w-4" />
                </a>
                <button
                    class="cursor-pointer text-red-600 hover:text-red-900"
                    @click="deleteQuestion(row)"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <div class="space-y-2 rounded-md border border-dashed p-4">
            <p class="text-sm font-medium text-muted-foreground">
                Add a new question to this quiz
            </p>
            <Form
                v-bind="storeQuestion()"
                :action="storeQuestion().url"
                class="flex items-center gap-2"
                v-slot="{ processing }"
            >
                <input type="hidden" name="quiz_id" :value="quizUuid" />
                <Input
                    name="name"
                    v-model="questionName"
                    class="flex-1"
                    placeholder="Question text"
                    required
                />
                <Button type="submit" size="sm" :disabled="processing">
                    <Plus class="h-4 w-4" />
                </Button>
            </Form>
        </div>
    </div>
</template>
