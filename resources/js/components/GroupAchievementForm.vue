<template>
  <component class="form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-input
        class="form__control"
        type="textarea"
        label="Содержимое"
        v-model="modelValue.content"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'content')?.message]"
        hide-bottom-space
      />
      <q-select
        label="Семестр"
        v-model="modelValue.semester"
        :options="[
          { label: '1 семестр', value: '1' },
          { label: '2 семестр', value: '2' },
        ]"
        emit-value
        map-options
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import Joi from '@/Joi'
import { GroupAchievementFormModel } from '@/types'
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

const modelValue = defineModel<GroupAchievementFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  content: Joi.string().required(),
  semester: Joi.string().required().valid('1', '2'),
})

const validated = computed(() => schema.validate(modelValue.value))
</script>
