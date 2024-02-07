<template>
  <component class="address-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
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
        label="Содержимое"
        v-model="modelValue.content"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'content')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        type="textarea"
        label="Результат"
        v-model="modelValue.result"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'result')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { InteractionWithParentFormModel } from '@/types'
import { QForm } from 'quasar'
import { Component as TComponent, computed } from 'vue'

const props = withDefaults(
  defineProps<{
    minDate: Date
    maxDate: Date
    withoutSubmitButton?: boolean
    component?: TComponent | string
  }>(),
  {
    component: QForm,
  }
)

const modelValue = defineModel<InteractionWithParentFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  date: Joi.date().required().min(props.minDate).max(props.maxDate),
  content: Joi.string().required(),
  result: Joi.string().required().allow(null, ''),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
