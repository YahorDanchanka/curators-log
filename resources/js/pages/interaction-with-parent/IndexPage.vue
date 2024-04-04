<template>
  <ThePage padding>
    <Head title="Содержание взаимодействия с родителями (другими законными представителями) учащихся" />
    <h1 class="text-h4 q-mb-md text-center">
      Содержание взаимодействия с родителями<br />
      <span class="text-h5">(другими законными представителями)</span>
      учащихся
    </h1>
    <q-table :rows="props.group.interaction_with_parents" :columns="columns" :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <q-space />
        <q-btn
          color="primary"
          icon="add"
          size="sm"
          round
          @click="
            router.get(
              route('groups.interaction-with-parents.create', {
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
                  route('groups.interaction-with-parents.edit', {
                    group: group.id,
                    interaction_with_parent: props.row.id,
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
import { InteractionWithParentService } from '@/services'
import { GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { useEventListener } from '@vueuse/core'
import { useQuasar } from 'quasar'
import { Required } from 'utility-types'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ group: Required<GroupModel, 'interaction_with_parents'> }>()
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
    name: 'content',
    label: 'Содержание деятельности',
    align: 'left',
    sortable: true,
    field: 'content',
  },
  {
    name: 'result',
    label: 'Результат',
    align: 'left',
    sortable: true,
    field: 'result',
  },
]

useEventListener(document, 'print', () => {
  downloadFile(
    route('groups.interaction-with-parents.print', {
      group: props.group.id,
    })
  )
})

function onDeleteConfirm(interactionWithParentId: string | number) {
  $q.dialog({
    title: t('messages.confirmDelete.title'),
    message: t('messages.confirmDelete.description'),
    cancel: true,
  }).onOk(() => {
    onSave(InteractionWithParentService.delete(props.group.id, interactionWithParentId), 'delete')
  })
}
</script>
