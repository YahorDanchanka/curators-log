<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        type="date"
        label="Дата"
        v-model="modelValue.date"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'date')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        type="textarea"
        label="Замечания"
        v-model="modelValue.comments"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'comments')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        type="textarea"
        label="Предложения"
        v-model="modelValue.suggestions"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'suggestions')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="ФИО"
        v-model="modelValue.full_name"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'full_name')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Должность"
        v-model="modelValue.position"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'position')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { AdviceFormModel } from '@/types'
import { QForm } from 'quasar'
import { Component as TComponent, computed } from 'vue'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: TComponent | string
  }>(),
  {
    component: QForm,
  }
)

const modelValue = defineModel<AdviceFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  date: Joi.date().required().max('now'),
  comments: Joi.string().required().allow(null, ''),
  suggestions: Joi.string().required().allow(null, ''),
  full_name: Joi.string().required().allow(null, '').max(255),
  position: Joi.string().required().allow(null, '').max(255),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
