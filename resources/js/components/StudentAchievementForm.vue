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
        label="За какие достижения"
        v-model="modelValue.reason"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'reason')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        type="textarea"
        label="Форма поощрения"
        v-model="modelValue.prize"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'prize')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { StudentAchievementFormModel } from '@/types'
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

const modelValue = defineModel<StudentAchievementFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  date: Joi.date().required().max('now'),
  reason: Joi.string().required(),
  prize: Joi.string().required(),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
