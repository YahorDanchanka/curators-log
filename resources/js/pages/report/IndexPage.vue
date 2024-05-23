<template>
  <ThePage padding>
    <Head title="Отчет о выполнении плана воспитательной и идеологической работы" />
    <CourseTitle :course-number="props.course.number">
      <span class="text-uppercase">Отчет</span><br />
      о выполнении плана воспитательной и идеологической работы куратора учебной группы, проведении внеплановых
      мероприятий
    </CourseTitle>
    <template v-for="(reports, month) in props.groupedReports">
      <q-markup-table class="q-mb-xs" separator="cell" wrap-cells>
        <thead>
          <tr>
            <th rowspan="2" style="width: 100px">
              Номер месяца<br />
              {{ quasarDate.formatDate(getDate(parseInt(month)), 'MM') }}
              {{ getDate(parseInt(month)).getFullYear() }} г.
            </th>
            <th rowspan="2">Содержание деятельности</th>
            <th colspan="2">Количество часов</th>
            <th class="border-b cell_autowidth">Действия</th>
          </tr>
          <tr>
            <th class="border-l" style="width: 100px">в течение недели</th>
            <th style="width: 100px">
              6-й<br />
              день
            </th>
            <td class="border-b text-center">
              <q-btn
                icon="edit"
                color="primary"
                size="sm"
                round
                @click="
                  router.get(
                    route('groups.courses.reports.show', {
                      group: props.group.id,
                      course_number: props.course.number,
                      month: parseInt(month),
                    })
                  )
                "
              />
            </td>
          </tr>
        </thead>
        <tbody>
          <template v-for="(reports, week) in groupBy(reports, 'week')">
            <tr>
              <th class="border-r border-b" :rowspan="reports.length + 1">{{ week }}</th>
            </tr>

            <tr v-for="report in reports">
              <td v-html="report.content"></td>
              <td class="text-center">{{ report.hours_per_week }}</td>
              <td class="text-center">{{ report.hours_saturday }}</td>
              <td></td>
            </tr>
          </template>
        </tbody>
      </q-markup-table>
      <p>Количество часов: {{ getTotalHours(reports).toFixed(2) }}</p>
    </template>
  </ThePage>
</template>

<script setup lang="ts">
import CourseTitle from '@/components/CourseTitle.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile } from '@/helpers'
import { CourseModel, GroupModel, ReportFormModel, ReportTable } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { groupBy, sum } from 'lodash'
import { date as quasarDate } from 'quasar'
import route from 'ziggy-js'

const props = defineProps<{
  group: GroupModel
  course: CourseModel
  groupedReports: { [key: string]: ReportTable[] }
}>()

function getDate(month: number) {
  const instance = new Date(month >= 9 && month <= 12 ? props.course.start_education : props.course.end_education)
  instance.setMonth(month - 1)
  return instance
}

function getTotalHours(reports: ReportFormModel) {
  return sum(reports.map((report) => report.hours_per_week + (report.hours_saturday ?? 0)))
}

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.reports.print', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})
</script>
