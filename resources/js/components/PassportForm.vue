<template>
  <component class="passport-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-select
        class="form__control"
        label="Серия паспорта"
        v-model="modelValue.series"
        :options="['AB', 'BM', 'HB', 'KH', 'MP', 'MC', 'KB', 'PP', 'SP', 'DP']"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'series')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        mask="#######"
        label="Номер паспорта / личный номер"
        v-model="modelValue.number"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'number')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="РОВД"
        v-model="modelValue.district_department"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'district_department')?.message]"
        hide-bottom-space
      />
      <q-input
        type="date"
        class="form__control"
        label="Дата выдачи"
        v-model="modelValue.issue_date"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'issue_date')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { QForm } from 'quasar'
import Joi from '@/Joi'
import { PassportFormModel } from '@/types'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: object | string
  }>(),
  {
    component: QForm,
  }
)

const modelValue = defineModel<PassportFormModel>({ required: true })
const emit = defineEmits(['emit'])

const schema = Joi.object({
  series: Joi.string().required().max(2),
  number: Joi.string().required().length(7),
  district_department: Joi.string().required().max(255),
  issue_date: Joi.date().required().max('now'),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
