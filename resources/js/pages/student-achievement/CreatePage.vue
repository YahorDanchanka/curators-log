<template>
  <q-page padding>
    <Head title="Поощрения учащегося" />
    <StudentAchievementForm
      v-model="modelValue"
      @submit="onSave(StudentAchievementService.create(props.group.id, props.studentNumber, modelValue), 'create')"
    />
  </q-page>
</template>

<script lang="ts" setup>
import StudentAchievementForm from '@/components/StudentAchievementForm.vue'
import { onSave } from '@/helpers'
import { StudentAchievementService } from '@/services'
import { GroupModel, StudentAchievementFormModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string }>()

const modelValue = ref<StudentAchievementFormModel>({ date: '', reason: '', prize: '' })
</script>
