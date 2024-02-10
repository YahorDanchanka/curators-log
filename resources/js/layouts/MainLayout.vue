<template>
  <BaseLayout>
    <q-layout view="hHh LpR fFf">
      <q-header elevated class="bg-primary text-white">
        <q-toolbar>
          <q-btn icon="menu" @click="toggleLeftDrawer" dense flat round />
          <q-toolbar-title> Журнал куратора </q-toolbar-title>
          <q-btn
            v-if="props.printing"
            class="q-mr-sm"
            icon="print"
            title="Печать"
            flat
            round
            dense
            @click="emitEvent('print')"
          />
          <q-btn v-if="props.saving" icon="save" title="Сохранить" flat round dense @click="emitEvent('save')" />
        </q-toolbar>
      </q-header>

      <q-drawer side="left" behavior="mobile" v-model="leftDrawerOpen" bordered overlay>
        <q-scroll-area class="fit">
          <q-list class="menu-list">
            <q-item
              :active="route().current() === 'curators.index'"
              clickable
              v-ripple
              @click="router.get(route('curators.index'))"
            >
              <q-item-section avatar>
                <q-icon name="supervisor_account" />
              </q-item-section>
              <q-item-section> Кураторы </q-item-section>
            </q-item>
            <q-item
              :active="route().current() === 'groups.index'"
              clickable
              v-ripple
              @click="router.get(route('groups.index'))"
            >
              <q-item-section avatar>
                <q-icon name="group" />
              </q-item-section>
              <q-item-section> Группы </q-item-section>
            </q-item>
            <q-item clickable v-ripple @click="router.post(route('auth.logout'))">
              <q-item-section avatar>
                <q-icon name="logout" />
              </q-item-section>
              <q-item-section> Выйти </q-item-section>
            </q-item>
          </q-list>
        </q-scroll-area>
      </q-drawer>

      <q-page-container>
        <slot />
      </q-page-container>
    </q-layout>
  </BaseLayout>
</template>

<script lang="ts" setup>
import BaseLayout from '@/layouts/BaseLayout.vue'
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ saving?: boolean; printing?: boolean }>()

const leftDrawerOpen = ref(false)

function emitEvent(type: string) {
  document.dispatchEvent(new Event(type))
}

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
