<template>
  <q-page padding>
    <Head :title />
    <StudentForm v-model="student" @submit="onSubmit" />
  </q-page>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { GroupModel, StudentFormModel, StudentModel } from '@/types'
import StudentForm from '@/components/StudentForm.vue'
import { GroupStudentService } from '@/services'
import { onSave } from '@/helpers'

const props = defineProps<{ group: GroupModel; studentNumber: string; student: StudentModel }>()

const student = ref<StudentFormModel>(props.student)

const title = computed(() => `Редактирование ${props.student.initials}`)

function onSubmit() {
  onSave(GroupStudentService.update(props.group.id, props.student.id, student.value))
}
</script>
