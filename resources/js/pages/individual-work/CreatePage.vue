<template>
  <q-page padding>
    <Head title="Индивидуальная работа" />
    <IndividualWorkForm
      v-model="modelValue"
      @submit="onSave(IndividualWorkService.create(props.group.id, props.studentNumber, modelValue), 'create')"
    />
  </q-page>
</template>

<script lang="ts" setup>
import IndividualWorkForm from '@/components/IndividualWorkForm.vue'
import { onSave } from '@/helpers'
import { IndividualWorkService } from '@/services'
import { GroupModel, IndividualWorkFormModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string; type: string }>()

const modelValue = ref<IndividualWorkFormModel>({
  date: '',
  content: '',
  result: '',
  type: props.type === 'relative' || props.type === 'student' ? props.type : 'relative',
})
</script>
