<template>
  <div class="report-table">
    <header class="report-table__header">
      <div class="report-table__cell" style="grid-row: span 2">
        Номер месяца {{ numericMonth }} {{ date.getFullYear() }} г.
      </div>
      <div class="report-table__cell" style="grid-row: span 2">Содержание деятельности</div>
      <div class="report-table__cell" style="grid-column: span 2">Количество часов</div>
      <div class="report-table__cell">Действия</div>
      <div class="report-table__cell">в течение недели</div>
      <div class="report-table__cell">
        6-й<br />
        день
      </div>
      <div class="report-table__cell link" @click="emit('loadPlan')">Загрузить из плана</div>
    </header>
    <div class="report-table__body">
      <template v-for="(reports, week) in groupedReportsByWeek" :key="week">
        <div class="report-table__group report-table__cell">
          {{ week }}
        </div>

        <draggable class="report-table__rows" item-key="id" group="reports" :list="reports">
          <template #item="{ element }">
            <div class="report-table__row report-table__row_draggable" :key="element.id">
              <div class="report-table__cell report-table__cell_content cell_no-padding">
                <q-editor min-height="5rem" style="border: 0; width: 100%" v-model="element.content" />
              </div>
              <div class="report-table__cell">
                <q-input
                  type="number"
                  step="0.5"
                  min="0.5"
                  :model-value="element.hours_per_week"
                  @update:model-value="(val: string) => (element.hours_per_week = parseFloat(val) ? parseFloat(val) : null)"
                />
              </div>
              <div class="report-table__cell">
                <q-input
                  type="number"
                  step="0.5"
                  min="0.5"
                  :model-value="element.hours_saturday"
                  @update:model-value="(val: string) => (element.hours_saturday = parseFloat(val) ? parseFloat(val) : null)"
                />
              </div>
              <div class="report-table__cell">
                <q-btn icon="delete" color="negative" size="sm" round @click="deleteReport(element, week)" />
              </div>
            </div>
          </template>
          <template #footer>
            <div class="report-table__row" style="order: 1">
              <div class="report-table__cell" style="grid-column: span 4">
                <q-btn icon="add" color="primary" size="sm" round @click="addReport(week)" />
              </div>
            </div>
          </template>
        </draggable>
      </template>
    </div>
    <footer class="report-table__footer">
      <p>Количество часов: {{ totalHours.toFixed(2) }}</p>
    </footer>
  </div>
</template>

<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { uid } from 'quasar'
import draggable from 'vuedraggable'
import { cloneDeep, groupBy, sum } from 'lodash'
import { date as quasarDate } from 'quasar'
import { Month, ReportFormModel, ReportFormModelItem } from '@/types'

const props = defineProps<{ date: Date; disable?: boolean }>()
const reports = defineModel<ReportFormModel>({ required: true })
const emit = defineEmits(['loadPlan'])

const groupedReportsByWeek = ref<{ [key: string]: ReportFormModel }>({
  '1': [],
  '2': [],
  '3': [],
  '4': [],
  ...groupBy(cloneDeep(reports.value), 'week'),
})

const numericMonth = computed(() => quasarDate.formatDate(props.date, 'MM'))

const totalHours = computed(() =>
  sum(reports.value.map((report) => report.hours_per_week + (report.hours_saturday ?? 0)))
)

function addReport(week: ReportFormModelItem['week']) {
  groupedReportsByWeek.value[week].push({
    id: uid(),
    content: '',
    hours_per_week: 1,
    hours_saturday: null,
    month: <Month>quasarDate.formatDate(props.date, 'M'),
    week,
  })
}

function deleteReport(item: ReportFormModelItem, week: ReportFormModelItem['week']) {
  groupedReportsByWeek.value[week] = groupedReportsByWeek.value[week].filter((i) => i.id !== item.id)
}

watch(
  groupedReportsByWeek,
  () => {
    const newModelValue: ReportFormModel = []

    for (const week in groupedReportsByWeek.value) {
      groupedReportsByWeek.value[week].forEach((item) => {
        /** Sync a week property */
        item.week = week
        newModelValue.push(item)
      })
    }

    reports.value = cloneDeep(newModelValue)
  },
  { deep: true }
)
</script>

<style scoped lang="sass">
.report-table
  font-size: 1em
  overflow-x: auto

.report-table__header
  display: grid
  grid-template-columns: 90px 1fr 100px 100px 100px
  grid-template-rows: repeat(2, 1fr)
  text-align: center
  font-weight: 500
  user-select: none
  font-size: 0.857em

.report-table__body
  display: grid
  grid-template-columns: 90px 1fr 100px 100px 100px
  font-size: 0.928em

.report-table__rows
  display: flex
  flex-wrap: wrap
  flex-direction: column
  grid-column: span 4

.report-table__row
  display: grid
  grid-template-columns: 1fr 100px 100px 100px

.report-table__cell
  display: flex
  flex-wrap: wrap
  align-items: center
  justify-content: center
  text-align: center
  border: 1px solid rgba(0, 0, 0, 0.12)
  padding: 7px 16px

.report-table__cell_content
  justify-content: left
  text-align: left
</style>
