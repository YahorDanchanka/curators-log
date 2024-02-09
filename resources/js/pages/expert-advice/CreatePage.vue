<template>
  <q-page padding>
    <Head title="Рекомендации специалистов" />
    <ExpertAdviceForm
      v-model="modelValue"
      @submit="onSave(ExpertAdviceService.create(props.group.id, props.studentNumber, modelValue), 'create')"
    />
  </q-page>
</template>

<script lang="ts" setup>
import ExpertAdviceForm from '@/components/ExpertAdviceForm.vue'
import { onSave } from '@/helpers'
import { ExpertAdviceService } from '@/services'
import { ExpertAdviceFormModel, GroupModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string }>()

const modelValue = ref<ExpertAdviceFormModel>({ content: '', result: '' })
</script>
