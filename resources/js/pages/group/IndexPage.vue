<template>
  <q-page padding>
    <Head title="Группы" />
    <q-table :rows="props.groups" :columns="groupColumns" :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <div class="q-table__title">Группы</div>
        <q-space />
        <q-btn color="primary" icon="add" size="sm" round @click="router.get(route('groups.create'))" />
      </template>

      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th auto-width />
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
          <q-th class="text-left" auto-width>Действия</q-th>
        </q-tr>
      </template>

      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td auto-width>
            <q-btn
              size="sm"
              color="primary"
              round
              @click="props.expand = !props.expand"
              :icon="props.expand ? 'remove' : 'add'"
            />
          </q-td>
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.value }}
          </q-td>
          <q-td>
            <q-btn
              class="q-mr-sm"
              icon="edit"
              color="primary"
              size="sm"
              round
              @click="router.get(route('groups.edit', { group: props.row.id }))"
            />
            <q-btn
              class="q-mr-sm"
              icon="delete"
              color="negative"
              size="sm"
              round
              @click="onDeleteConfirm(props.row.id)"
            />
            <q-btn icon="more_vert" size="sm" round>
              <q-popup-proxy>
                <ListGenerator :list="getGroupActionList(props.row)" />
              </q-popup-proxy>
            </q-btn>
          </q-td>
        </q-tr>
        <q-tr v-show="props.expand" :props="props">
          <q-td colspan="100%">
            <q-table title="Курсы" :rows="props.row.courses" :columns="courseColumns" :rows-per-page-options="[0]">
              <template v-slot:header="props">
                <q-tr :props="props">
                  <q-th v-for="col in props.cols" :key="col.name" :props="props">
                    {{ col.label }}
                  </q-th>
                  <q-th class="text-left" auto-width>Действия</q-th>
                </q-tr>
              </template>
              <template v-slot:body="props">
                <q-tr :props="props">
                  <q-td v-for="col in props.cols" :key="col.name" :props="props">
                    {{ col.value }}
                  </q-td>
                  <q-td>
                    <q-btn icon="more_vert" size="sm" round>
                      <q-popup-proxy>
                        <ListGenerator :list="getCourseActionList(props.row)" />
                      </q-popup-proxy>
                    </q-btn>
                  </q-td>
                </q-tr>
              </template>
            </q-table>
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </q-page>
</template>

<script lang="ts" setup>
import ListGenerator from '@/components/ListGenerator.vue'
import { formatDate } from '@/helpers'
import { GroupService } from '@/services'
import { CourseModel, GroupModel, MenuList } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { sortBy } from 'lodash'
import { QTableColumn, useQuasar } from 'quasar'
import quasarLangRu from 'quasar/lang/ru'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ groups: GroupModel[] }>()
const $q = useQuasar()
const { t } = useI18n()

const groupColumns: QTableColumn[] = [
  {
    name: 'name',
    label: 'Название',
    align: 'left',
    sortable: true,
    field: 'name',
  },
  {
    name: 'education_period',
    label: 'Срок обучения',
    align: 'left',
    sortable: true,
    field: 'education_period',
  },
]

const courseColumns: QTableColumn[] = [
  {
    name: 'Курс',
    label: 'Курс',
    align: 'left',
    sortable: true,
    field: 'number',
  },
  {
    name: 'start_education',
    label: 'Начало обучения',
    align: 'left',
    sortable: true,
    field: 'start_education',
    format: (val: string) => formatDate(val),
  },
  {
    name: 'end_education',
    label: 'Конец обучения',
    align: 'left',
    sortable: true,
    field: 'end_education',
    format: (val: string) => formatDate(val),
  },
  {
    name: 'curator_id',
    label: 'Куратор',
    align: 'left',
    sortable: true,
    field: (row: CourseModel) => row.curator?.full_name,
  },
]

function getGroupActionList(group: GroupModel): MenuList {
  return sortBy(
    [
      {
        label: 'Учащиеся',
        route: route('groups.students.index', { group: group }),
      },
      {
        label: 'Взаимодействие с семьями учащихся',
        items: [
          {
            label: 'Учет посещаемости родителями (другими законными представителями) проводимых мероприятий',
            route: route('groups.family-attendances.index', { group }),
          },
          {
            label: 'Содержание взаимодействия с родителями (другими законными представителями) учащихся',
            route: route('groups.interaction-with-parents.index', { group }),
          },
        ],
      },
      {
        label: 'Замечания и предложения по организации идеологической и воспитательной работы',
        route: route('groups.advice.index', { group: group }),
      },
    ],
    'label'
  )
}

function getCourseActionList(course: CourseModel): MenuList {
  const group = course.group_id
  const course_number = course.number

  return sortBy(
    [
      {
        label: 'Актив учебной группы',
        route: route('groups.courses.leadership.index', { group, course_number }),
      },
      {
        label:
          'Отчет о выполнении плана воспитательной и идеологической работы куратора учебной группы, проведении внеплановых мероприятий',
        items: [
          { label: 'Печатная форма', route: route('groups.courses.reports.index', { group: group, course_number }) },
          ...quasarLangRu.date.months.map((month, monthIndex) => ({
            label: month,
            route: route('groups.courses.reports.show', { group: group, month: monthIndex + 1, course_number }),
          })),
        ],
      },
      {
        label: 'План идеологической и воспитательной работы',
        items: [
          ...quasarLangRu.date.months.map((month, monthIndex) => ({
            label: month,
            route: route('groups.courses.plans.index', { group: group, month: monthIndex + 1, course_number }),
          })),
        ],
      },
      {
        label: 'Достижения учебной группы',
        route: route('groups.courses.achievements.index', { group, course: course_number }),
      },
      {
        label: 'Занятость учащихся общественно полезной деятельностью',
        route: route('groups.courses.student-employment.index', { group, course_number }),
      },
      {
        label: 'Отчисления за период обучения',
        route: route('groups.courses.expulsions.index', { group, course: course_number }),
      },
      {
        label: 'Результаты изучения уровня воспитанности учащихся',
        route: route('groups.courses.education-level.index', { group, course_number }),
      },
      {
        label: 'Социально-педагогическая характеристика',
        route: route('groups.courses.socio-pedagogical-characteristic.index', { group, course_number }),
      },
      {
        label: 'Прочие характеристики',
        route: route('groups.courses.other-characteristic.index', { group, course_number }),
      },
      {
        label: 'Ведомости успеваемости',
        route: route('groups.courses.grade-reports.index', { group, course: course_number }),
      },
      {
        label: 'Социальный паспорт',
        route: route('groups.courses.social-passport.print', { group, course_number }),
        download: true,
      },
    ],
    'label'
  )
}

function onDeleteConfirm(groupId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    GroupService.delete(groupId)
  })
}
</script>
