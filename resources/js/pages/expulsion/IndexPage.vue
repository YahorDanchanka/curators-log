<template>
  <ThePage padding>
    <Head :title="title" />
    <CourseTitle :course-number="props.course.number">
      {{ title }}
      <q-btn
        color="primary"
        icon="add"
        size="sm"
        title="Добавить"
        round
        @click="
          router.get(route('groups.courses.expulsions.create', { group: props.group.id, course: props.course.number }))
        "
      />
    </CourseTitle>
    <q-markup-table separator="cell" wrap-cells>
      <thead>
        <tr>
          <th rowspan="2">Фамилия, имя, отчество</th>
          <th rowspan="2" style="width: 130px">Дата отчисления</th>
          <th colspan="3">Причины отчисления</th>
          <th rowspan="2">Действия</th>
        </tr>
        <tr>
          <th class="border-l" style="width: 150px">по инициативе учащегося</th>
          <th style="width: 150px">по инициативе учреждения образования</th>
          <th>другие причины (указать)</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="expulsion in props.course.expulsions">
          <td>{{ findStudent(expulsion.student_id)?.initials }}</td>
          <td>{{ expulsion.date }}</td>
          <td class="text-center">{{ expulsion.initiator === 'student' ? '+' : '' }}</td>
          <td class="text-center">{{ expulsion.initiator === 'college' ? '+' : '' }}</td>
          <td>{{ expulsion.reason }}</td>
          <td class="text-center">
            <q-btn
              class="q-mr-sm"
              title="Редактировать"
              color="primary"
              icon="edit"
              size="sm"
              round
              @click="
                router.get(
                  route('groups.courses.expulsions.edit', {
                    group: props.group.id,
                    course: props.course.number,
                    expulsion: expulsion.id,
                  })
                )
              "
            />
            <q-btn
              color="negative"
              title="Удалить"
              icon="delete"
              size="sm"
              round
              @click="onDeleteConfirm(expulsion.id)"
            />
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script lang="ts" setup>
import CourseTitle from '@/components/CourseTitle.vue'
import ThePage from '@/components/ThePage.vue'
import { ExpulsionService } from '@/services/ExpulsionService'
import { BaseTable, CourseModel, GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'
import { useEventListener } from '@vueuse/core'
import { downloadFile } from '@/helpers'

const props = defineProps<{ group: GroupModel; course: CourseModel }>()
const $q = useQuasar()
const { t } = useI18n()

const title = 'Отчисления за период обучения'

function findStudent(id: BaseTable['id']) {
  return props.group.students!.find((student) => student.id === id)
}

function onDeleteConfirm(expulsionId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    ExpulsionService.delete(props.group.id, props.course.number, expulsionId)
  })
}

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.courses.expulsions.print', {
      group: props.group.id,
      course: props.course.number,
    })
  )
})
</script>
