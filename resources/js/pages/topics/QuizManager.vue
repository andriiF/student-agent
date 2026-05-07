<script setup lang="ts">
import { Form, router } from '@inertiajs/vue3';
import { Plus, Pencil, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import {
    store as storeQuiz,
    edit as editQuiz,
    destroy as destroyQuiz,
} from '@/routes/quizzes';
import type { Quiz } from '@/types';

const props = defineProps<{ quizzes: Quiz[]; topicUuid: string }>();

const quizName = ref('');

const columns = [
    { key: 'name', label: 'Quiz Name' },
    { key: 'questions_count', label: 'Questions' },
];

const rows = computed(() =>
    props.quizzes.map((quiz) => ({
        uuid: quiz.uuid,
        name: quiz.name,
        questions_count: `${quiz.questions?.length ?? 0} questions`,
    })),
);

const deleteQuiz = (quiz: Quiz) => {
    if (confirm(`Are you sure you want to delete ${quiz.name}?`)) {
        router.delete(destroyQuiz(quiz.uuid));
    }
};
</script>

<template>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold">Quizzes</h2>
        </div>

        <!-- Existing quizzes with their questions -->
        <DataTable
            :columns="columns"
            :rows="rows"
            row-key="uuid"
            v-if="props.quizzes.length > 0"
        >
            <template #actions="{ row }">
                <a
                    :href="editQuiz(row.uuid).url"
                    class="text-indigo-600 hover:text-indigo-900"
                >
                    <Pencil class="h-4 w-4"
                /></a>
                <button
                    @click="deleteQuiz(row)"
                    class="cursor-pointer text-red-600 hover:text-red-900"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <!-- Add quiz -->
        <div class="space-y-2 rounded-md border border-dashed p-4">
            <p class="text-sm font-medium text-muted-foreground">
                Add a new quiz to this topic
            </p>
            <Form
                v-bind="storeQuiz()"
                :action="storeQuiz().url"
                class="flex items-center gap-2"
                :onSuccess="
                    () => {
                        quizName.value = '';
                    }
                "
                v-slot="{ processing }"
            >
                <input type="hidden" name="topic_ids[]" :value="topicUuid" />
                <Input
                    name="name"
                    v-model="quizName"
                    class="flex-1"
                    placeholder="Quiz name"
                    required
                />
                <Button type="submit" size="sm" :disabled="processing">
                    <Plus class="h-4 w-4" />
                </Button>
            </Form>
        </div>
    </div>
</template>
