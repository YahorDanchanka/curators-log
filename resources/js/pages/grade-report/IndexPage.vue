<template>
  <q-page padding>
    <Head :title="title" />
    <AppTable
      id="grade-report-table"
      class="q-mb-sm"
      :rows="props.course.grade_reports"
      :columns="columns"
      :rows-per-page-options="[0]"
    >
      <template v-slot:top="data">
        <div class="q-table__title">{{ title }}</div>
        <q-space />
        <q-btn
          color="primary"
          icon="add"
          dense
          round
          @click="
            router.get(
              route('groups.courses.grade-reports.create', {
                group: props.group.id,
                course: props.course.number,
              })
            )
          "
        />
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
              icon="visibility"
              round
              @click="
                router.get(
                  route('groups.courses.grade-reports.show', {
                    group: group.id,
                    course: course.number,
                    grade_report: props.row.id,
                  })
                )
              "
            />
            <q-btn
              class="q-mr-sm"
              size="sm"
              color="primary"
              icon="edit"
              round
              @click="
                router.get(
                  route('groups.courses.grade-reports.edit', {
                    group: group.id,
                    course: course.number,
                    grade_report: props.row.id,
                  })
                )
              "
            />
            <q-btn
              size="sm"
              color="negative"
              icon="delete"
              round
              @click="
                onSave(
                  inertiaFetch(
                    route('groups.courses.grade-reports.destroy', {
                      group: group.id,
                      course: course.number,
                      grade_report: props.row.id,
                    }),
                    { method: 'delete' }
                  ),
                  'delete'
                )
              "
            />
          </q-td>
        </q-tr>
      </template>
    </AppTable>
  </q-page>
</template>

<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue'
import { inertiaFetch, onSave } from '@/helpers'
import { CourseModel, GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { QTableColumn } from 'quasar'
import { Required } from 'utility-types'
import { computed } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; course: Required<CourseModel, 'grade_reports'> }>()

const title = computed(() => `Ведомости успеваемости группы ${props.course.group_name}`)

const columns: QTableColumn[] = [
  {
    name: 'name',
    label: 'Название',
    align: 'left',
    sortable: true,
    field: 'name',
  },
]
</script>
