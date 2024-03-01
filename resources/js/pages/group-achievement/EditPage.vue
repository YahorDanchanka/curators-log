<template>
  <ThePage padding>
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
  </ThePage>
</template>

<script lang="ts" setup>
import GroupAchievementForm from '@/components/GroupAchievementForm.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { GroupAchievementService } from '@/services'
import { CourseModel, GroupAchievementFormModel, GroupAchievementTable, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; course: CourseModel; achievement: GroupAchievementTable }>()

const modelValue = ref<GroupAchievementFormModel>(props.achievement)
</script>
