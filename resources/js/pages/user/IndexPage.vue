<template>
  <q-page padding>
    <Head title="Пользователи" />
    <AppTable id="user-table" :rows="$props.users" :columns="columns" :rows-per-page-options="[0]">
      <template v-slot:top="data">
        <h1 class="q-table__title" style="line-height: normal">Пользователи</h1>
        <q-space />
        <q-btn
          v-if="can('users.create')"
          color="primary"
          icon="add"
          size="sm"
          round
          @click="router.get(route('users.create'))"
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
              v-if="props.row.can?.update"
              class="q-mr-sm"
              size="sm"
              color="primary"
              icon="edit"
              round
              @click="router.get(route('users.edit', { user: props.row.id }))"
            />
            <q-btn
              v-if="props.row.can?.delete"
              size="sm"
              color="negative"
              icon="delete"
              round
              @click="
                onSave(inertiaFetch(route('users.destroy', { user: props.row.id }), { method: 'delete' }), 'delete')
              "
            />
          </q-td>
        </q-tr>
      </template>
    </AppTable>
  </q-page>
</template>

<script lang="ts" setup>
import AppTable from '@/components/AppTable.vue'
import { can, inertiaFetch, onSave } from '@/helpers'
import { UserModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { QTableColumn } from 'quasar'
import { Required } from 'utility-types'
import { useI18n } from 'vue-i18n'
import route from 'ziggy-js'

const props = defineProps<{ users: Required<UserModel, 'roles'>[] }>()
const { t } = useI18n()

const columns: QTableColumn[] = [
  {
    name: 'login',
    label: 'Логин',
    align: 'left',
    sortable: true,
    field: 'login',
  },
  {
    name: 'roles',
    label: 'Роль',
    align: 'left',
    sortable: true,
    field: (row: Required<UserModel, 'roles'>) => (row.roles.length > 0 ? t(`any.${row.roles[0].name}`) : ''),
  },
]
</script>
