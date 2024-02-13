<template>
  <q-page padding>
    <Head title="Специальности" />
    <AppTable id="specialty-table" :rows="props.specialties" :columns="columns" :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <h1 class="q-table__title" style="line-height: normal">Специальности</h1>
        <q-space />
        <q-btn color="primary" icon="add" size="sm" round @click="router.get(route('specialties.create'))" />
      </template>
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
          <q-th class="text-left">Действия</q-th>
        </q-tr>
      </template>
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.value }}
          </q-td>
          <q-td>
            <q-btn
              class="q-mr-sm"
              size="sm"
              color="primary"
              icon="edit"
              round
              @click="router.get(route('specialties.edit', { specialty: props.row.id }))"
            />
            <q-btn
              size="sm"
              color="negative"
              icon="delete"
              round
              @click="onSave(SpecialtyService.delete(props.row.id), 'delete')"
            />
          </q-td>
        </q-tr>
      </template>
    </AppTable>
  </q-page>
</template>

<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue'
import { onSave } from '@/helpers'
import { SpecialtyService } from '@/services'
import { SpecialtyTable } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { QTableColumn } from 'quasar'
import route from 'ziggy-js'

const props = defineProps<{ specialties: SpecialtyTable[] }>()

const columns: QTableColumn[] = [
  {
    name: 'name',
    label: 'Название',
    align: 'left',
    sortable: true,
    field: 'name',
  },
  {
    name: 'code',
    label: 'Код',
    align: 'left',
    sortable: true,
    field: 'code',
  },
  {
    name: 'prefix',
    label: 'Префикс',
    align: 'left',
    sortable: true,
    field: 'prefix',
  },
]
</script>
