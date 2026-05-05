<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/users';
import { update } from '@/routes/users';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{ user: User }>();
</script>

<template>
    <Head title="Edit User" />
    <div class="p-6">
        <h1 class="sr-only">Edit User</h1>

        <div class="flex flex-col space-y-6">
            <Heading
                variant="small"
                title="Profile information"
                description="Update the user's name and email address"
            />

            <Form
                v-bind="update(props.user.id)"
                class="space-y-6"
                v-slot="{ errors, processing }"
            >
                <div class="grid gap-2">
                    <Label for="name">Name</Label>
                    <Input
                        id="name"
                        name="name"
                        class="mt-1 block w-full"
                        :default-value="props.user.name"
                        required
                        autocomplete="name"
                        placeholder="Full name"
                    />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label for="email">Email address</Label>
                    <Input
                        id="email"
                        type="email"
                        name="email"
                        class="mt-1 block w-full"
                        :default-value="props.user.email"
                        required
                        autocomplete="email"
                        placeholder="Email address"
                    />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div class="grid gap-2">
                    <Label for="password">
                        New password
                        <span class="font-normal text-muted-foreground"
                            >(optional)</span
                        >
                    </Label>
                    <PasswordInput
                        id="password"
                        name="password"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="Leave blank to keep current password"
                    />
                    <InputError class="mt-2" :message="errors.password" />
                </div>

                <div class="grid gap-2">
                    <Label for="password_confirmation"
                        >Confirm new password</Label
                    >
                    <PasswordInput
                        id="password_confirmation"
                        name="password_confirmation"
                        class="mt-1 block w-full"
                        autocomplete="new-password"
                        placeholder="Confirm new password"
                    />
                    <InputError
                        class="mt-2"
                        :message="errors.password_confirmation"
                    />
                </div>

                <div class="flex items-center gap-4">
                    <Button :disabled="processing">Save</Button>
                    <Link
                        :href="index()"
                        class="text-sm text-muted-foreground hover:text-foreground"
                    >
                        Cancel
                    </Link>
                </div>
            </Form>
        </div>
    </div>
</template>
