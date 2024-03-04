<template>
  <component class="passport-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <AutocompleteInput
        class="form__control"
        label="Серия паспорта"
        v-model="modelValue.series"
        :options="['AB', 'BM', 'HB', 'KH', 'MP', 'MC', 'KB', 'PP', 'SP', 'DP']"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'series')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        mask="#######"
        label="Номер паспорта"
        v-model="modelValue.number"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'number')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Идентификационный номер"
        v-model="modelValue.id_number"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'id_number')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="РОВД"
        v-model="modelValue.district_department"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'district_department')?.message]"
        hide-bottom-space
      />
      <q-input
        type="date"
        class="form__control"
        label="Дата выдачи"
        v-model="modelValue.issue_date"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'issue_date')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import AutocompleteInput from '@/components/AutocompleteInput.vue'
import { PassportFormModel } from '@/types'
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

const modelValue = defineModel<PassportFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  series: Joi.string().required().max(2),
  number: Joi.string().required().length(7),
  id_number: Joi.string().required(),
  district_department: Joi.string().required().max(255),
  issue_date: Joi.date().required().max('now'),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
