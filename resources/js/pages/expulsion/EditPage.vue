<template>
  <ThePage padding>
    <Head title="Отчислить учащегося" />
    <ExpulsionForm
      v-model="expulsion"
      :students="props.group.students"
      :course="props.course"
      @submit="ExpulsionService.update(props.group.id, props.course.number, props.expulsion.id, expulsion)"
    />
  </ThePage>
</template>

<script setup lang="ts">
import ExpulsionForm from '@/components/ExpulsionForm.vue'
import ThePage from '@/components/ThePage.vue'
import { ExpulsionService } from '@/services/ExpulsionService'
import { CourseModel, ExpulsionFormModel, ExpulsionTable, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { reactive } from 'vue'

const props = defineProps<{ group: GroupModel; course: CourseModel; expulsion: ExpulsionTable }>()

const expulsion = reactive<ExpulsionFormModel>(props.expulsion)
</script>
