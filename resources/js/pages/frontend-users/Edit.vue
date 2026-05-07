<script setup lang="ts">
import { Form, Head, Link } from '@inertiajs/vue3';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { index, update } from '@/routes/frontendUsers';
import type { FrontendUser } from '@/types';

const props = defineProps<{ frontendUser: FrontendUser }>();
</script>

<template>
    <Head title="Edit Front User" />
    <div class="mt-5 grid grid-cols-6 gap-4">
        <div class="col-span-6 px-4 md:col-span-4 md:col-start-2 md:px-0">
            <div class="flex flex-col space-y-6">
                <Heading variant="small" title="Edit Front User" />

                <Form
                    v-bind="update(props.frontendUser.uuid)"
                    :action="update(props.frontendUser.uuid).url"
                    class="space-y-6"
                    v-slot="{ errors, processing }"
                >
                    <div class="grid gap-2">
                        <Label for="firstname">Firstname</Label>
                        <Input
                            id="firstname"
                            name="firstname"
                            class="mt-1 block w-full"
                            :default-value="props.frontendUser.firstname"
                            required
                            autocomplete="name"
                            placeholder="Firstname"
                        />
                        <InputError class="mt-2" :message="errors.firstname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="lastname">Lastname</Label>
                        <Input
                            id="lastname"
                            name="lastname"
                            class="mt-1 block w-full"
                            :default-value="props.frontendUser.lastname"
                            required
                            autocomplete="name"
                            placeholder="Lastname"
                        />
                        <InputError class="mt-2" :message="errors.lastname" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            class="mt-1 block w-full"
                            :default-value="props.frontendUser.email"
                            required
                            autocomplete="email"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="errors.email" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="phone">Phone</Label>
                        <Input
                            id="phone"
                            type="text"
                            name="phone"
                            class="mt-1 block w-full"
                            :default-value="props.frontendUser.phone ?? ''"
                            placeholder="Phone"
                        />
                        <InputError class="mt-2" :message="errors.phone" />
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
