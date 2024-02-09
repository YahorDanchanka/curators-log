<template>
  <q-page padding>
    <Head title="Рекомендации специалистов" />
    <ExpertAdviceForm
      v-model="modelValue"
      @submit="
        onSave(ExpertAdviceService.update(props.group.id, props.studentNumber, props.expertAdvice.id, modelValue))
      "
    />
  </q-page>
</template>

<script lang="ts" setup>
import ExpertAdviceForm from '@/components/ExpertAdviceForm.vue'
import { onSave } from '@/helpers'
import { ExpertAdviceService } from '@/services'
import { ExpertAdviceFormModel, ExpertAdviceTable, GroupModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
  group: GroupModel
  student: StudentModel
  studentNumber: string
  expertAdvice: ExpertAdviceTable
}>()

const modelValue = ref<ExpertAdviceFormModel>(props.expertAdvice)
</script>
