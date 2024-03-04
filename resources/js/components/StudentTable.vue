<template>
  <AppTable
    class="student-table"
    id="student-table"
    :rows="props.students"
    :columns="columns"
    :rows-per-page-options="[0]"
  >
    <template v-slot:top="data">
      <div v-if="title" class="q-table__title">{{ title }}</div>
      <q-space />
      <q-btn color="primary" icon="add" size="sm" round @click="emit('create')" />
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
      <q-tr
        class="students-table__student"
        :class="{ 'students-table__student_expelled': !!props.row.is_expelled }"
        :props="props"
      >
        <q-td v-for="col in props.cols" :key="col.name" :props="props">
          {{ col.value }}
        </q-td>
        <q-td>
          <q-btn
            class="q-mr-sm"
            size="sm"
            color="primary"
            icon="visibility"
            round
            @click="emit('show', props.row, props.rowIndex)"
          />
          <q-btn
            class="q-mr-sm"
            size="sm"
            color="primary"
            icon="edit"
            round
            @click="emit('edit', props.row, props.rowIndex)"
          />
          <q-btn class="q-mr-sm" size="sm" color="negative" icon="delete" round @click="emit('delete', props.row)" />
          <q-btn icon="more_vert" size="sm" round>
            <q-popup-proxy>
              <ListGenerator :list="getStudentActionList(props.row, props.rowIndex)" />
            </q-popup-proxy>
          </q-btn>
        </q-td>
      </q-tr>
    </template>
  </AppTable>
</template>

<script setup lang="ts">
import AppTable from '@/components/AppTable.vue'
import ListGenerator from '@/components/ListGenerator.vue'
import { formatDate } from '@/helpers'
import { MenuList, StudentModel } from '@/types'
import { sortBy } from 'lodash'

const props = defineProps<{ title?: string; students: StudentModel[] }>()
const emit = defineEmits<{
  (e: 'create'): void
  (e: 'show', student: StudentModel, index: number): void
  (e: 'edit', student: StudentModel, index: number): void
  (e: 'delete', student: StudentModel): void
}>()

const columns = [
  {
    name: 'number',
    label: '№',
    align: 'left',
    sortable: true,
    field: (row: StudentModel) => props.students.findIndex((student) => student === row) + 1,
  },
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
    name: 'sex',
    label: 'Пол',
    align: 'left',
    sortable: true,
    field: 'sex',
  },
  {
    name: 'birthday',
    label: 'Дата рождения',
    align: 'left',
    sortable: true,
    field: 'birthday',
    format: (val: string | null) => (val ? formatDate(val) : ''),
  },
  {
    name: 'age',
    label: 'Возраст',
    align: 'left',
    sortable: true,
    field: 'age',
    format: (val: number) => val || '',
  },
  {
    name: 'citizenship',
    label: 'Гражданство',
    align: 'left',
    sortable: true,
    field: 'citizenship',
  },
  {
    name: 'home_phone',
    label: 'Домашний телефон',
    align: 'left',
    sortable: true,
    field: 'home_phone',
  },
  {
    name: 'phone',
    label: 'Телефон',
    align: 'left',
    sortable: true,
    field: 'phone',
  },
  {
    name: 'educational_institution',
    label: 'Учреждение образования',
    align: 'left',
    sortable: true,
    field: 'educational_institution',
  },
  {
    name: 'social_conditions',
    label: 'Социальные условия',
    align: 'left',
    sortable: true,
    field: 'social_conditions',
  },
  {
    name: 'hobbies',
    label: 'Увлечения',
    align: 'left',
    sortable: true,
    field: 'hobbies',
  },
  {
    name: 'other_details',
    label: 'Другая информация',
    align: 'left',
    sortable: true,
    field: 'other_details',
  },
  {
    name: 'medical_certificate_date',
    label: 'Дата справки',
    align: 'left',
    sortable: true,
    field: 'medical_certificate_date',
  },
  {
    name: 'health',
    label: 'Группа здоровья',
    align: 'left',
    sortable: true,
    field: 'health',
  },
  {
    name: 'apprenticeship',
    label: 'Основа',
    align: 'left',
    sortable: true,
    field: 'apprenticeship',
  },
  {
    name: 'address',
    label: 'Домашний адрес',
    align: 'left',
    sortable: true,
    field: (row: StudentModel) => row.address?.address,
  },
  {
    name: 'study_address',
    label: 'Место проживания в период обучения',
    align: 'left',
    sortable: true,
    field: (row: StudentModel) => row.study_address?.address,
  },
]

function getStudentActionList(student: StudentModel, index: number): MenuList {
  return sortBy(
    [
      {
        label: 'Родственники',
        route: route('groups.students.relatives.index', { group: student.group_id, student: index + 1 }),
      },
    ],
    'label'
  )
}
</script>

<style lang="sass" scoped>
.student-table
  thead tr:first-child th:first-child
    background-color: white

  td:first-child
    background-color: white

  th:first-child,
  td:first-child
    position: sticky
    left: 0
    z-index: 1
</style>
