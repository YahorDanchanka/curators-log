<template>
  <q-table :rows="props.asocialBehavior" :columns="columns" :rows-per-page-options="[0]" wrap-cells>
    <template v-slot:header="props">
      <q-tr :props="props">
        <q-th v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.label }}
        </q-th>
        <q-th class="text-left">Действия</q-th>
        <q-th class="text-left cell_autowidth">
          <q-btn color="primary" icon="add" size="sm" round @click="emit('create')" />
        </q-th>
      </q-tr>
    </template>
    <template v-slot:body="props">
      <q-tr :props="props">
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.value }}
        </q-td>
        <q-td colspan="2">
          <q-btn class="q-mr-sm" size="sm" color="primary" icon="edit" round @click="emit('edit', props.row)" />
          <q-btn class="q-mr-sm" size="sm" color="negative" icon="delete" round @click="emit('delete', props.row)" />
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script lang="ts" setup>
import { formatDate } from '@/helpers'
import { AsocialBehaviorTable } from '@/types'
import { QTableColumn } from 'quasar'

const props = defineProps<{ asocialBehavior: AsocialBehaviorTable[] }>()
const emit = defineEmits<{
  (e: 'create'): void
  (e: 'edit', achievement: AsocialBehaviorTable): void
  (e: 'delete', achievement: AsocialBehaviorTable): void
}>()

const columns: QTableColumn[] = [
  {
    name: 'date',
    label: 'Дата',
    align: 'left',
    sortable: true,
    field: 'date',
    format: (val: string) => formatDate(val),
  },
  {
    name: 'action',
    label: 'Характер проявления',
    align: 'left',
    sortable: true,
    field: 'action',
  },
  {
    name: 'sanctions',
    label: 'Меры',
    align: 'left',
    sortable: true,
    field: 'sanctions',
  },
  {
    name: 'result',
    label: 'Результат',
    align: 'left',
    sortable: true,
    field: 'result',
  },
]
</script>
