<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AnswerManager from '@/pages/questions/AnswerManager.vue';
import { dashboard } from '@/routes';
import { index, update } from '@/routes/questions';
import { edit as editQuiz } from '@/routes/quizzes';
import { edit as editTopic } from '@/routes/topics';
import type { Quiz, Question } from '@/types';

const props = defineProps<{ question: Question; quizzes: Quiz[] }>();

const topicHref = props.question.quiz.topics?.[0]?.uuid
    ? editTopic(props.question.quiz.topics[0].uuid).url
    : null;

const quizHref = props.question.quiz.uuid
    ? editQuiz(props.question.quiz.uuid).url
    : null;
</script>

<template>
    <Head title="Edit Question" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Breadcrumbs
                    :breadcrumbs="[
                        { title: 'Dashboard', href: dashboard() },
                        { title: 'Topic', href: topicHref },
                        { title: 'Quiz', href: quizHref },
                        { title: 'Edit Question' },
                    ]"
                />
                <Heading variant="small" title="Edit Question" />

                <AnswerManager
                    :answers="props.question.answers ?? []"
                    :question-uuid="props.question.uuid"
                />

                <hr class="border-border" />
                <Form
                    v-bind="update(props.question.uuid)"
                    :action="update(props.question.uuid).url"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            name="name"
                            class="mt-1 block w-full"
                            required
                            :default-value="props.question.name"
                            placeholder="Question text"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="quiz_id">Quiz</Label>
                        <Select
                            name="quiz_id"
                            :default-value="props.question.quiz_id"
                            required
                        >
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a quiz" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="quiz in quizzes"
                                    :key="quiz.uuid"
                                    :value="quiz.uuid"
                                >
                                    {{ quiz.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="errors.quiz_id" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
