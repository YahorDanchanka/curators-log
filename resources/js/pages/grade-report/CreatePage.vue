<template>
  <q-page padding>
    <Head title="Создать ведомость успеваемости" />
    <GradeReportForm v-model="gradeReport" @submit="onSubmit" />
  </q-page>
</template>

<script lang="ts" setup>
import GradeReportForm from '@/components/GradeReportForm.vue'
import { inertiaFetch, onSave } from '@/helpers'
import type { CourseModel, GradeReportFormModel, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import route from 'ziggy-js'

const $q = useQuasar()
const props = defineProps<{ group: GroupModel; course: CourseModel }>()

const gradeReport = ref<GradeReportFormModel>({ name: '' })

function onSubmit() {
  onSave(
    inertiaFetch(route('groups.courses.grade-reports.store', { group: props.group.id, course: props.course.number }), {
      data: gradeReport.value,
      method: 'post',
    }),
    'create'
  )
}
</script>
