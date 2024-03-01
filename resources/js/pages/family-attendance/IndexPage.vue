<template>
  <ThePage padding>
    <Head title="Учет посещаемости родителями (другими законными представителями) проводимых мероприятий" />
    <h1 class="text-h4 text-center q-mb-md">
      Учет посещаемости родителями<br />
      <span class="text-h5">(другими законными представителями)</span> проводимых мероприятий
    </h1>
    <q-markup-table separator="cell" wrap-cell>
      <thead>
        <tr>
          <th rowspan="2">№ п/п</th>
          <th rowspan="2">ФИО учащегося</th>
          <th rowspan="2">ФИО родителей (законных представителей)</th>
          <th v-for="course in props.group.courses" :key="course.id" :colspan="findColumns(course.id).length">
            {{ course.number }} курс обучения
          </th>
          <th rowspan="2">Примечания</th>
        </tr>
        <tr>
          <template v-for="course in props.group.courses" :key="course.id">
            <td v-for="column in findColumns(course.id)" class="border-l border-b">
              <q-input
                type="date"
                :model-value="column.date"
                dense
                @update:model-value="setColumnDate(column, <string>$event)"
              />
            </td>
          </template>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(student, studentIndex) in props.group.students">
          <td>{{ studentIndex + 1 }}</td>
          <td>{{ student.initials }}</td>
          <td>
            <q-select
              option-label="initials"
              option-value="id"
              :model-value="findItem(student.id)?.relative_id"
              :options="student.adult_relatives"
              emit-value
              map-options
              clearable
              dense
              @update:model-value="setRelative(student.id, $event)"
            />
          </td>
          <template v-for="course in props.group.courses" :key="course.id">
            <q-td
              class="text-center cursor-pointer non-selectable"
              v-for="column in findColumns(course.id)"
              :key="column.id"
              @click="setValue(column, student.id)"
            >
              <template v-if="findColumnRow(column, student.id)?.value === 1">+</template>
              <template v-else-if="findColumnRow(column, student.id)?.value === 0">-</template>
              <template v-else>(пусто)</template>
            </q-td>
          </template>
          <td class="cell_no-padding">
            <input
              type="text"
              class="cell__input"
              :value="findItem(student.id)?.note"
              @input="setNote(student.id, ($event.target as HTMLInputElement).value)"
            />
          </td>
        </tr>
        <tr>
          <th colspan="3">Всего</th>
          <template v-for="course in props.group.courses" :key="course.id">
            <th class="text-center" v-for="column in findColumns(course.id)" :key="column.id">
              {{ column.rows.filter((row) => !!row.value).length || '' }}
            </th>
          </template>
          <td></td>
        </tr>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script lang="ts" setup>
import ThePage from '@/components/ThePage.vue'
import { downloadFile, inertiaFetch, onSave } from '@/helpers'
import {
  BaseTable,
  FamilyAttendanceFormModel,
  FamilyAttendanceFormModelColumn,
  FamilyAttendanceFormModelColumnRow,
  FamilyAttendanceFormModelItem,
  FamilyAttendanceRowTable,
  FamilyAttendanceTable,
  GroupModel,
} from '@/types'
import { Head } from '@inertiajs/vue3'
import { groupBy } from 'lodash'
import { uid } from 'quasar'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'

const props = defineProps<{
  group: Required<GroupModel, 'courses' | 'students'>
  familyAttendance: FamilyAttendanceTable[]
  familyAttendanceRows: FamilyAttendanceRowTable[]
}>()

const groupedRowsByDate = computed(() => groupBy(props.familyAttendanceRows, (row) => `${row.date}|${row.course_id}`))

const modelValue = ref<FamilyAttendanceFormModel>({
  columns: (() => {
    const columns: FamilyAttendanceFormModelColumn[] = []

    for (const template in groupedRowsByDate.value) {
      const rows = groupedRowsByDate.value[template]
      const [date, courseId] = template.split('|')
      columns.push({
        id: uid(),
        date,
        course_id: +courseId,
        rows: <FamilyAttendanceFormModelColumnRow[]>rows
          .map((row) => {
            const familyAttendance = props.familyAttendance.find((item) => item.id === row.family_attendance_id)
            return familyAttendance ? { id: row.id, value: row.value, student_id: familyAttendance!.student_id } : null
          })
          .filter(Boolean),
      })
    }

    props.group.courses.forEach((course) => {
      const columnsByCourseCount = columns.filter((column) => column.course_id === course.id).length

      if (columnsByCourseCount < 5) {
        for (let i = 0; i < 5 - columnsByCourseCount; i++) {
          columns.push({ id: uid(), rows: [], course_id: course.id })
        }
      }
    })

    return columns
  })(),
  items: <FamilyAttendanceFormModelItem[]>props.familyAttendance,
})

function findItemCondition(studentId: BaseTable['id']) {
  return (item: FamilyAttendanceFormModelItem) => item.student_id === studentId
}

function findItem(studentId: BaseTable['id']) {
  return modelValue.value.items.find(findItemCondition(studentId))
}

function findItemIndex(studentId: BaseTable['id']) {
  return modelValue.value.items.findIndex(findItemCondition(studentId))
}

function findColumns(courseId: BaseTable['id']) {
  return modelValue.value.columns.filter((column) => column.course_id === courseId)
}
function findColumnRow(
  column: FamilyAttendanceFormModelColumn,
  studentId: BaseTable['id']
): FamilyAttendanceFormModelColumnRow | undefined {
  return column.rows.find((row) => row.student_id === studentId)
}

function setRelative(studentId: BaseTable['id'], relativeId: BaseTable['id']) {
  const index = findItemIndex(studentId)

  if (index !== -1) {
    modelValue.value.items[index].relative_id = relativeId
  } else {
    modelValue.value.items.push({ id: uid(), student_id: studentId, relative_id: relativeId })
  }
}

function setNote(studentId: BaseTable['id'], value: string) {
  const index = findItemIndex(studentId)

  if (index !== -1) {
    modelValue.value.items[index].note = value
  } else {
    modelValue.value.items.push({ id: uid(), student_id: studentId, note: value })
  }
}

function setColumnDate(column: FamilyAttendanceFormModelColumn, value: string) {
  column.date = value
}

function setValue(column: FamilyAttendanceFormModelColumn, studentId: BaseTable['id']) {
  let columnRow = findColumnRow(column, studentId)

  if (!columnRow) {
    column.rows.push({ id: uid(), student_id: studentId })
    columnRow = findColumnRow(column, studentId)
  }

  if (columnRow!.value === 1) {
    columnRow!.value = 0
  } else if (columnRow!.value === 0) {
    column.rows = column.rows.filter((c) => c !== columnRow)
  } else {
    columnRow!.value = 1
  }
}

document.addEventListener('print', () => {
  downloadFile(route('groups.family-attendances.print', { group: props.group.id }))
})

document.addEventListener('save', () => {
  onSave(
    inertiaFetch(route('groups.family-attendances.sync', { group: props.group.id }), {
      method: 'post',
      data: {
        data: props.group.students.map((student) => {
          const item = findItem(student.id)

          return {
            note: item?.note,
            student_id: student.id,
            relative_id: item?.relative_id,
            rows: modelValue.value.columns
              .map((column) =>
                column.rows
                  .filter((row) => row.student_id === student.id)
                  .map((row) => ({ date: column.date, ...row, course_id: column.course_id }))
              )
              .flat(),
          }
        }),
      },
    })
  )
})
</script>
