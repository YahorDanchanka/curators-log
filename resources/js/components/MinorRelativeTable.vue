<template>
  <q-table :rows="props.relatives" :columns="columns" :rows-per-page-options="[0]">
    <template v-slot:top="data">
      <div class="q-table__title">Несовершеннолетние родственники</div>
      <q-space />
      <q-btn color="primary" icon="add" dense round @click="emit('create')" />
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
          <q-btn class="q-mr-sm" size="sm" color="primary" icon="edit" round @click="emit('edit', props.row)" />
          <q-btn class="q-mr-sm" size="sm" color="negative" icon="delete" round @click="emit('delete', props.row)" />
        </q-td>
      </q-tr>
    </template>
  </q-table>
</template>

<script setup lang="ts">
import { RelativeModel } from '@/types'
import { QTableColumn } from 'quasar'

const props = defineProps<{ relatives: RelativeModel[] }>()
const emit = defineEmits<{
  (e: 'create'): void
  (e: 'edit', id: RelativeModel): void
  (e: 'delete', id: RelativeModel): void
}>()

const columns: QTableColumn[] = [
  {
    name: 'surname',
    label: 'Фамилия',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.surname,
  },
  {
    name: 'name',
    label: 'Имя',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.name,
  },
  {
    name: 'patronymic',
    label: 'Отчество',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.patronymic,
  },
  {
    name: 'sex',
    label: 'Пол',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.sex,
  },
  {
    name: 'birthday',
    label: 'Дата рождения',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.birthday,
  },
  {
    name: 'age',
    label: 'Возраст',
    align: 'left',
    sortable: true,
    field: 'age',
  },
  {
    name: 'educational_institution',
    label: 'Учреждение образования',
    align: 'left',
    sortable: true,
    field: (row: RelativeModel) => row.educational_institution,
  },
]
</script>
