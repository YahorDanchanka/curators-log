<template>
  <BaseLayout>
    <q-layout view="hHh LpR fFf">
      <q-header elevated class="bg-primary text-white">
        <q-toolbar>
          <q-btn icon="menu" @click="toggleLeftDrawer" dense flat round />
          <q-toolbar-title> АРМ зам. директора по воспитательной работе УО "ГГАЭК" </q-toolbar-title>
          <q-btn v-if="props.loading" class="q-mr-sm" icon="sync" flat round dense @click="emitEvent('sync')">
            <q-tooltip> Загрузить из предыдущего курса </q-tooltip>
          </q-btn>
          <q-btn v-if="props.printing" class="q-mr-sm" icon="print" flat round dense @click="emitEvent('print')">
            <q-tooltip> Печать </q-tooltip>
          </q-btn>
          <q-btn v-if="props.saving" icon="save" title="Сохранить" flat round dense @click="emitEvent('save')">
            <q-tooltip> Сохранить </q-tooltip>
          </q-btn>
        </q-toolbar>
      </q-header>

      <q-drawer side="left" behavior="mobile" v-model="leftDrawerOpen" bordered overlay>
        <q-scroll-area class="fit">
          <ListGenerator class="menu-list" :list="menuList" />
        </q-scroll-area>
      </q-drawer>

      <q-page-container>
        <slot />
      </q-page-container>
    </q-layout>
  </BaseLayout>
</template>

<script lang="ts" setup>
import ListGenerator from '@/components/ListGenerator.vue'
import { can } from '@/helpers'
import BaseLayout from '@/layouts/BaseLayout.vue'
import { MenuList } from '@/types'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ saving?: boolean; printing?: boolean; loading?: boolean }>()

const leftDrawerOpen = ref(false)

function emitEvent(type: string) {
  document.dispatchEvent(new Event(type))
}

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

const menuList = ref<MenuList>([
  {
    route: route('users.index'),
    label: 'Пользователи',
    icon: 'admin_panel_settings',
    hidden: !can('users.viewAny'),
  },
  {
    route: route('specialties.index'),
    label: 'Специальности',
    icon: 'school',
    hidden: !can('specialties.viewAny'),
  },
  {
    route: route('curators.index'),
    label: 'Кураторы',
    icon: 'supervisor_account',
    hidden: !can('curators.viewAny'),
  },
  { route: route('groups.index'), label: 'Группы', icon: 'groups', hidden: !can('groups.viewAny') },
  {
    label: 'Выйти',
    icon: 'logout',
    onClick() {
      router.post(route('auth.logout'))
    },
  },
])
</script>
