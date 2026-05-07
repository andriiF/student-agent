<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import PasswordInput from '@/components/PasswordInput.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index } from '@/routes/users';
import { store } from '@/routes/users';
</script>

<template>
    <Head title="Create User" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Heading variant="small" title="Create User" />

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
                            required
                            autocomplete="email"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password"> Password </Label>
                        <PasswordInput
                            id="password"
                            name="password"
                            class="mt-1 block w-full"
                            required
                            autocomplete="new-password"
                            placeholder="Password"
                        />
                        <InputError class="mt-2" :message="errors.password" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="password_confirmation"
                            >Confirm password</Label
                        >
                        <PasswordInput
                            id="password_confirmation"
                            name="password_confirmation"
                            class="mt-1 block w-full"
                            autocomplete="new-password"
                            placeholder="Confirm password"
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
    </div>
</template>
