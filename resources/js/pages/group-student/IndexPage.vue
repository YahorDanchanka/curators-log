<template>
  <q-page padding>
    <Head :title="title" />
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el class="cursor-pointer" label="Группы" @click="router.get(route('groups.index'))" />
      <q-breadcrumbs-el :label="props.group.name!" style="color: black" />
      <q-breadcrumbs-el label="Список учащихся" />
    </q-breadcrumbs>
    <StudentTable
      :title="title"
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
import StudentTable from '@/components/StudentTable.vue'
import { onSave } from '@/helpers'
import { GroupStudentService } from '@/services'
import { GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { computed } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ group: Required<GroupModel, 'name' | 'students'> }>()

const title = computed(() => `Список учащихся учебной группы ${props.group.name}`)
</script>
