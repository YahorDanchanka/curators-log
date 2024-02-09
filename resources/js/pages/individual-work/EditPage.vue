<template>
  <q-page padding>
    <Head title="Факты ассоциального поведения учащегося" />
    <IndividualWorkForm
      v-model="modelValue"
      @submit="
        onSave(IndividualWorkService.update(props.group.id, props.studentNumber, props.individualWork.id, modelValue))
      "
    />
  </q-page>
</template>

<script lang="ts" setup>
import IndividualWorkForm from '@/components/IndividualWorkForm.vue'
import { onSave } from '@/helpers'
import { IndividualWorkService } from '@/services'
import { GroupModel, IndividualWorkFormModel, IndividualWorkTable, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
  group: GroupModel
  student: StudentModel
  studentNumber: string
  individualWork: IndividualWorkTable
}>()

const modelValue = ref<IndividualWorkFormModel>(props.individualWork)
</script>
