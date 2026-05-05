<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import DataTable from '@/components/DataTable.vue';
import Pagination from '@/components/Pagination.vue';

import { create } from '@/routes/users';
import { edit } from '@/routes/users';
import { destroy } from '@/routes/users';

interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

interface Props {
    users: {
        data: User[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const columns = [
    { key: 'id', label: 'ID' },
    { key: 'name', label: 'Name' },
    { key: 'email', label: 'Email' },
];

const userToDelete = ref<User | null>(null);
const showDeleteDialog = ref(false);

const openDeleteDialog = (user: User) => {
    userToDelete.value = user;
    showDeleteDialog.value = true;
};

const confirmDelete = () => {
    if (userToDelete.value) {
        router.delete(destroy(userToDelete.value.id), {
            onSuccess: () => {
                showDeleteDialog.value = false;
                userToDelete.value = null;
            },
        });
    }
};
</script>

<template>
    <Head title="Dashboard" />
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Users</h1>
            <Link
                :href="create.get()"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Create User
            </Link>
        </div>

        <DataTable :columns="columns" :rows="props.users.data" row-key="id">
            <template #actions="{ row }">
                <Link
                    :href="edit(row.id)"
                    class="text-indigo-600 hover:text-indigo-900"
                    >Edit</Link
                >
                <button
                    @click="openDeleteDialog(row)"
                    class="cursor-pointer text-red-600 hover:text-red-900"
                >
                    Delete
                </button>
            </template>
        </DataTable>

        <Pagination
            :current-page="props.users.current_page"
            :last-page="props.users.last_page"
            :total="props.users.total"
            :count="props.users.data.length"
            base-url="/users"
        />
    </div>
</template>
