<template>
  <q-page padding>
    <Head title="Группы" />
    <q-table title="Группы" :rows="props.groups" :columns="groupColumns" :rows-per-page-options="[0]">
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th auto-width />
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
        </q-tr>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td auto-width>
            <q-btn
              size="sm"
              color="primary"
              round
              dense
              @click="props.expand = !props.expand"
              :icon="props.expand ? 'remove' : 'add'"
            />
          </q-td>
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.value }}
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <q-table title="Курсы" :rows="props.row.courses" :columns="courseColumns" :rows-per-page-options="[0]" />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>

<script lang="ts" setup>
import { Head } from '@inertiajs/vue3'
import { CourseModel, GroupModel } from '@/types'

const props = defineProps<{ groups: GroupModel[] }>()

const groupColumns = [
  {
    name: 'name',
    label: 'Название',
    align: 'left',
    sortable: true,
    field: (row: GroupModel) => row.name,
  },
]

const courseColumns = [
  {
    name: 'Курс',
    label: 'Курс',
    align: 'left',
    sortable: true,
    field: (row: CourseModel) => row.number,
  },
  {
    name: 'start_education',
    label: 'Начало обучения',
    align: 'left',
    sortable: true,
    field: (row: CourseModel) => row.start_education,
  },
  {
    name: 'end_education',
    label: 'Конец обучения',
    align: 'left',
    sortable: true,
    field: (row: CourseModel) => row.end_education,
  },
  {
    name: 'curator_id',
    label: 'Куратор',
    align: 'left',
    sortable: true,
    field: (row: CourseModel) => row.curator?.full_name,
  },
]
</script>
