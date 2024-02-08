<template>
  <q-page padding>
    <Head title="Поощрения учащегося" />
    <StudentAchievementForm
      v-model="modelValue"
      @submit="
        onSave(
          StudentAchievementService.update(props.group.id, props.studentNumber, props.achievement.id, modelValue),
          'update'
        )
      "
    />
  </q-page>
</template>

<script lang="ts" setup>
import StudentAchievementForm from '@/components/StudentAchievementForm.vue'
import { onSave } from '@/helpers'
import { StudentAchievementService } from '@/services'
import { GroupModel, StudentAchievementFormModel, StudentAchievementTable, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{
  group: GroupModel
  student: StudentModel
  studentNumber: string
  achievement: StudentAchievementTable
}>()

const modelValue = ref<StudentAchievementFormModel>(props.achievement)
</script>
