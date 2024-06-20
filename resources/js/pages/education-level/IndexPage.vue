<template>
  <ThePage padding>
    <Head :title />
    <CourseTitle :title :course-number="props.course.number" />
    <q-markup-table class="education-level-table" separator="cell" wrap-cells>
      <thead>
        <tr>
          <th class="cell_autowidth cell_sticky cell_sticky_top">
            №<br />
            п/п
          </th>
          <th class="cell_autowidth cell_sticky cell_sticky_top">
            Фамилия, имя, отчество<br />
            учащегося
          </th>
          <th
            class="cell_sticky cell_sticky_top"
            v-for="characteristic in props.characteristics"
            :key="characteristic.id"
            style="min-width: 103px; padding-left: 0; padding-right: 0"
          >
            <span class="education-level-table__column text_vertical">
              {{ characteristic.name }}
            </span>
          </th>
          <th class="cell_sticky cell_sticky_top">
            <div class="education-level-table__column text_vertical">Итоговая оценка учащегося</div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(student, studentIndex) in props.group.students" :key="student.id">
          <td>{{ studentIndex + 1 }}</td>
          <td class="cell_sticky">{{ student.initials }}</td>
          <td v-for="characteristic in props.characteristics" :key="characteristic.id">
            <q-select
              :modelValue="findRow(student.id, characteristic.id)?.level"
              :options="[
                { label: 'высокий', value: 4 },
                { label: 'средний', value: 3 },
                { label: 'достаточный', value: 2 },
                { label: 'низкий', value: 1 },
              ]"
              clearable
              emit-value
              dense
              @update:modelValue="(level) => updateRow(student.id, characteristic.id, level)"
            />
          </td>
          <th class="border-b">{{ findMeanByStudent(student.id) ? findMeanByStudent(student.id).toFixed(2) : '' }}</th>
        </tr>
        <tr>
          <td colspan="2">Итоговая оценка по группе</td>
          <th v-for="characteristic in props.characteristics" :key="characteristic.id">
            {{
              findMeanByCharacteristic(characteristic.id) ? findMeanByCharacteristic(characteristic.id).toFixed(2) : ''
            }}
          </th>
          <th>{{ meanLevel ? meanLevel.toFixed(2) : '' }}</th>
        </tr>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script lang="ts" setup>
import CourseTitle from '@/components/CourseTitle.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, inertiaFetch, onSave } from '@/helpers'
import {
  BaseTable,
  CharacteristicTable,
  CourseModel,
  EducationLevelFormModel,
  EducationLevelModel,
  GroupModel,
} from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { meanBy } from 'lodash'
import { uid } from 'quasar'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'

const props = defineProps<{
  group: GroupModel
  course: CourseModel
  characteristics: CharacteristicTable[]
  educationLevels: Required<EducationLevelModel, 'characteristic_student'>[]
}>()
const title = 'Результаты изучения уровня воспитанности учащихся'

const modelValue = ref<EducationLevelFormModel>(props.educationLevels)

const meanLevel = computed(() => meanBy(modelValue.value, 'level'))

function findRowCondition(studentId: BaseTable['id'], characteristicId: BaseTable['id']) {
  return (row: (typeof modelValue.value)[0]) =>
    row.characteristic_student!.student_id === studentId &&
    row.characteristic_student!.characteristic_id === characteristicId
}

function findRow(studentId: BaseTable['id'], characteristicId: BaseTable['id']) {
  return modelValue.value.find(findRowCondition(studentId, characteristicId))
}

function findMeanByStudent(studentId: BaseTable['id']) {
  const rowsByStudent = modelValue.value.filter((row) => row.characteristic_student.student_id === studentId)

  if (rowsByStudent.length === 0) {
    return 0
  }

  return meanBy(rowsByStudent, 'level')
}

function findMeanByCharacteristic(characteristicId: BaseTable['id']) {
  const rowsByCharacteristic = modelValue.value.filter(
    (row) => row.characteristic_student.characteristic_id === characteristicId
  )

  if (rowsByCharacteristic.length === 0) {
    return 0
  }

  return meanBy(rowsByCharacteristic, 'level')
}

function updateRow(studentId: BaseTable['id'], characteristicId: BaseTable['id'], level: number | null) {
  const row = findRow(studentId, characteristicId)

  if (row) {
    if (level) {
      row.level = level
      return
    }

    modelValue.value = modelValue.value.filter((r) => r !== row)
  } else {
    if (!level) return

    modelValue.value.push({
      id: uid(),
      level,
      characteristic_student: {
        id: uid(),
        student_id: studentId,
        characteristic_id: characteristicId,
      },
    })
  }
}

useEventListener(document, 'sync', () => {
  router.get(
    route('groups.courses.education-level.load-prev-course', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})

useEventListener(document, 'save', () => {
  onSave(
    inertiaFetch(
      route('groups.courses.education-level.sync', { group: props.group.id, course_number: props.course.number }),
      {
        method: 'post',
        data: {
          data: modelValue.value,
        },
      }
    )
  )
})

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.education-level.print', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})
</script>

<style lang="sass" scoped>
.education-level-table
  max-height: 550px

.education-level-table__column
  height: 150px
  text-align: left
</style>
