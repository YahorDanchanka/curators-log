<template>
  <ThePage class="socio-pedagogical-characteristic-page page_flex" padding use-height>
    <Head :title="title" />
    <CourseTitle :title="title" :course-number="props.course.number" />
    <StudentCharacteristicTable
      class="socio-pedagogical-characteristic-page__table page__table q-markup-table_header_sticky q-markup-table_column_sticky"
      v-model="attachedCharacteristics"
      :characteristics="props.characteristics"
      :students="props.group.students"
      sticky-header
    >
      <template #thead_afterbegin>
        <th class="cell_autowidth">№<br />п/п</th>
        <th style="min-width: 200px">Фамилия, имя, отчество</th>
        <th>Дата рождения</th>
      </template>
      <template #thead_beforeend>
        <th>Иногородние учащиеся</th>
        <th>Учащиеся, проживающие в общежитии</th>
      </template>
      <template v-slot="{ student, studentIndex, attachOrDetachCharacteristic, findAttachedCharacteristic }">
        <tr>
          <td>{{ studentIndex + 1 }}</td>
          <td>{{ student.initials }}</td>
          <td>{{ formatDate(student.birthday!) }}</td>
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
      </template>
      <template #summary_afterbegin>
        <th colspan="3">Всего</th>
      </template>
      <template #summary_beforeend>
        <th>{{ props.group.students.filter((student) => student.is_nonresident).length || '' }}</th>
        <th>{{ props.group.students.filter((student) => student.is_dorm).length || '' }}</th>
      </template>
    </StudentCharacteristicTable>
    <q-dialog v-model="isPrintMenuVisible">
      <q-card style="width: 100%">
        <q-card-actions vertical>
          <q-btn-group>
            <q-btn color="primary" label="Стандартная форма" @click="printExcel" />
            <q-btn color="primary" label="Отчетная" @click="printWord" />
          </q-btn-group>
        </q-card-actions>
      </q-card>
    </q-dialog>
  </ThePage>
</template>

<script lang="ts" setup>
import CourseTitle from '@/components/CourseTitle.vue'
import StudentCharacteristicTable from '@/components/StudentCharacteristicTable.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, formatDate, onSave } from '@/helpers'
import { SocioPedagogicalCharacteristicService } from '@/services'
import { CharacteristicStudentTable, CharacteristicTable, CourseModel, GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'

const props = defineProps<{
  group: Required<GroupModel, 'students'>
  course: CourseModel
  characteristics: CharacteristicTable[]
}>()

const isPrintMenuVisible = ref(false)

const title = computed(() => `Социально-педагогическая характеристика учебной группы № ${props.course.group_name}`)

const attachedCharacteristics = ref<CharacteristicStudentTable[]>(
  props.group.students!.map((student) => student.characteristics!.map((characteristic) => characteristic.pivot)).flat()
)

function printExcel() {
  downloadFile(
    route('groups.courses.socio-pedagogical-characteristic.print', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
}

function printWord() {
  downloadFile(
    route('groups.courses.socio-pedagogical-characteristic.print-word', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
}

useEventListener(document, 'sync', () => {
  router.get(
    route('groups.courses.socio-pedagogical-characteristic.load-prev-course', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})

useEventListener(document, 'save', () => {
  onSave(SocioPedagogicalCharacteristicService.sync(props.group.id, props.course.number, attachedCharacteristics.value))
})

useEventListener(document, 'print', () => {
  isPrintMenuVisible.value = true
})
</script>

<style lang="sass" scoped>
.socio-pedagogical-characteristic-page__table
  height: calc(100% - 16px - 24px - 80px)
</style>
