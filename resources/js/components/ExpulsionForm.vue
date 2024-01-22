<template>
  <q-form class="expulsion-form form" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-select
        label="Учащийся"
        v-model="modelValue.student_id"
        :options="props.students"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'student_id')?.message]"
        option-label="initials"
        option-value="id"
        emit-value
        map-options
        hide-bottom-space
      />
      <q-input
        type="date"
        label="Дата отчисления"
        v-model="modelValue.date"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'date')?.message]"
        hide-bottom-space
      />
      <q-select
        label="По инициативе"
        v-model="modelValue.initiator"
        :options="[
          { label: 'учащегося', value: 'student' },
          { label: 'учреждения образования', value: 'college' },
        ]"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'initiator')?.message]"
        emit-value
        map-options
        hide-bottom-space
      />
      <q-input
        type="textarea"
        label="другие причины"
        v-model="modelValue.reason"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'reason')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </q-form>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import Joi from '@/Joi'
import { CourseModel, ExpulsionFormModel, StudentModel } from '@/types'

const props = defineProps<{ course: CourseModel; students: StudentModel[] }>()

const modelValue = defineModel<ExpulsionFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  student_id: Joi.number().integer().required(),
  date: Joi.date().required().min(props.course.start_education).max(props.course.end_education),
  initiator: Joi.string().required(),
  reason: Joi.string().allow('', null),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
