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
        label="Характер проявления"
        v-model="modelValue.action"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'action')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        type="textarea"
        label="Меры"
        v-model="modelValue.sanctions"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'sanctions')?.message]"
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
import { AsocialBehaviorFormModel } from '@/types'
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

const modelValue = defineModel<AsocialBehaviorFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  date: Joi.date().required().max('now'),
  action: Joi.string().required(),
  sanctions: Joi.string().required().allow(null, ''),
  result: Joi.string().required().allow(null, ''),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
