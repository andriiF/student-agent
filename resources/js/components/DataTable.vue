<script setup lang="ts" generic="T extends Record<string, unknown>">
interface Column {
    key: string;
    label: string;
}

defineProps<{
    columns: Column[];
    rows: T[];
    rowKey?: keyof T;
}>();

defineSlots<{
    actions(props: { row: T }): unknown;
}>();

const getByPath = (obj: T, path: string): unknown => {
    if (!path.includes('.')) {
        return obj[path];
    }

    return path.split('.').reduce<unknown>((current, part) => {
        if (current == null || typeof current !== 'object') {
            return undefined;
        }

        return (current as Record<string, unknown>)[part];
    }, obj);
};
</script>

<template>
    <div
        class="overflow-hidden rounded-lg border border-border bg-card shadow-sm"
    >
        <table class="min-w-full divide-y divide-border">
            <thead class="bg-muted">
                <tr>
                    <th
                        v-for="col in columns"
                        :key="col.key"
                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase"
                    >
                        {{ col.label }}
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-medium tracking-wider text-muted-foreground uppercase"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border bg-card">
                <tr
                    v-for="(row, i) in rows"
                    :key="rowKey ? String(row[rowKey]) : i"
                    class="hover:bg-accent"
                >
                    <td
                        v-for="col in columns"
                        :key="col.key"
                        class="whitespace-wrap max-w-[150px] px-6 py-4 text-sm text-card-foreground"
                    >
                        {{ getByPath(row, col.key) }}
                    </td>
                    <td
                        class="px-6 py-4 text-sm whitespace-nowrap text-card-foreground"
                    >
                        <div class="flex gap-2">
                            <slot name="actions" :row="row" />
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
