<template>
  <q-markup-table separator="cell" wrap-cells>
    <thead>
      <slot name="thead_afterbegin">
        <th class="cell_autowidth">№<br />п/п</th>
        <th>Фамилия, имя, отчество</th>
      </slot>
      <th v-for="characteristic in props.characteristics" :key="characteristic.id">{{ characteristic.name }}</th>
      <slot name="thead_beforeend" />
    </thead>
    <tbody>
      <slot
        v-for="(student, studentIndex) in props.students"
        v-bind="{ student, studentIndex, attachOrDetachCharacteristic, findAttachedCharacteristic }"
      >
        <tr>
          <td>{{ studentIndex + 1 }}</td>
          <td>{{ student.initials }}</td>
          <td
            class="text-center"
            v-for="characteristic in props.characteristics"
            :key="characteristic.id"
            @click="attachOrDetachCharacteristic(student, characteristic)"
          >
            {{ findAttachedCharacteristic(student, characteristic) ? '+' : '' }}
          </td>
        </tr>
      </slot>
      <tr>
        <slot name="summary_afterbegin">
          <th colspan="2">Всего</th>
        </slot>
        <th v-for="characteristic in props.characteristics" :key="characteristic.id">
          {{ getAttachedCharacteristicCount(characteristic) || '' }}
        </th>
        <slot name="summary_beforeend"></slot>
      </tr>
    </tbody>
  </q-markup-table>
</template>

<script lang="ts" setup>
import { AttachedCharacteristic, CharacteristicTable, StudentModel } from '@/types'

const attachedCharacteristics = defineModel<AttachedCharacteristic[]>({ required: true })
const props = defineProps<{ characteristics: CharacteristicTable[]; students: StudentModel[] }>()

function getAttachedCharacteristicComparator(student: StudentModel, characteristic: CharacteristicTable) {
  return (c: AttachedCharacteristic) => c.student_id === student.id && c.characteristic_id === characteristic.id
}

function findAttachedCharacteristic(student: StudentModel, characteristic: CharacteristicTable) {
  return attachedCharacteristics.value.find(getAttachedCharacteristicComparator(student, characteristic))
}

function getAttachedCharacteristicCount(characteristic: CharacteristicTable) {
  return attachedCharacteristics.value.filter(
    (attachedCharacteristic) => attachedCharacteristic.characteristic_id === characteristic.id
  ).length
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
</script>
