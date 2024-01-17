<template>
  <q-form class="group-form form" greedy @submit.prevent="emit('submit')">
    <div class="form__body q-mb-md">
      <q-input
        type="number"
        label="Номер"
        min="1"
        max="255"
        v-model.number="modelValue.number"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'number')?.message]"
        hide-bottom-space
      >
        <template #hint>
          ПО-1<strong>{{ modelValue.number }}</strong>
        </template>
      </q-input>
      <q-select
        label="Специальность"
        option-label="name"
        option-value="id"
        v-model="modelValue.specialty_id"
        :options="props.specialties"
        :rules="[() => validated.error?.details.find((item) => item.context.key === 'specialty_id')?.message]"
        hide-bottom-space
        map-options
        emit-value
      />
      <div class="row items-center">
        <h2 class="col text-h6">Курсы обучения</h2>
        <div class="col text-right">
          <q-btn icon="add" size="sm" color="primary" round @click="addCourse" />
        </div>
      </div>
      <q-card v-for="(course, courseIndex) in modelValue.courses" :key="course.id">
        <q-card-section class="items-center" horizontal>
          <q-card-section class="col">
            <CourseForm
              class="group-form__course-form"
              component="div"
              v-model="modelValue.courses[courseIndex]"
              :curators="props.curators"
              :disabled-fields="['number']"
              without-submit-button
            />
          </q-card-section>
          <q-card-actions vertical class="justify-around">
            <q-btn color="negative" icon="delete" flat round @click="removeCourse(courseIndex)" />
          </q-card-actions>
        </q-card-section>
      </q-card>
    </div>
    <q-btn type="submit" class="form__btn" label="Сохранить" color="primary" />
  </q-form>
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { uid, useQuasar } from 'quasar'
import Joi from '@/Joi'
import { CuratorModel, GroupFormModel, SpecialtyTable } from '@/types'
import CourseForm from '@/components/CourseForm.vue'
import { difference } from '@/helpers'

const props = defineProps<{
  specialties: Pick<SpecialtyTable, 'id' | 'name'>[]
  curators: Pick<CuratorModel, 'id' | 'full_name'>[]
}>()
const modelValue = defineModel<GroupFormModel>({ required: true })
const emit = defineEmits(['submit'])
const $q = useQuasar()

const schema = Joi.object({
  number: Joi.number().integer().required().min(1).max(255),
  specialty_id: Joi.number().integer().required(),
})

const validated = computed(() => schema.validate(modelValue.value))
const usedCourseNumbers = computed(() => [...new Set(modelValue.value.courses.map((course) => course.number))])
const unusedCourseNumbers = computed(() => difference([1, 2, 3, 4], usedCourseNumbers.value))

function addCourse() {
  if (modelValue.value.courses.length >= 4) {
    $q.notify({ type: 'info', message: 'Группа может содержать максимум 4 курса обучения.' })
    return
  }

  modelValue.value.courses.push({
    id: uid(),
    number: unusedCourseNumbers.value[0] ? unusedCourseNumbers.value[0] : 1,
    start_education: null,
    end_education: null,
    curator_id: null,
  })
}

function removeCourse(courseIndex: number) {
  if (modelValue.value.courses.length <= 1) {
    $q.notify({ type: 'info', message: 'Группа должна содержать минимум 1 курс обучения.' })
    return
  }

  modelValue.value.courses.splice(courseIndex, 1)
}
</script>

<style lang="sass" scoped>
.group-form__course-form
  :deep(.form__body)
    grid-template-columns: repeat(4, minmax(0, 1fr))
</style>
