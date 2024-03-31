<template>
  <ThePage class="page_flex" padding use-height>
    <Head :title="title" />
    <StudentTable
      class="page__table"
      :title="title"
      :students="props.group.students"
      @create="router.get(route('groups.students.create', { group: props.group.id }))"
      @show="
        (student, index) => router.get(route('groups.students.show', { group: props.group.id, student: index + 1 }))
      "
      @edit="
        (student, index) => router.get(route('groups.students.edit', { group: props.group.id, student: index + 1 }))
      "
      @delete="(student) => onDeleteConfirm(student.id)"
    />
  </ThePage>
</template>

<script setup lang="ts">
import StudentTable from '@/components/StudentTable.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { GroupStudentService } from '@/services'
import { GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { Required } from 'utility-types'
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ group: Required<GroupModel, 'name' | 'students'> }>()
const $q = useQuasar()
const { t } = useI18n()

const title = computed(() => `Список учащихся учебной группы ${props.group.name}`)

function onDeleteConfirm(studentId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    onSave(GroupStudentService.delete(props.group.id, studentId), 'delete')
  })
}
</script>
