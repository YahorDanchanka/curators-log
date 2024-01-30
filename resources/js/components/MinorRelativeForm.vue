<template>
  <component class="relative-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        label="Фамилия"
        v-model="modelValue.surname"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'surname')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Имя"
        v-model="modelValue.name"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'name')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Отчество"
        v-model="modelValue.patronymic"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'patronymic')?.message]"
        hide-bottom-space
      />
      <q-select
        class="form__control"
        label="Пол"
        v-model="modelValue.sex"
        :options="['мужской', 'женский']"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'sex')?.message]"
        emit-value
        map-options
        hide-bottom-space
      />
      <q-input
        type="date"
        label="Дата рождения"
        v-model="modelValue.birthday"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'birthday')?.message]"
        hide-bottom-space
      />
      <q-input
        label="Учреждение образования"
        v-model="modelValue.educational_institution"
        :rules="[
          () => validated.error?.details.find((item) => item.context.key === 'educational_institution')?.message,
        ]"
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
import { RelativeFormModel } from '@/types'
import AddressForm from '@/components/AddressForm.vue'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: object | string
  }>(),
  {
    component: QForm,
  }
)

const modelValue = defineModel<RelativeFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  surname: Joi.string().required().max(255),
  name: Joi.string().required(),
  patronymic: Joi.string().required().allow(null, ''),
  sex: Joi.string().required().allow('мужской', 'женский'),
  birthday: Joi.date().required().max('now'),
  educational_institution: Joi.string().required().allow(null, ''),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>

<style lang="sass" scoped>
.relative-form .form__body
  grid-template-columns: repeat(2, minmax(0, 1fr))

.relative-form__address-form
  grid-column: span 2

  :deep(.form__body)
    grid-template-columns: repeat(2, minmax(0, 1fr))

    .form__control:last-child
      grid-column: span 2
</style>
