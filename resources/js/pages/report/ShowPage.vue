<template>
  <q-page padding>
    <Head title="Отчет о выполнении плана воспитательной и идеологической работы" />
    <div class="text-bold text-right">{{ props.course.number }} курс обучения</div>
    <h1 class="text-h4 text-center q-mb-md">
      <span class="text-uppercase">Отчет</span><br />
      о выполнении плана воспитательной и идеологической работы куратора учебной группы, проведении внеплановых
      мероприятий
    </h1>
    <ReportForm v-model="reports" :date="date" />
  </q-page>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { CourseModel, GroupModel, ReportFormModel } from '@/types'
import { inertiaFetch, onSave } from '@/helpers'
import ReportForm from '@/components/ReportForm.vue'

const props = defineProps<{ group: GroupModel; course: CourseModel; month: string }>()

const reports = ref<ReportFormModel>(props.course.reports || [])

const date = computed(() => {
  const instance = new Date(
    +props.month >= 9 && +props.month <= 12 ? props.course.start_education : props.course.end_education
  )
  instance.setMonth(+props.month - 1)
  return instance
})

document.addEventListener('save', () => {
  onSave(
    inertiaFetch(
      route('groups.courses.reports.sync', {
        group: props.group.id,
        course_number: props.course.number,
        month: props.month,
      }),
      {
        method: 'post',
        data: {
          data: reports.value,
        },
      }
    )
  )
})
</script>
