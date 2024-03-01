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
import { GroupModel, StudentFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const props = defineProps<{ group: GroupModel }>()

const student = ref<StudentFormModel>({
  surname: '',
  name: '',
  sex: 'мужской',
  citizenship: 'Республика Беларусь',
  apprenticeship: 'Внебюджет',
})

const title = computed(() => `Создать учащегося учебной группы ${props.group.name}`)

function onSubmit() {
  onSave(GroupStudentService.create(props.group.id, student.value), 'create')
}
</script>
