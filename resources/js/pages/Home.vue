<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { dashboard, login } from '@/routes';
</script>

<template>
    <Head title="Welcome"> </Head>
    <div
        class="flex min-h-screen flex-col items-center justify-center bg-[#FDFDFC] p-6 text-[#1b1b18] dark:bg-[#0a0a0a]"
    >
        <Link
            v-if="$page.props.auth.user"
            :href="dashboard()"
            class="inline-block rounded-sm border border-[#19140035] px-12 py-4 text-xl leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Dashboard
        </Link>
        <Link
            v-else
            :href="login()"
            class="inline-block rounded-sm border border-[#19140035] px-12 py-4 text-xl leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
            Log in
        </Link>
    </div>
</template>
