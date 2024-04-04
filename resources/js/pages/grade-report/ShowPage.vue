<template>
  <ThePage padding>
    <Head :title="props.gradeReport.name" />
    <!-- <q-btn-dropdown class="q-mb-md" color="primary" label="Загрузить итоговые оценки из">
      <q-list>
        <q-item
          v-for="gradeReport in props.course.grade_reports"
          :key="gradeReport.id"
          clickable
          v-close-popup
          @click="loadSummaryGrades(gradeReport)"
        >
          <q-item-section>
            <q-item-label>{{ gradeReport.name }}</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-btn-dropdown> -->
    <GradeTable v-model="gradeTableState" :students="props.group.students" @save="onGradeTableSave" />
  </ThePage>
</template>

<script lang="ts" setup>
import GradeTable from '@/components/GradeTable.vue'
import ThePage from '@/components/ThePage.vue'
import { GradeDTO, GradeRowDTO, GradeSubjectDTO, GradeTableDTO } from '@/dto'
import { downloadFile } from '@/helpers'
import type { CourseModel, GradeReportModel, GradeReportTable, GradeSummaryResponse, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import axios from 'axios'
import { mapValues } from 'lodash'
import { Required } from 'utility-types'
import { ref } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{
  group: Required<GroupModel, 'students'>
  course: Required<CourseModel, 'grade_reports'>
  gradeReport: GradeReportModel
}>()

const gradeTableState = ref(
  props.gradeReport.grade?.body
    ? GradeTableDTO.fromJSON(JSON.parse(props.gradeReport.grade?.body))
    : new GradeTableDTO()
)

async function loadSummaryGrades(gradeReport: Pick<GradeReportTable, 'id'>) {
  const response = await axios.get<GradeSummaryResponse>(
    route('grade-reports.grades.summary', { group: props.group.id, grade_report: gradeReport.id })
  )

  /** Add summary grades */
  response.data.forEach((subject) => {
    gradeTableState.value.addSubject(
      new GradeSubjectDTO(
        subject.name,
        mapValues(subject.rows, (grade) => new GradeRowDTO([new GradeDTO('default', grade)]))
      )
    )
  })
}

function onGradeTableSave() {
  axios.post(
    route('grade-reports.grades.sync', { group: props.group.id, grade_report: props.gradeReport.id }),
    gradeTableState.value
  )
}

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.grade-reports.print', {
      group: props.group.id,
      course_number: props.course.number,
      grade_report: props.gradeReport.id,
    })
  )
})
</script>
