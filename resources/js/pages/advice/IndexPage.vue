<template>
  <ThePage padding>
    <Head title="Замечания и предложения по организации идеологической и воспитательной работы" />
    <h1 class="text-h4 q-mb-md text-center">
      Замечания и предложения<br />по организации идеологической и воспитательной работы
    </h1>
    <q-table :rows="props.group.advice" :columns :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <q-space />
        <q-btn
          color="primary"
          icon="add"
          size="sm"
          round
          @click="
            router.get(
              route('groups.advice.create', {
                group: group.id,
              })
            )
          "
        />
      </template>
      <template v-slot:header="props">
        <q-tr :props="props">
          <q-th v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.label }}
          </q-th>
          <q-th class="text-left">Действия</q-th>
        </q-tr>
      </template>
      <template v-slot:body="props">
        <q-tr :props="props">
          <q-td v-for="col in props.cols" :key="col.name" :props="props">
            {{ col.value }}
          </q-td>
          <q-td>
            <q-btn
              class="q-mr-sm"
              size="sm"
              color="primary"
              icon="edit"
              round
              @click="
                router.get(
                  route('groups.advice.edit', {
                    group: group.id,
                    advice: props.row.id,
                  })
                )
              "
            />
            <q-btn
              class="q-mr-sm"
              size="sm"
              color="negative"
              icon="delete"
              round
              @click="onDeleteConfirm(props.row.id)"
            />
          </q-td>
        </q-tr>
      </template>
    </q-table>
  </ThePage>
</template>

<script lang="ts" setup>
import ThePage from '@/components/ThePage.vue'
import { downloadFile, formatDate, onSave } from '@/helpers'
import { AdviceService } from '@/services'
import { GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { useQuasar } from 'quasar'
import { Required } from 'utility-types'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ group: Required<GroupModel, 'advice'> }>()
const $q = useQuasar()
const { t } = useI18n()

const columns = [
  {
    name: 'date',
    label: 'Дата',
    align: 'left',
    sortable: true,
    field: 'date',
    format: (val: string) => formatDate(val),
  },
  {
    name: 'comments',
    label: 'Замечания',
    align: 'left',
    sortable: true,
    field: 'comments',
  },
  {
    name: 'suggestions',
    label: 'Предложения',
    align: 'left',
    sortable: true,
    field: 'suggestions',
  },
  {
    name: 'full_name',
    label: 'ФИО',
    align: 'left',
    sortable: true,
    field: 'full_name',
  },
  {
    name: 'position',
    label: 'Должность',
    align: 'left',
    sortable: true,
    field: 'position',
  },
]

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.advice.print', {
      group: props.group.id,
    })
  )
})

function onDeleteConfirm(adviceId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    onSave(AdviceService.delete(props.group.id, adviceId), 'delete')
  })
}
</script>
