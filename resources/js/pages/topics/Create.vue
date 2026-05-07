<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
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
import { index, store } from '@/routes/topics';
import type { FrontendUser } from '@/types';

defineProps<{ frontendUsers: FrontendUser[] }>();
</script>

<template>
    <Head title="Create Topic" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Heading variant="small" title="Create Topic" />

                <Form
                    v-bind="store()"
                    :action="store().url"
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
                            placeholder="Topic name"
                        />
                        <InputError class="mt-2" :message="errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="front_user_id">Frontend User</Label>
                        <Select name="front_user_id" required>
                            <SelectTrigger class="w-full">
                                <SelectValue placeholder="Select a user" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="user in frontendUsers.data"
                                    :key="user.uuid"
                                    :value="user.uuid"
                                >
                                    {{ user.firstname }} {{ user.lastname }} ({{
                                        user.email
                                    }})
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError
                            class="mt-2"
                            :message="errors.front_user_id"
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
