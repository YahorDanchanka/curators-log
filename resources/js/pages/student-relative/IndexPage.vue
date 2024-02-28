<template>
  <q-page padding>
    <Head :title="`Родственники ${props.student.initials}`" />
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el class="cursor-pointer" label="Группы" @click="router.get(route('groups.index'))" />
      <q-breadcrumbs-el :label="props.group.name!" style="color: black" />
      <q-breadcrumbs-el
        class="cursor-pointer"
        label="Список учащихся"
        @click="router.get(route('groups.students.index', { group: props.group.id }))"
      />
      <q-breadcrumbs-el :label="props.student.full_name" style="color: black" />
      <q-breadcrumbs-el label="Родственники" />
    </q-breadcrumbs>
    <AdultRelativeTable
      class="q-mb-md"
      :relatives="props.student.adult_relatives"
      @create="onCreate"
      @edit="onEdit"
      @delete="onDelete"
    />
    <MinorRelativeTable
      :relatives="props.student.minor_relatives"
      @create="onCreate('minor')"
      @edit="(relative) => onEdit(relative, 'minor')"
      @delete="onDelete"
    />
  </q-page>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import route from 'ziggy-js'
import { GroupModel, RelativeModel, StudentModel } from '@/types'
import AdultRelativeTable from '@/components/AdultRelativeTable.vue'
import MinorRelativeTable from '@/components/MinorRelativeTable.vue'
import { StudentRelativeService } from '@/services'
import { downloadFile } from '@/helpers'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string }>()

function onCreate(type: string = 'adult') {
  router.get(route('groups.students.relatives.create', { group: props.group.id, student: props.studentNumber, type }))
}

function onEdit(relative: RelativeModel, type: string = 'adult') {
  router.get(
    route('groups.students.relatives.edit', {
      group: props.group.id,
      student: props.studentNumber,
      relative: relative.id,
      type,
    })
  )
}

function onDelete(relative: RelativeModel) {
  StudentRelativeService.delete(props.group.id, props.studentNumber, relative.id)
}

document.addEventListener('print', () => {
  downloadFile(route('groups.students.relatives.print', { group: props.group.id, student_number: props.studentNumber }))
})
</script>
