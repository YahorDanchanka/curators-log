<template>
  <q-page padding>
    <Head title="Кураторы" />
    <AppTable id="curator-table" :rows="props.curators" :columns="columns" :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <h1 class="q-table__title" style="line-height: normal">Кураторы</h1>
        <q-space />
        <q-btn color="primary" icon="add" size="sm" round @click="router.get(route('curators.create'))" />
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
              v-if="props.row.can?.edit"
              class="q-mr-sm"
              size="sm"
              color="primary"
              icon="edit"
              round
              @click="router.get(route('curators.edit', { curator: props.row.id }))"
            />
            <q-btn
            v-if="props.row.can?.delete"
              size="sm"
              color="negative"
              icon="delete"
              round
              @click="onSave(CuratorService.delete(props.row.id), 'delete')"
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
import { CuratorService } from '@/services'
import { CuratorModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { QTableColumn } from 'quasar'
import route from 'ziggy-js'

const props = defineProps<{ curators: CuratorModel[] }>()

const columns: QTableColumn[] = [
  {
    name: 'surname',
    label: 'Фамилия',
    align: 'left',
    sortable: true,
    field: 'surname',
  },
  {
    name: 'name',
    label: 'Имя',
    align: 'left',
    sortable: true,
    field: 'name',
  },
  {
    name: 'patronymic',
    label: 'Отчество',
    align: 'left',
    sortable: true,
    field: 'patronymic',
  },
  {
    name: 'login',
    label: 'Логин',
    align: 'left',
    sortable: true,
    field: (row: CuratorModel) => row.user?.login,
  },
]
</script>
