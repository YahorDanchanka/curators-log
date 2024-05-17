<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        label="Логин"
        v-model="modelValue.login"
        :rules="getValidationRules(validated, 'login')"
        hide-bottom-space
      />
      <q-input
        type="password"
        class="form__control"
        label="Пароль"
        v-model="modelValue.password"
        :rules="getValidationRules(validated, 'password')"
        hide-bottom-space
      />
      <q-select
        class="form__control"
        label="Роль"
        v-model="modelValue.role"
        :options="[
          { label: t('any.admin'), value: 'admin' },
          { label: t('any.curator'), value: 'curator' },
          { label: t('any.psychologist'), value: 'psychologist' },
        ]"
        :rules="getValidationRules(validated, 'role')"
        emit-value
        map-options
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { getValidationRules } from '@/helpers'
import { UserFormModel } from '@/types'
import { QForm } from 'quasar'
import { Component as TComponent, computed } from 'vue'
import { useI18n } from 'vue-i18n'

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
const { t } = useI18n()

const modelValue = defineModel<UserFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  login: Joi.string().required().max(255),
  password: props.isUpdate ? Joi.string().allow('', null).min(8).max(255) : Joi.string().required().min(8).max(255),
  role: Joi.string()
    .required()
    .valid(...(props.isUpdate ? ['admin', 'curator', 'psychologist'] : ['admin', 'psychologist']))
    .messages({ 'any.only': 'Для создания куратора используйте соответствующую форму.' }),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
