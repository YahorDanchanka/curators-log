<template>
  <component class="address-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
    <div class="form__body">
      <q-select
        class="form__control"
        label="Область"
        v-model="modelValue.region_id"
        :options="regionOptions"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'region_id')?.message]"
        emit-value
        map-options
        hide-bottom-space
      />
      <q-select
        class="form__control"
        label="Район"
        v-model="modelValue.district_id"
        :options="districtOptions"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'district_id')?.message]"
        emit-value
        map-options
        hide-bottom-space
      />
      <q-select
        class="form__control"
        label="Тип места жительства"
        v-model="modelValue.type"
        :options="['Город', 'Деревня', 'Посёлок', 'Хутор', 'Агрогородок', 'Городской поселок']"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'type')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Населенный пункт"
        v-model="modelValue.residence"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'residence')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Улица"
        v-model="modelValue.street"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'street')?.message]"
        hide-bottom-space
      />
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import { computed, watch } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { QForm } from 'quasar'
import Joi from '@/Joi'
import { AddressFormModel, AdministrativeDivisionTable } from '@/types'
import { AdministrativeDivisionRepository } from '@/repositories'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: object | string
  }>(),
  {
    component: QForm,
  }
)

const page = usePage<{ administrativeDivisions: AdministrativeDivisionTable[] }>()
const modelValue = defineModel<AddressFormModel>({ required: true })
const emit = defineEmits(['submit'])

const schema = Joi.object({
  region_id: Joi.number().required(),
  district_id: Joi.number().required(),
  type: Joi.string().required().max(255),
  residence: Joi.string().required().max(255),
  street: Joi.string().required().max(255),
})

const validated = computed(() => schema.validate(modelValue.value))

const administrativeDivisionRepository = computed(
  () => new AdministrativeDivisionRepository(page.props.administrativeDivisions)
)

const regionOptions = computed(() => administrativeDivisionRepository.value.getRegions())
const districtOptions = computed(() => administrativeDivisionRepository.value.getDistricts(modelValue.value.region_id))

/** Сбрасываем район, если меняется регион */
watch(
  () => modelValue.value.region_id,
  () => {
    modelValue.value.district_id = districtOptions.value[0].value
  }
)
</script>
