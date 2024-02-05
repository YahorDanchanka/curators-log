<template>
  <q-page padding>
    <Head :title />
    <StudentForm v-model="student" @submit="onSubmit" />
  </q-page>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { GroupModel, StudentFormModel } from '@/types'
import StudentForm from '@/components/StudentForm.vue'
import { GroupStudentService } from '@/services'
import { onSave } from '@/helpers'

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
