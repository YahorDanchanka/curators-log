<template>
  <BaseLayout>
    <q-layout view="hHh LpR fFf">
      <q-header elevated class="bg-primary text-white">
        <q-toolbar>
          <q-btn icon="menu" @click="toggleLeftDrawer" dense flat round />
          <q-toolbar-title> Журнал куратора </q-toolbar-title>
        </q-toolbar>
      </q-header>

      <q-drawer side="left" behavior="mobile" v-model="leftDrawerOpen" bordered overlay>
        <q-scroll-area class="fit"> </q-scroll-area>
      </q-drawer>

      <q-page-container>
        <slot />
      </q-page-container>
    </q-layout>
  </BaseLayout>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import BaseLayout from '@/layouts/BaseLayout.vue'

const leftDrawerOpen = ref(false)

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}
</script>
