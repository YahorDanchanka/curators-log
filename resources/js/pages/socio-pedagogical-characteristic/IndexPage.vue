<template>
  <q-page padding>
    <Head :title="title" />
    <h1 class="course-title text-h4 q-mb-md">
      <span class="course-title__header">{{ title }}</span>
      <strong class="course-title__course">{{ props.course.number }} курс обучения</strong>
    </h1>
    <q-markup-table class="characteristic-table" separator="cell" wrap-cells>
      <thead>
        <tr>
          <th>
            <span class="text_vertical">№ п/п</span>
          </th>
          <th style="min-width: 200px">
            <div>Фамилия, имя, отчество</div>
          </th>
          <th style="min-width: 100px">
            <div>Дата рождения</div>
          </th>
          <th v-for="characteristic in props.characteristics" :key="characteristic.id">
            <div class="characteristic-table__characteristic text_vertical">{{ characteristic.name }}</div>
          </th>
          <th>
            <div class="characteristic-table__characteristic text_vertical">Иногородние учащиеся</div>
          </th>
          <th>
            <div class="characteristic-table__characteristic text_vertical">Учащиеся, проживающие в общежитии</div>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(student, studentIndex) in props.group.students">
          <td>{{ studentIndex + 1 }}</td>
          <td>{{ student.initials }}</td>
          <td>{{ student.birthday }}</td>
          <td
            class="text-center"
            v-for="characteristic in props.characteristics"
            :key="characteristic.id"
            @click="attachOrDetachCharacteristic(student, characteristic)"
          >
            {{ findAttachedCharacteristic(student, characteristic) ? '+' : '' }}
          </td>
          <td class="text-center" disabled>{{ student.is_nonresident ? '+' : '' }}</td>
          <td class="text-center" disabled>{{ student.is_dorm ? '+' : '' }}</td>
        </tr>
        <tr>
          <th colspan="3">Всего</th>
          <th v-for="characteristic in props.characteristics" :key="characteristic.id">
            {{
              attachedCharacteristics.filter(
                (attachedCharacteristic) => attachedCharacteristic.characteristic_id === characteristic.id
              ).length || ''
            }}
          </th>
          <th>{{ props.group.students.filter((student) => student.is_nonresident).length || '' }}</th>
          <th>{{ props.group.students.filter((student) => student.is_dorm).length || '' }}</th>
        </tr>
      </tbody>
    </q-markup-table>
  </q-page>
</template>

<script lang="ts" setup>
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { CharacteristicStudentTable, CharacteristicTable, CourseModel, GroupModel, StudentModel } from '@/types'
import { SocioPedagogicalCharacteristicService } from '@/services'
import { onSave, downloadFile } from '@/helpers'

const props = defineProps<{
  group: GroupModel
  course: CourseModel
  characteristics: CharacteristicTable[]
}>()

const title = computed(() => `Социально-педагогическая характеристика учебной группы № ${props.course.group_name}`)

const attachedCharacteristics = ref<CharacteristicStudentTable[]>(
  props.group.students!.map((student) => student.characteristics!.map((characteristic) => characteristic.pivot)).flat()
)

function getAttachedCharacteristicComparator(student: StudentModel, characteristic: CharacteristicTable) {
  return (c: AttachedCharacteristic) => c.student_id === student.id && c.characteristic_id === characteristic.id
}

function findAttachedCharacteristic(student: StudentModel, characteristic: CharacteristicTable) {
  return attachedCharacteristics.value.find(getAttachedCharacteristicComparator(student, characteristic))
}

function attachOrDetachCharacteristic(student: StudentModel, characteristic: CharacteristicTable) {
  const attachedCharacteristicIndex = attachedCharacteristics.value.findIndex(
    getAttachedCharacteristicComparator(student, characteristic)
  )

  if (attachedCharacteristicIndex !== -1) {
    /** Detach */
    attachedCharacteristics.value.splice(attachedCharacteristicIndex, 1)
  } else {
    /** Attach */
    attachedCharacteristics.value.push({ student_id: student.id, characteristic_id: characteristic.id })
  }
}

document.addEventListener('save', () => {
  onSave(SocioPedagogicalCharacteristicService.sync(props.group.id, props.course.number, attachedCharacteristics.value))
})

document.addEventListener('print', () => {
  downloadFile(
    route('groups.courses.socio-pedagogical-characteristic.print', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})
</script>

<style lang="sass" scoped>
.characteristic-table__characteristic
  max-height: 150px
  text-align: left
  height: 100%
</style>
