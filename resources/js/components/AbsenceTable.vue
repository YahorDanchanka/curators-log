<template>
  <q-markup-table class="absence-markup-table" separator="cell">
    <thead>
      <tr>
        <th rowspan="3">№ п/п</th>
        <th rowspan="3" style="width: 200px">Ф.И.О. учащегося</th>
        <th :colspan="daysInMonth * 2">дата</th>
        <th colspan="2">из них</th>
      </tr>
      <tr>
        <th v-for="day in daysInMonth" class="border-l" colspan="2" style="width: calc(var(--cell-width) * 2)">
          {{ day }}
        </th>
        <th rowspan="2">уваж</th>
        <th rowspan="2">неуваж</th>
      </tr>
      <tr>
        <template v-for="day in daysInMonth">
          <th class="border-l" style="width: var(--cell-width)">у</th>
          <th style="width: var(--cell-width)">н</th>
        </template>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(student, studentIndex) in students" :key="student.id">
        <td>{{ studentIndex + 1 }}</td>
        <td style="width: 200px">{{ student.initials }}</td>

        <template v-for="day in daysInMonth" :key="day">
          <td class="absence-markup-table__absence cell_no-padding" v-for="t in ['reasonable', 'unreasonable']">
            <input
              class="absence-markup-table__input"
              type="number"
              min="1"
              max="12"
              style="width: var(--cell-width)"
              :value="findAbsence(day, student) ? findAbsence(day, student)[`${t}_count`] || '' : null"
              @input="writeAbsence($event, day, student, t)"
            />
          </td>
        </template>

        <td>
          {{
            sumBy(
              absences.filter((absence) => absence.student_id === student.id),
              'reasonable_count'
            ) || ''
          }}
        </td>

        <td>
          {{
            sumBy(
              absences.filter((absence) => absence.student_id === student.id),
              'unreasonable_count'
            ) || ''
          }}
        </td>
      </tr>

      <tr>
        <th :colspan="daysInMonth * 2 + 2">итого</th>
        <th>{{ totalReasonableCount || '' }}</th>
        <th>{{ totalUnreasonableCount || '' }}</th>
      </tr>
    </tbody>
  </q-markup-table>
</template>

<script lang="ts" setup>
import { getDaysInMonth } from '@/helpers'
import { AbsenceTable, StudentModel } from '@/types'
import { useVModel } from '@vueuse/core'
import { sumBy } from 'lodash'
import { date as quasarDate } from 'quasar'
import { computed } from 'vue'

const props = defineProps<{
  date: Date
  students: StudentModel[]
  modelValue: Pick<AbsenceTable, 'date' | 'reasonable_count' | 'unreasonable_count' | 'student_id'>[]
}>()
const emit = defineEmits(['update:modelValue'])

const absences = useVModel(props, 'modelValue', emit)

const daysInMonth = computed(() => getDaysInMonth(props.date.getMonth() - 1, props.date.getFullYear()))
const totalReasonableCount = computed(() => sumBy(absences.value, 'reasonable_count'))
const totalUnreasonableCount = computed(() => sumBy(absences.value, 'unreasonable_count'))

function findAbsence(day: number, student: StudentModel) {
  return absences.value.find((absence) => absence.student_id === student.id && new Date(absence.date).getDate() === day)
}

function enforceMinMax(event: InputEvent) {
  const input = event.target as HTMLInputElement
  const num = +input.value
  const min = +input.getAttribute('min')!
  const max = +input.getAttribute('max')!

  if (Number.isNaN(min) || Number.isNaN(max)) {
    return
  }

  if (input.value !== '') {
    if (num < min) {
      input.value = min.toString()
    }

    if (num > max) {
      input.value = max.toString()
    }
  }
}

function writeAbsence(event: Event, day: number, student: StudentModel, type: 'reasonable' | 'unreasonable') {
  enforceMinMax(<InputEvent>event)
  const absence = findAbsence(day, student)
  const input = <HTMLInputElement>event.target
  const parsedValue = +input.value
  const count = Number.isNaN(parsedValue) ? 0 : parsedValue

  if (absence) {
    absence[`${type}_count`] = count
  } else {
    absences.value.push({
      date: quasarDate.formatDate(quasarDate.adjustDate(quasarDate.clone(props.date), { date: day }), 'YYYY-MM-DD'),
      reasonable_count: type === 'reasonable' ? count : 0,
      unreasonable_count: type === 'unreasonable' ? count : 0,
      student_id: student.id,
    })
  }

  absences.value = absences.value.filter(
    (absence) => !(absence.reasonable_count === 0 && absence.unreasonable_count === 0)
  )
}
</script>

<style lang="sass" scoped>
.absence-markup-table
  --cell-width: 50px

.absence-markup-table__input
  max-width: 100%
  height: inherit
  border: none
  text-align: center

.absence-markup-table
  thead, td:nth-child(2)
    position: sticky
    background-color: white

  thead
    top: 0
    z-index: 3

  td:nth-child(2)
    left: 0
    z-index: 2
</style>
