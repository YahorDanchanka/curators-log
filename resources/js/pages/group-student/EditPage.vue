<template>
  <ThePage padding>
    <Head :title="title" />
    <StudentForm v-model="student" @submit="onSubmit" />
  </ThePage>
</template>

<script setup lang="ts">
import StudentForm from '@/components/StudentForm.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { GroupStudentService } from '@/services'
import { GroupModel, StudentFormModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{ group: GroupModel; studentNumber: string; student: StudentModel }>()

const student = ref<StudentFormModel>(props.student)

const title = computed(() => `Редактирование ${props.student.initials}`)

function onSubmit() {
  onSave(GroupStudentService.update(props.group.id, props.student.id, student.value))
}
</script>
