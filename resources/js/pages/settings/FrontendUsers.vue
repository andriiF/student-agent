<script setup lang="ts">
import { Form, Head, router } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';

type FrontendUserRow = {
    id: string;
    name: string;
    email: string;
    created_at: string | null;
};

type Props = {
    frontendUsers: {
        data: FrontendUserRow[];
    };
};

defineProps<Props>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Frontend users',
                href: '/settings/frontend-users',
            },
        ],
    },
});

const deleteUser = (userId: string) => {
    router.delete(`/settings/frontend-users/${userId}`);
};
</script>

<template>
    <Head title="Frontend users" />

    <div class="space-y-6">
        <Heading
            variant="small"
            title="Frontend users"
            description="Manage users of the frontend application"
        />

        <Form
            action="/settings/frontend-users"
            method="post"
            class="space-y-4 rounded-lg border p-4"
            v-slot="{ errors, processing }"
        >
            <h3 class="font-medium">Create frontend user</h3>

            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input id="name" name="name" required />
                <InputError :message="errors.name" />
            </div>

            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input id="email" name="email" type="email" required />
                <InputError :message="errors.email" />
            </div>

            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input id="password" name="password" type="password" required />
                <InputError :message="errors.password" />
            </div>

            <Button :disabled="processing">Create user</Button>
        </Form>

        <div class="space-y-4">
            <Form
                v-for="user in frontendUsers.data"
                :key="user.id"
                :action="`/settings/frontend-users/${user.id}`"
                method="post"
                class="rounded-lg border p-4 space-y-3"
                v-slot="{ processing, errors }"
            >
                <input type="hidden" name="_method" value="PATCH" />

                <div class="grid gap-2">
                    <Label :for="`name-${user.id}`">Name</Label>
                    <Input
                        :id="`name-${user.id}`"
                        name="name"
                        :default-value="user.name"
                        required
                    />
                    <InputError :message="errors.name" />
                </div>

                <div class="grid gap-2">
                    <Label :for="`email-${user.id}`">Email</Label>
                    <Input
                        :id="`email-${user.id}`"
                        name="email"
                        type="email"
                        :default-value="user.email"
                        required
                    />
                    <InputError :message="errors.email" />
                </div>

                <p class="text-xs text-muted-foreground">
                    Created: {{ user.created_at ?? '-' }}
                </p>

                <div class="flex gap-2">
                    <Button type="submit" :disabled="processing">Save</Button>
                    <Button
                        type="button"
                        variant="destructive"
                        @click="deleteUser(user.id)"
                    >
                        Delete
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>
