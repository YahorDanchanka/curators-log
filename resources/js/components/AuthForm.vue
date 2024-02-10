<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        label="Логин"
        v-model="modelValue.login"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'login')?.message]"
        hide-bottom-space
      />
      <q-input
        type="password"
        class="form__control"
        label="Пароль"
        v-model="modelValue.password"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'password')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Войти" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { AuthFormModel } from '@/types'
import { QForm } from 'quasar'
import { Component as TComponent, computed } from 'vue'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: TComponent | string
    schema?: any
  }>(),
  {
    component: QForm,
    schema: () => ({
      login: Joi.string().required().max(255),
      password: Joi.string().required().min(8).max(255),
    }),
  }
)

const modelValue = defineModel<AuthFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object(props.schema)

const validated = computed(() => schema.validate(modelValue.value))
</script>
