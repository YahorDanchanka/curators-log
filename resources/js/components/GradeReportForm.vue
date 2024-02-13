<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        label="Название"
        v-model="modelValue.name"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'name')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { GradeReportFormModel } from '@/types'
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

const modelValue = defineModel<GradeReportFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  name: Joi.string().required().max(255),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
