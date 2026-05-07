<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSelect } from '@/components/ui/multiselect';
import { dashboard } from '@/routes';
import { index, update } from '@/routes/quizzes';
import { edit as editTopic } from '@/routes/topics';
import type { Quiz, Topic } from '@/types';

const props = defineProps<{ quiz: Quiz; topics: Topic[] }>();

const topicHref = props.quiz.topics?.[0]?.uuid
    ? editTopic(props.quiz.topics[0].uuid).url
    : null;

const selectedTopics = ref<string[]>(props.quiz.topics?.map((t) => t.uuid) ?? []);

const topicOptions = computed(() =>
    props.topics.map((t) => ({ value: t.uuid, label: t.name })),
);
</script>

<template>
    <Head title="Edit Quiz" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Breadcrumbs
                    :breadcrumbs="[
                        { title: 'Dashboard', href: dashboard() },
                        ...(topicHref ? [{ title: 'Topic', href: topicHref }] : [{ title: 'Quizzes', href: index() }]),
                        { title: 'Edit Quiz' },
                    ]"
                />
                <Heading variant="small" title="Edit Quiz" />

                <Form
                    v-bind="update(props.quiz.uuid)"
                    :action="update(props.quiz.uuid).url"
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
                            :default-value="props.quiz.name"
                            placeholder="Quiz name"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label>Topics</Label>
                        <MultiSelect
                            v-model="selectedTopics"
                            :options="topicOptions"
                            placeholder="Select topics..."
                        />
                        <input
                            v-for="uuid in selectedTopics"
                            :key="uuid"
                            type="hidden"
                            name="topic_ids[]"
                            :value="uuid"
                        />
                        <InputError
                            class="mt-2"
                            :message="errors['topic_ids']"
                        />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="processing">Save</Button>
                        <Link
                            :href="index()"
                            class="text-sm text-muted-foreground hover:text-foreground"
                            >Cancel</Link
                        >
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>
