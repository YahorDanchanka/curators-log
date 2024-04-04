<template>
  <ThePage padding>
    <Head title="Отчет о выполнении плана воспитательной и идеологической работы" />
    <div class="text-bold text-right">{{ props.course.number }} курс обучения</div>
    <h1 class="text-h4 text-center">
      <span class="text-uppercase">Отчет</span><br />
      о выполнении плана воспитательной и идеологической работы куратора учебной группы, проведении внеплановых
      мероприятий
    </h1>
    <a
      class="block text-center q-mb-md"
      href="#"
      @click="
        router.visit(
          route('groups.courses.plans.index', {
            group: props.group.id,
            course_number: props.course.number,
            month: props.month,
          })
        )
      "
    >
      Перейти к плану
    </a>
    <ReportForm class="q-mb-md" v-model="reports" :date="date" @load-plan="loadPlan" />
  </ThePage>
</template>

<script lang="ts" setup>
import ReportForm from '@/components/ReportForm.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, inertiaFetch, onSave } from '@/helpers'
import { CourseModel, GroupModel, ReportFormModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { computed, ref } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; course: CourseModel; month: string }>()

const reports = ref<ReportFormModel>(props.course.reports || [])

const date = computed(() => {
  const instance = new Date(
    +props.month >= 9 && +props.month <= 12 ? props.course.start_education : props.course.end_education
  )
  instance.setMonth(+props.month - 1)
  return instance
})

function loadPlan() {
  router.get(
    route('groups.courses.reports.load-plan', {
      group: props.group.id,
      course_number: props.course.number,
      month: props.month,
    })
  )
}

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.reports.print-single', {
      group: props.group.id,
      course_number: props.course.number,
      month: props.month,
    })
  )
})

useEventListener(document, 'save', () => {
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
