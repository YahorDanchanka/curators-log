<template>
  <ThePage padding>
    <Head :title="title" />
    <h1 class="course-title text-h4 q-mb-md">
      <span class="course-title__header">{{ title }}</span>
      <strong class="course-title__course">{{ props.course.number }} курс обучения</strong>
    </h1>
    <StudentCharacteristicTable
      v-model="attachedCharacteristics"
      :characteristics="props.characteristics"
      :students="props.group.students"
    />
  </ThePage>
</template>

<script lang="ts" setup>
import StudentCharacteristicTable from '@/components/StudentCharacteristicTable.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { OtherCharacteristicService } from '@/services'
import { AttachedCharacteristic, CharacteristicTable, CourseModel, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'

const props = defineProps<{
  group: Required<GroupModel, 'students'>
  course: CourseModel
  characteristics: CharacteristicTable[]
}>()

const attachedCharacteristics = ref<AttachedCharacteristic[]>(
  props.group.students.map((student) => student.characteristics!.map((characteristic) => characteristic.pivot)).flat()
)

const title = computed(() => `Прочие характеристики учебной группы № ${props.course.group_name}`)

useEventListener(document, 'save', () => {
  onSave(OtherCharacteristicService.sync(props.group.id, props.course.number, attachedCharacteristics.value))
})
</script>
