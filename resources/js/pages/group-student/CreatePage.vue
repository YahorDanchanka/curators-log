<template>
  <q-page padding>
    <Head :title="title" />
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el class="cursor-pointer" label="Группы" @click="router.get(route('groups.index'))" />
      <q-breadcrumbs-el :label="props.group.name!" style="color: black" />
      <q-breadcrumbs-el
        class="cursor-pointer"
        label="Список учащихся"
        @click="router.get(route('groups.students.index', { group: props.group.id }))"
      />
      <q-breadcrumbs-el label="Создание" />
    </q-breadcrumbs>
    <StudentForm v-model="student" @submit="onSubmit" />
  </q-page>
</template>

<script setup lang="ts">
import StudentForm from '@/components/StudentForm.vue'
import { onSave } from '@/helpers'
import { GroupStudentService } from '@/services'
import { GroupModel, StudentFormModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import route from 'ziggy-js'

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
