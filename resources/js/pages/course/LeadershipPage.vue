<template>
  <ThePage padding>
    <Head :title="title" />
    <CourseTitle :title :course-number="props.course.number" />
    <q-select
      class="q-mb-md"
      label="Староста"
      option-value="id"
      option-label="full_name"
      v-model="modelValue.leader_id"
      :options="props.group.students"
      emit-value
      map-options
      clearable
    />
    <q-select
      class="q-mb-md"
      label="Заместитель старосты"
      option-value="id"
      option-label="full_name"
      v-model="modelValue.deputy_leader_id"
      :options="props.group.students"
      emit-value
      map-options
      clearable
    />
    <q-select
      class="q-mb-md"
      label="Секретарь ОО «БРСМ» учебной группы"
      option-value="id"
      option-label="full_name"
      v-model="modelValue.brsm_secretary_id"
      :options="props.group.students"
      emit-value
      map-options
      clearable
    />
    <q-select
      label="Профсоюзный организатор"
      option-value="id"
      option-label="full_name"
      v-model="modelValue.union_organizer_id"
      :options="props.group.students"
      emit-value
      map-options
      clearable
    />
    <h2 class="text-h4 text-center q-mt-md q-mb-md">Состав совета группы</h2>
    <q-markup-table separator="cell" wrap-cells>
      <thead>
        <tr>
          <th>Сектор</th>
          <th class="cell_autowidth">№<br />п/п</th>
          <th>Фамилия, имя, отчество</th>
        </tr>
      </thead>
      <tbody>
        <template
          v-for="groupCompositionCharacteristic in props.groupCompositionCharacteristics"
          :key="groupCompositionCharacteristic.id"
        >
          <tr>
            <td :rowspan="findGroupCompositionRows(groupCompositionCharacteristic.id).length + 2">
              {{ groupCompositionCharacteristic.name }}
            </td>
          </tr>
          <tr v-for="(row, rowIndex) in findGroupCompositionRows(groupCompositionCharacteristic.id)">
            <td class="border-l">{{ rowIndex + 1 }}</td>
            <td>
              <div class="row items-center q-col-gutter-sm">
                <div class="col">
                  <q-select
                    option-value="id"
                    option-label="full_name"
                    v-model="row.student_id"
                    :options="props.group.students"
                    emit-value
                    map-options
                  />
                </div>
                <div class="col-auto">
                  <q-btn
                    color="negative"
                    icon="delete"
                    size="sm"
                    round
                    @click="removeGroupCompositionRow(groupCompositionCharacteristic.id, row.student_id)"
                  />
                </div>
              </div>
            </td>
          </tr>
          <tr>
            <td class="border-l text-center" colspan="2">
              <q-btn
                color="primary"
                icon="add"
                size="sm"
                round
                @click="addGroupCompositionRow(groupCompositionCharacteristic.id)"
              />
            </td>
          </tr>
        </template>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script lang="ts" setup>
import CourseTitle from '@/components/CourseTitle.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, onSave } from '@/helpers'
import { StudentRepository } from '@/repositories'
import { LeadershipService } from '@/services'
import { BaseTable, CharacteristicTable, CourseModel, GroupModel, LeadershipFormModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { computed, reactive } from 'vue'

const props = defineProps<{
  group: GroupModel
  course: CourseModel
  groupCompositionCharacteristics: CharacteristicTable[]
}>()

const title = 'Актив учебной группы'

const studentRepository = computed(() => new StudentRepository(props.group.students ?? []))
const attachedCharacteristics = computed(() => studentRepository.value.getAttachedCharacteristics())
const pivotAttachedCharacteristics = computed(() => studentRepository.value.getPivotAttachedCharacteristics())

const modelValue = reactive<LeadershipFormModel>({
  leader_id: pivotAttachedCharacteristics.value.find((pivot) => pivot.characteristic_id === 19)?.student_id,
  deputy_leader_id: pivotAttachedCharacteristics.value.find((pivot) => pivot.characteristic_id === 20)?.student_id,
  brsm_secretary_id: pivotAttachedCharacteristics.value.find((pivot) => pivot.characteristic_id === 21)?.student_id,
  union_organizer_id: pivotAttachedCharacteristics.value.find((pivot) => pivot.characteristic_id === 22)?.student_id,
  group_composition: attachedCharacteristics.value.filter((c) => c.type === 'group-composition').map((c) => c.pivot),
})

function findGroupCompositionRows(characteristicId: BaseTable['id']) {
  return modelValue.group_composition.filter((c) => c.characteristic_id === characteristicId)
}

function removeGroupCompositionRow(characteristicId: BaseTable['id'], studentId: BaseTable['id']) {
  modelValue.group_composition = modelValue.group_composition.filter(
    (row) => !(row.characteristic_id === characteristicId && row.student_id === studentId)
  )
}

function addGroupCompositionRow(characteristicId: BaseTable['id']) {
  modelValue.group_composition.push({
    student_id: null,
    characteristic_id: characteristicId,
    course_id: props.course.id,
  })
}

useEventListener(document, 'sync', () => {
  router.get(
    route('groups.courses.leadership.load-prev-course', { group: props.group.id, course_number: props.course.number })
  )
})

useEventListener(document, 'save', () => {
  onSave(LeadershipService.sync(props.group.id, props.course.number, modelValue))
})

useEventListener(document, 'print', () => {
  downloadFile(route('groups.courses.leadership.print', { group: props.group.id, course_number: props.course.number }))
})
</script>
