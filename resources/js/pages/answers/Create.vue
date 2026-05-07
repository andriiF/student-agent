<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { index, store } from '@/routes/answers';
import type { Question } from '@/types';

defineProps<{ questions: Question[] }>();
</script>

<template>
    <Head title="Create Answer" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Heading variant="small" title="Create Answer" />

                <Form v-bind="store()" class="space-y-6" v-slot="{ errors, processing }">
                    <div class="grid gap-2">
                        <Label for="name">Answer text</Label>
                        <Input id="name" name="name" class="mt-1 block w-full" required placeholder="Answer text" />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="question_id">Question</Label>
                        <Select name="question_id" required>
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a question" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="question in questions" :key="question.uuid" :value="question.uuid">
                                    {{ question.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="errors.question_id" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="explanation">Explanation</Label>
                        <Input id="explanation" name="explanation" class="mt-1 block w-full" placeholder="Optional explanation" />
                        <InputError class="mt-2" :message="errors.explanation" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="order">Order</Label>
                        <Input id="order" name="order" type="number" class="mt-1 block w-full" placeholder="Display order" />
                        <InputError class="mt-2" :message="errors.order" />
                    </div>

                    <div class="flex items-center gap-6">
                        <div class="flex items-center gap-2">
                            <Checkbox id="is_correct" name="is_correct" value="1" />
                            <Label for="is_correct">Correct answer</Label>
                        </div>
                        <div class="flex items-center gap-2">
                            <Checkbox id="is_active" name="is_active" value="1" :default-checked="true" />
                            <Label for="is_active">Active</Label>
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>
                        <Link :href="index()" class="text-sm text-muted-foreground hover:text-foreground">Cancel</Link>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
