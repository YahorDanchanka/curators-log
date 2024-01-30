<template>
  <q-page padding>
    <Head :title="`Родственники ${props.student.initials}`" />
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
</script>
