<template>
  <ThePage padding>
    <Head :title="`Достижения учебной группы ${props.course.group_name}`" />
    <GroupAchievementForm
      v-model="modelValue"
      @submit="onSave(GroupAchievementService.create(props.group.id, props.course.number, modelValue), 'create')"
    />
  </ThePage>
</template>

<script lang="ts" setup>
import GroupAchievementForm from '@/components/GroupAchievementForm.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { GroupAchievementService } from '@/services'
import { CourseModel, GroupAchievementFormModel, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; course: CourseModel }>()

const modelValue = ref<GroupAchievementFormModel>({ content: '', semester: '1' })
</script>
