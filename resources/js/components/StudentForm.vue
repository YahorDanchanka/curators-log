<template>
  <component class="student-form form" :is="props.component" greedy @submit.prevent="emit('submit')">
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
      <q-select
        class="form__control"
        label="Пол"
        v-model="modelValue.sex"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'sex')?.message]"
        :options="['мужской', 'женский']"
        hide-bottom-space
      />
      <q-input
        type="date"
        class="form__control"
        label="Дата рождения"
        v-model="modelValue.birthday"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'birthday')?.message]"
        hide-bottom-space
      />
      <AutocompleteInput
        class="form__control"
        label="Гражданство"
        v-model="modelValue.citizenship"
        :options="['Республика Беларусь', 'Российская Федерация', 'Украина']"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'citizenship')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Домашний телефон"
        v-model="modelValue.home_phone"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'home_phone')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Телефон"
        v-model="modelValue.phone"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'phone')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Учреждение образования"
        v-model="modelValue.educational_institution"
        :rules="[
          () => validated.error?.details.find((item: any) => item.context.key === 'educational_institution')?.message,
        ]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Отношения учащегося с членами семьи, попечителем и др."
        v-model="modelValue.social_conditions"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'social_conditions')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Увлечения"
        v-model="modelValue.hobbies"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'hobbies')?.message]"
        hide-bottom-space
      />
      <q-input
        class="form__control"
        label="Другая информация"
        v-model="modelValue.other_details"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'other_details')?.message]"
        hide-bottom-space
      />
      <q-input
        type="date"
        class="form__control"
        label="Дата справки"
        v-model="modelValue.medical_certificate_date"
        :rules="[
          () => validated.error?.details.find((item: any) => item.context.key === 'medical_certificate_date')?.message,
        ]"
        hide-bottom-space
      />
      <AutocompleteInput
        class="form__control"
        label="Группа здоровья"
        v-model="modelValue.health"
        :options="['Основная', 'Подготовительная', 'Специальная медицинская']"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'health')?.message]"
        hide-bottom-space
      />
      <q-select
        class="form__control"
        label="Основа"
        v-model="modelValue.apprenticeship"
        :rules="[() => validated.error?.details.find((item: any) => item.context.key === 'apprenticeship')?.message]"
        :options="['Бюджет', 'Внебюджет']"
        hide-bottom-space
      />
      <q-uploader
        class="form__control full-width"
        label="Изображение"
        accept=".jpg, image/*"
        max-file-size="1048576"
        @rejected="onPhotoUploadRejected"
        @added="onUploaded"
        @removed="onRemoved"
      >
        <template v-slot:header="scope">
          <div class="row no-wrap items-center q-pa-sm q-gutter-xs">
            <q-spinner v-if="scope.isUploading" class="q-uploader__spinner" />

            <div class="col">
              <div class="q-uploader__title">Изображение</div>
              <div class="q-uploader__subtitle">{{ scope.uploadSizeLabel }}</div>
            </div>

            <q-btn
              v-if="modelValue.image !== null && modelValue.image_url"
              type="a"
              icon="hide_image"
              @click="removeImage(scope)"
              round
              dense
              flat
            >
              <q-tooltip>Удалить установленное изображение</q-tooltip>
            </q-btn>

            <q-btn v-if="scope.canAddFiles" type="a" icon="add_box" @click="scope.pickFiles" round dense flat>
              <q-uploader-add-trigger />
              <q-tooltip>Выбрать</q-tooltip>
            </q-btn>
          </div>
        </template>
      </q-uploader>
      <q-expansion-item class="form__control" label="Домашний адрес" v-model="showedForms.addressForm">
        <AddressForm v-if="modelValue.address" component="div" v-model="modelValue.address" without-submit-button />
      </q-expansion-item>
      <q-expansion-item class="form__control" v-model="showedForms.studyAddress">
        <template v-slot:header>
          <q-item-section class="col">
            Место проживания в период обучения ({{
              isDormitory ? 'общежитие' : isOtherStudyAddress ? 'другой' : 'там же'
            }})
          </q-item-section>
          <q-item-section class="items-start col-auto">
            <q-checkbox label="Общежитие" dense :modelValue="isDormitory" @update:modelValue="setDormitory" />
          </q-item-section>
        </template>
        <AddressForm
          v-if="modelValue.study_address"
          component="div"
          v-model="modelValue.study_address"
          without-submit-button
        />
      </q-expansion-item>
      <q-expansion-item
        class="form__control"
        label="Паспортные данные"
        style="grid-column: span 2"
        v-model="showedForms.passportForm"
      >
        <PassportForm
          class="student-form__passport-form"
          v-if="modelValue.passport"
          component="div"
          v-model="modelValue.passport"
          without-submit-button
        />
      </q-expansion-item>
    </div>
    <q-btn v-if="!withoutSubmitButton" type="submit" class="form__btn q-mt-md" label="Сохранить" color="primary" />
  </component>
</template>

<script setup lang="ts">
import { Component as TComponent, computed, reactive, watch } from 'vue'
import { QForm, QUploader, useQuasar } from 'quasar'
import Joi from '@/Joi'
import { StudentFormModel } from '@/types'
import AddressForm from '@/components/AddressForm.vue'
import PassportForm from '@/components/PassportForm.vue'
import AutocompleteInput from '@/components/AutocompleteInput.vue'
import { cloneDeep, isEqual, pick } from 'lodash'

const props = withDefaults(
  defineProps<{
    withoutSubmitButton?: boolean
    component?: TComponent | string
  }>(),
  {
    component: QForm,
  }
)

const $q = useQuasar()
const modelValue = defineModel<StudentFormModel>({ required: true })
const emit = defineEmits(['submit'])

const studyAddress = {
  type: 'Город',
  residence: '',
  street: '',
  apartment_number: '',
  region_id: 40,
  district_id: 44,
}

const dormitoryObject = {
  type: 'Город',
  residence: 'Гомель',
  street: 'Речицкая',
  house_number: '4',
  region_id: 40,
  district_id: 44,
}

const schema = Joi.object({
  surname: Joi.string().required().max(255),
  name: Joi.string().required().max(255),
  patronymic: Joi.string().allow(null, '').max(255),
  sex: Joi.string().required().allow('мужской', 'женский'),
  birthday: Joi.date().max('now').allow(null, ''),
  citizenship: Joi.string().allow(null, '').max(255),
  home_phone: Joi.string().allow(null, '').max(255),
  phone: Joi.string().allow(null, '').max(255),
  educational_institution: Joi.string().allow(null, '').max(255),
  social_conditions: Joi.string().allow(null, '').max(255),
  hobbies: Joi.string().allow(null, '').max(255),
  other_details: Joi.string().allow(null, '').max(255),
  medical_certificate_date: Joi.date().allow(null, '').max('now'),
  health: Joi.string().allow(null, '').max(255),
  apprenticeship: Joi.string().allow(null, '').max(255),
})

const showedForms = reactive({
  addressForm: !!modelValue.value.address,
  studyAddress: !!modelValue.value.study_address,
  passportForm: !!modelValue.value.passport,
})

const isDormitory = computed(
  () =>
    !!modelValue.value.study_address &&
    isEqual(
      pick(modelValue.value.study_address, ['type', 'residence', 'street', 'house_number', 'region_id', 'district_id']),
      dormitoryObject
    )
)

const isOtherStudyAddress = computed(
  () => !isDormitory.value && !!modelValue.value.study_address && typeof modelValue.value.study_address === 'object'
)

const validated = computed(() => schema.validate(modelValue.value))

function onUploaded(files: readonly any[]) {
  if (files[0]) {
    modelValue.value.image = files[0]
  }
}

function onRemoved() {
  delete modelValue.value.image
}

function removeImage(scope: QUploader) {
  scope.removeQueuedFiles()
  modelValue.value.image = null
}

function setDormitory() {
  if (isDormitory.value) {
    modelValue.value.study_address = cloneDeep(studyAddress)
    return
  }

  modelValue.value.study_address = cloneDeep({
    ...dormitoryObject,
    apartment_number: '',
  })

  showedForms.studyAddress = true
}

function setStudyAddress(value: boolean) {
  modelValue.value.study_address = value ? cloneDeep(studyAddress) : undefined
}

function onPhotoUploadRejected() {
  $q.notify({
    type: 'negative',
    message: 'Загружаемый файл весит более 1 МБ.',
  })
}

watch(
  () => showedForms.addressForm,
  () => {
    if (showedForms.addressForm) {
      modelValue.value.address = cloneDeep(studyAddress)
    } else {
      delete modelValue.value.address
    }
  }
)

watch(
  () => showedForms.studyAddress,
  () => {
    if (showedForms.studyAddress) {
      if (!isDormitory.value) {
        setStudyAddress(true)
      }
    } else {
      delete modelValue.value.study_address
    }
  }
)

watch(
  () => showedForms.passportForm,
  () => {
    if (showedForms.passportForm) {
      modelValue.value.passport = {
        series: 'HB',
        number: '',
        district_department: '',
        issue_date: '',
      }
    } else {
      delete modelValue.value.passport
    }
  }
)
</script>

<style lang="sass" scoped>
.student-form
  .form__body
    grid-template-columns: repeat(2, minmax(0, 1fr))

.student-form__passport-form
  :deep(.form__body)
    grid-template-columns: repeat(2, minmax(0, 1fr))
</style>
