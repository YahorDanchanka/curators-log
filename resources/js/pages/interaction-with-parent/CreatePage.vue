<template>
  <ThePage padding>
    <Head title="Содержание взаимодействия с родителями (другими законными представителями) учащихся" />
    <InteractionWithParentForm
      v-model="modelValue"
      :min-date="new Date(props.group.first_course.start_education)"
      :max-date="new Date(props.group.last_course.end_education)"
      @submit="onSave(InteractionWithParentService.create(props.group.id, modelValue), 'create')"
    />
  </ThePage>
</template>

<script lang="ts" setup>
import InteractionWithParentForm from '@/components/InteractionWithParentForm.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { InteractionWithParentService } from '@/services'
import { GroupModel, InteractionWithParentFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { ref } from 'vue'

const props = defineProps<{
  group: Required<GroupModel, 'interaction_with_parents' | 'first_course' | 'last_course'>
}>()

const modelValue = ref<InteractionWithParentFormModel>({ date: '', content: '', result: '' })
</script>
