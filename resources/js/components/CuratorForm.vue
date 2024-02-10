<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        label="Фамилия"
        v-model="modelValue.surname"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'surname')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Имя"
        v-model="modelValue.name"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'name')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Отчество"
        v-model="modelValue.patronymic"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'patronymic')?.message]"
        hide-bottom-space
      />
      <AuthForm
        component="div"
        v-model="modelValue"
        :schema="
          isUpdate
            ? { login: Joi.string().required().max(255), password: Joi.string().allow(null, '').min(8).max(255) }
            : undefined
        "
        without-submit-button
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import AuthForm from '@/components/AuthForm.vue'
import { CuratorFormModel } from '@/types'
import { QForm } from 'quasar'
import { Component as TComponent, computed } from 'vue'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: TComponent | string
    isUpdate?: boolean
  }>(),
  {
    component: QForm,
  }
)

const modelValue = defineModel<CuratorFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  surname: Joi.string().required().max(255),
  name: Joi.string().required().max(255),
  patronymic: Joi.string().required().allow(null, '').max(255),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
