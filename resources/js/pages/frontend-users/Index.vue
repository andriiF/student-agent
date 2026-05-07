<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import DataTable from '@/components/DataTable.vue';
import Pagination from '@/components/Pagination.vue';
import { index } from '@/routes/frontendUsers';
import { create } from '@/routes/frontendUsers';
import { edit } from '@/routes/frontendUsers';
import { destroy } from '@/routes/frontendUsers';
import { Pencil, Trash2 } from 'lucide-vue-next';
import type { FrontendUser } from '@/types';

interface Props {
    frontendUsers: {
        data: FrontendUser[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
    };
}

const props = defineProps<Props>();

const deleteFrontendUser = (user: FrontendUser) => {
    if (
        confirm(
            `Are you sure you want to delete ${user.firstname} ${user.lastname}?`,
        )
    ) {
        router.delete(destroy(user.uuid));
    }
};

const columns = [
    { key: 'uuid', label: 'ID' },
    { key: 'firstname', label: 'Firstname' },
    { key: 'lastname', label: 'Lastname' },
    { key: 'email', label: 'Email' },
    { key: 'phone', label: 'Phone' },
];
</script>

<template>
    <Head title="Frontend Users" />
    <div class="p-6">
        <div class="mb-6 flex items-center justify-between">
            <h1 class="text-2xl font-bold">Frontend Users</h1>
            <Link
                :href="create()"
                class="rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700"
            >
                Create Front User
            </Link>
        </div>

        <DataTable
            :columns="columns"
            :rows="props.frontendUsers.data"
            row-key="uuid"
        >
            <template #actions="{ row }">
                <Link
                    :href="edit(row.uuid)"
                    class="text-indigo-600 hover:text-indigo-900"
                >
                    <Pencil class="h-4 w-4"
                /></Link>
                <button
                    @click="deleteFrontendUser(row)"
                    class="cursor-pointer text-red-600 hover:text-red-900"
                >
                    <Trash2 class="h-4 w-4" />
                </button>
            </template>
        </DataTable>

        <Pagination
            :current-page="props.frontendUsers.current_page"
            :last-page="props.frontendUsers.last_page"
            :total="props.frontendUsers.total"
            :count="props.frontendUsers.data.length"
            :base-url="index()"
        />
    </div>
</template>
