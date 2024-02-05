<template>
  <q-page padding>
    <Head :title />
    <StudentTable
      :title
      :students="props.group.students"
      @create="router.get(route('groups.students.create', { group: props.group.id }))"
      @show="
        (student, index) => router.get(route('groups.students.show', { group: props.group.id, student: index + 1 }))
      "
      @edit="
        (student, index) => router.get(route('groups.students.edit', { group: props.group.id, student: index + 1 }))
      "
      @delete="(student) => onSave(GroupStudentService.delete(props.group.id, student.id), 'delete')"
    />
  </q-page>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import route from 'ziggy-js'
import { Head, router } from '@inertiajs/vue3'
import { GroupModel } from '@/types'
import StudentTable from '@/components/StudentTable.vue'
import { GroupStudentService } from '@/services'
import { onSave } from '@/helpers'

const props = defineProps<{ group: GroupModel }>()

const title = computed(() => `Список учащихся учебной группы ${props.group.name}`)
</script>
