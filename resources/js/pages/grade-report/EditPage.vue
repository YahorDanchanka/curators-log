<template>
  <q-page padding>
    <Head title="Редактировать ведомость успеваемости" />
    <GradeReportForm v-model="gradeReport" @submit="onSubmit" />
  </q-page>
</template>

<script lang="ts" setup>
import GradeReportForm from '@/components/GradeReportForm.vue'
import { inertiaFetch, onSave } from '@/helpers'
import type { CourseModel, GradeReportFormModel, GradeReportTable, GroupTable } from '@/types'
import { Head } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import route from 'ziggy-js'

const $q = useQuasar()
const props = defineProps<{ group: GroupTable; course: CourseModel; gradeReport: GradeReportTable }>()

const gradeReport = ref<GradeReportFormModel>(props.gradeReport)

function onSubmit() {
  onSave(
    inertiaFetch(
      route('groups.courses.grade-reports.update', {
        group: props.group.id,
        course: props.course.number,
        grade_report: props.gradeReport.id,
      }),
      {
        data: { ...gradeReport.value, _method: 'put' },
        method: 'post',
      }
    )
  )
}
</script>
