<template>
  <ThePage class="page_flex" padding use-height>
    <Head :title="title" />
    <CourseTitle :title="title" :course-number="props.course.number" />
    <AbsenceTable class="page__table" v-model="absences" :students="props.group.students" :date="courseDate" />
  </ThePage>
</template>

<script lang="ts" setup>
import ThePage from '@/components/ThePage.vue'
import AbsenceTable from '@/components/AbsenceTable.vue'
import CourseTitle from '@/components/CourseTitle.vue'
import { downloadFile, getCourseDate, inertiaFetch, onSave } from '@/helpers'
import { CourseModel, GroupModel, AbsenceTable as IAbsenceTable } from '@/types'
import { Head } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { cloneDeep } from 'lodash'
import { date as quasarDate } from 'quasar'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'

const props = defineProps<{
  group: Required<GroupModel, 'students'>
  course: Required<CourseModel, 'group_name'>
  month: string
  absences: IAbsenceTable[]
}>()

const absences = ref(cloneDeep(props.absences))

const courseDate = computed(() => getCourseDate(props.course, +props.month))

const title = computed(
  () =>
    `Ведомость пропусков группы ${props.course.group_name} за ${quasarDate.formatDate(
      courseDate.value,
      'MMMM'
    )} ${courseDate.value.getFullYear()}`
)

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.absences.print', {
      group: props.group.id,
      course_number: props.course.number,
      month: props.month,
    })
  )
})

useEventListener(document, 'save', () => {
  onSave(
    inertiaFetch(
      route('groups.courses.absences.sync', {
        group: props.group.id,
        course_number: props.course.number,
        month: props.month,
      }),
      { method: 'post', data: { rows: absences.value } }
    )
  )
})
</script>
