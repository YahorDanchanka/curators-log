<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        type="textarea"
        label="Рекомендации педагога-психолога, педагога социального (психологическая диагностика, консультирование)"
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
import { ExpertAdviceFormModel } from '@/types'
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

const modelValue = defineModel<ExpertAdviceFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  content: Joi.string().required(),
  result: Joi.string().required().allow(null, ''),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
