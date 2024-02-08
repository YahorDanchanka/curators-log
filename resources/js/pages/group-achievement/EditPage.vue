<template>
  <q-page padding>
    <Head :title="`Достижения учебной группы ${props.course.group_name}`" />
    <GroupAchievementForm
      v-model="modelValue"
      @submit="
        onSave(
          GroupAchievementService.update(props.group.id, props.course.number, props.achievement.id, modelValue),
          'update'
        )
      "
    />
  </q-page>
</template>

<script lang="ts" setup>
import GroupAchievementForm from '@/components/GroupAchievementForm.vue'
import { onSave } from '@/helpers'
import { GroupAchievementService } from '@/services'
import { CourseModel, GroupAchievementFormModel, GroupAchievementTable, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; course: CourseModel; achievement: GroupAchievementTable }>()

const modelValue = ref<GroupAchievementFormModel>(props.achievement)
</script>
