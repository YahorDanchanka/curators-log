<template>
  <ThePage padding>
    <Head title="План воспитательной и идеологической работы" />
    <div class="text-bold text-right">{{ props.course.number }} курс обучения</div>
    <h1 class="text-h4 text-center">
      <span class="text-uppercase">План</span><br />
      воспитательной и идеологической работы учебной группы № {{ props.course.group_name }}<br />
      на {{ month }} {{ date.getFullYear() }} г.
    </h1>
    <a
      class="block text-center q-mb-md"
      href="#"
      @click="
        router.visit(
          route('groups.courses.reports.show', {
            group: props.group.id,
            course_number: props.course.number,
            month: props.month,
          })
        )
      "
    >
      Перейти к отчету
    </a>
    <q-markup-table separator="cell" wrap-cells>
      <thead>
        <tr>
          <th style="width: 200px">Дата</th>
          <th>Форма работы</th>
          <th style="width: 200px">Отметка о выполнении</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <template v-for="(plans, section) in planService.getGroupedPlansBySection()" :key="section">
          <tr>
            <th class="border-b" colspan="4">{{ $t(`plan.${section}`) }}</th>
          </tr>
          <tr v-for="plan in plans" :key="plan.id">
            <td class="text-center">
              <span class="link">
                {{
                  typeof plan.date === 'object' && plan.date !== null
                    ? `с ${formatDate(plan.date.from)} по ${formatDate(plan.date.to)}`
                    : plan.date
                    ? formatDate(plan.date)
                    : 'выбрать'
                }}
              </span>
              <q-popup-edit v-model="plan.date" v-slot="scope" auto-save>
                <q-date
                  mask="YYYY-MM-DD"
                  v-model="scope.value"
                  :navigation-min-year-month="calendarBoundaries"
                  :navigation-max-year-month="calendarBoundaries"
                  :default-year-month="calendarBoundaries"
                  range
                />
              </q-popup-edit>
            </td>
            <td class="cell_no-padding">
              <q-editor min-height="5rem" style="border: 0" v-model="plan.content" />
            </td>
            <td>
              <q-select
                v-model="plan.done"
                :options="[
                  { label: 'Выполнено', value: 1 },
                  { label: 'Не выполнено', value: 0 },
                ]"
                emit-value
                map-options
              />
            </td>
            <td class="text-center">
              <q-btn icon="delete" color="negative" size="sm" round @click="onDeleteConfirm(plan.id)" />
            </td>
          </tr>
          <tr>
            <td class="text-center" colspan="4">
              <q-btn icon="add" color="primary" size="sm" round @click="planService.addPlan(section)" />
            </td>
          </tr>
        </template>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script setup lang="ts">
import ThePage from '@/components/ThePage.vue'
import { downloadFile, formatDate, onSave } from '@/helpers'
import { PlanService } from '@/services'
import { CourseModel, GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { date as quasarDate, useQuasar } from 'quasar'
import { computed, reactive } from 'vue'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; course: CourseModel; month: string }>()
const $q = useQuasar()
const { t } = useI18n()

const planService = reactive(new PlanService([]))
planService.load(props.course.plans || [])

const date = computed(() => {
  const instance = new Date(
    +props.month >= 9 && +props.month <= 12 ? props.course.start_education : props.course.end_education
  )
  instance.setMonth(+props.month - 1)
  return instance
})

const month = computed(() => quasarDate.formatDate(date.value, 'MMMM').toLowerCase())
const calendarBoundaries = computed(() => quasarDate.formatDate(quasarDate.startOfDate(date.value, 'month'), 'YYYY/MM'))

useEventListener(document, 'save', () => {
  onSave(planService.save(props.group.id, props.course.number, props.month))
})

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.plans.print', {
      group: props.group.id,
      course_number: props.course.number,
      month: props.month,
    })
  )
})

function onDeleteConfirm(planId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    planService.removePlan(planId)
  })
}
</script>
