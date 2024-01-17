<template>
  <component class="course-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        type="number"
        label="Курс"
        min="1"
        max="4"
        v-model.number="modelValue.number"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'number')?.message]"
        :disable="disabledFields.includes('number')"
        hide-bottom-space
      />
      <q-input
        type="date"
        label="Начало обучения"
        v-model="modelValue.start_education"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'start_education')?.message]"
        :disable="disabledFields.includes('start_education')"
        hide-bottom-space
      />
      <q-input
        type="date"
        label="Конец обучения"
        v-model="modelValue.end_education"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'end_education')?.message]"
        :disable="disabledFields.includes('end_education')"
        hide-bottom-space
      />
      <q-select
        label="Куратор"
        option-label="full_name"
        option-value="id"
        v-model="modelValue.curator_id"
        :options="props.curators"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'curator_id')?.message]"
        :disable="disabledFields.includes('curator_id')"
        hide-bottom-space
        map-options
        emit-value
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { QForm } from 'quasar'
import Joi from '@/Joi'
import { CourseFormModel, CuratorModel } from '@/types'

const props = withDefaults(
  defineProps<{
    curators: Pick<CuratorModel, 'id' | 'full_name'>[]
    withoutSubmitButton?: boolean
    disabledFields: string[]
    component?: object | string
  }>(),
  {
    disabledFields: () => [],
    component: QForm,
  }
)

const modelValue = defineModel<CourseFormModel>({ required: true })
const emit = defineEmits(['emit'])

const schema = Joi.object({
  number: Joi.number().integer().required().min(1).max(4),
  start_education: Joi.date().required(),
  end_education: Joi.date()
    .required()
    .greater(Joi.ref('start_education'))
    .messages({ 'date.greater': 'Конец обучения должен быть больше начала обучения.' }),
  curator_id: Joi.number().integer().required(),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
