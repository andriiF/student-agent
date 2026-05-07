<script setup lang="ts">
import { Form, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    destroy as destroyAnswer,
    edit as editAnswer,
    store as storeAnswer,
} from '@/routes/answers';
import type { Answer } from '@/types';

const props = defineProps<{ answers: Answer[]; questionUuid: string }>();

const answerName = ref('');
const isCorrect = ref(false);

const columns = [
    { key: 'name', label: 'Answer' },
    { key: 'is_correct_label', label: 'Correct' },
    { key: 'is_active_label', label: 'Active' },
];

const rows = computed(() =>
    props.answers.map((a) => ({
        uuid: a.uuid,
        name: a.name,
        is_correct_label: a.is_correct ? 'Yes' : 'No',
        is_active_label: a.is_active ? 'Yes' : 'No',
    })),
);

const deleteAnswer = (answer: { uuid: string; name: string }) => {
    if (confirm(`Are you sure you want to delete "${answer.name}"?`)) {
        router.delete(destroyAnswer(answer.uuid));
    }
};
</script>

<template>
    <div class="space-y-6">
        <h2 class="text-lg font-semibold">Answers</h2>

        <DataTable
            v-if="props.answers.length > 0"
            :columns="columns"
            :rows="rows"
            row-key="uuid"
        >
            <template #actions="{ row }">
                <a
                    :href="editAnswer(row.uuid).url"
                    class="text-indigo-600 hover:text-indigo-900"
                >
                    <Pencil class="h-4 w-4" />
                </a>
                <button
                    class="cursor-pointer text-red-600 hover:text-red-900"
                    @click="deleteAnswer(row)"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <div class="space-y-2 rounded-md border border-dashed p-4">
            <p class="text-sm font-medium text-muted-foreground">
                Add a new answer to this question
            </p>
            <Form
                v-bind="storeAnswer()"
                :action="storeAnswer().url"
                class="space-y-3"
                v-slot="{ processing }"
            >
                <input type="hidden" name="question_id" :value="questionUuid" />
                <div class="flex items-center gap-2">
                    <Input
                        name="name"
                        v-model="answerName"
                        class="flex-1"
                        placeholder="Answer text"
                        required
                    />
                    <Button type="submit" size="sm" :disabled="processing">
                        <Plus class="h-4 w-4" />
                    </Button>
                </div>
                <div class="flex items-center gap-2">
                    <Checkbox
                        id="is_correct"
                        name="is_correct"
                        :value="1"
                        v-model:checked="isCorrect"
                    />
                    <Label for="is_correct" class="text-sm font-normal">Correct answer</Label>
                </div>
            </Form>
        </div>
    </div>
</template>
