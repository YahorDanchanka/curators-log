<template>
  <ThePage class="page_flex" padding use-height>
    <Head :title="title" />
    <StudentTable
      class="page__table"
      :title="title"
      :students="props.group.students"
      @create="router.get(route('groups.students.create', { group: props.group.id }))"
      @show="
        (student, studentNumber) =>
          router.get(route('groups.students.show', { group: props.group.id, student: studentNumber }))
      "
      @edit="
        (student, studentNumber) =>
          router.get(route('groups.students.edit', { group: props.group.id, student: studentNumber }))
      "
      @delete="(student) => onDeleteConfirm(student.id)"
    />
    <q-dialog v-model="isPrintingDialogVisible">
      <q-card class="full-width">
        <q-card-section>
          <div class="text-h6">Выберите столбцы для отображения</div>
        </q-card-section>
        <q-card-section>
          <q-select label="Столбцы" v-model="printingColumns" :options="columns" emit-value map-options multiple />
        </q-card-section>
        <q-card-actions vertical>
          <q-btn color="primary" label="Печать" @click="printStudentList" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </ThePage>
</template>

<script setup lang="ts">
import StudentTable from '@/components/StudentTable.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, onSave } from '@/helpers'
import { GroupStudentService } from '@/services'
import { GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { Required } from 'utility-types'
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'
import { useEventListener } from '@vueuse/core'

const props = defineProps<{
  group: Required<GroupModel, 'name' | 'students'>
  columns: { value: string; label: string }[]
}>()
const $q = useQuasar()
const { t } = useI18n()

const isPrintingDialogVisible = ref(false)
const printingColumns = ref(['number', 'initials', 'birthday'])

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

useEventListener(document, 'print', () => {
  isPrintingDialogVisible.value = true
})

function printStudentList() {
  downloadFile(
    route('groups.students.printStudentList', {
      group: props.group.id,
      columns: printingColumns.value,
    })
  )
}
</script>
