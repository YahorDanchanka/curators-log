<template>
  <q-page padding>
    <Head title="Отчислить учащегося" />
    <ExpulsionForm
      v-model="expulsion"
      :students="props.group.students"
      :course="props.course"
      @submit="ExpulsionService.update(props.group.id, props.course.number, props.expulsion.id, expulsion)"
    />
  </q-page>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import ExpulsionForm from '@/components/ExpulsionForm.vue'
import { CourseModel, ExpulsionFormModel, ExpulsionTable, GroupModel } from '@/types'
import { ExpulsionService } from '@/services/ExpulsionService'

const props = defineProps<{ group: GroupModel; course: CourseModel; expulsion: ExpulsionTable }>()

const expulsion = reactive<ExpulsionFormModel>(props.expulsion)
</script>
