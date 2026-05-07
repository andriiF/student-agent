<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { dashboard } from '@/routes';
import { index, update } from '@/routes/answers';
import { edit as editQuestion } from '@/routes/questions';
import { edit as editQuiz } from '@/routes/quizzes';
import { edit as editTopic } from '@/routes/topics';
import type { Answer, Question } from '@/types';

const props = defineProps<{ answer: Answer; questions: Question[] }>();

const isCorrect = ref(Boolean(props.answer.is_correct));
const isActive = ref(Boolean(props.answer.is_active));

const topicHref = props.answer.question.quiz.topics?.[0]?.uuid
    ? editTopic(props.answer.question.quiz.topics[0].uuid).url
    : null;

const quizHref = props.answer.question.quiz.uuid
    ? editQuiz(props.answer.question.quiz.uuid).url
    : null;

const questionHref = props.answer.question.uuid
    ? editQuestion(props.answer.question.uuid).url
    : null;
const explonation = ref(props.answer.explanation);
</script>

<template>
    <Head title="Edit Answer" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Breadcrumbs
                    :breadcrumbs="[
                        { title: 'Dashboard', href: dashboard() },
                        { title: 'Topic', href: topicHref },
                        { title: 'Quiz', href: quizHref },
                        { title: 'Question', href: questionHref },
                        { title: 'Edit Answer' },
                    ]"
                />
                <Heading variant="small" title="Edit Answer" />

                <Form
                    v-bind="update(props.answer.uuid)"
                    :action="update(props.answer.uuid).url"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >

                    <div class="grid gap-2">
                        <Label for="question">Question</Label>
                        <Input
                            id="question"
                            name="question"
                            readonly
                            class="mt-1 block w-full"
                            :default-value="props.answer.question.name"
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="name">Answer text</Label>
                        <Input
                            id="name"
                            name="name"
                            class="mt-1 block w-full"
                            required
                            :default-value="props.answer.name"
                            placeholder="Answer text"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="explanation">Explanation</Label>
                        <textarea
                            rows="4"
                            id="explanation"
                            name="explanation"
                            class="mt-1 block w-full border border-input bg-input/10 p-2 dark:bg-input/30"
                            placeholder="Optional explanation"
                            v-model="explonation"
                        ></textarea>
                        <InputError
                            class="mt-2"
                            :message="errors.explanation"
                        />
                    </div>

                    <div class="grid gap-2">
                        <Label for="order">Order</Label>
                        <Input
                            id="order"
                            name="order"
                            type="number"
                            class="mt-1 block w-full"
                            :default-value="props.answer.order ?? ''"
                            placeholder="Display order"
                        />
                        <InputError class="mt-2" :message="errors.order" />
                    </div>

                    <input
                        type="hidden"
                        name="is_correct"
                        :value="isCorrect ? 1 : 0"
                    />
                    <input
                        type="hidden"
                        name="is_active"
                        :value="isActive ? 1 : 0"
                    />
                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <Checkbox
                                id="is_correct"
                                v-model="isCorrect"
                                data-state="checked"
                            />
                            <Label for="is_correct">Correct answer</Label>
                        </div>
                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" v-model="isActive" />
                            <Label for="is_active">Active</Label>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>

                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
