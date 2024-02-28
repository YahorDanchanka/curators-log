<template>
  <q-page padding>
    <Head :title />
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el class="cursor-pointer" label="Группы" @click="router.get(route('groups.index'))" />
      <q-breadcrumbs-el :label="props.group.name!" style="color: black" />
      <q-breadcrumbs-el
        class="cursor-pointer"
        label="Список учащихся"
        @click="router.get(route('groups.students.index', { group: props.group.id }))"
      />
      <q-breadcrumbs-el label="Редактирование" />
    </q-breadcrumbs>
    <StudentForm v-model="student" @submit="onSubmit" />
  </q-page>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { GroupModel, StudentFormModel, StudentModel } from '@/types'
import StudentForm from '@/components/StudentForm.vue'
import { GroupStudentService } from '@/services'
import { onSave } from '@/helpers'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; studentNumber: string; student: StudentModel }>()

const student = ref<StudentFormModel>(props.student)

const title = computed(() => `Редактирование ${props.student.initials}`)

function onSubmit() {
  onSave(GroupStudentService.update(props.group.id, props.student.id, student.value))
}
</script>
