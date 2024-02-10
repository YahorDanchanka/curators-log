<template>
  <BaseLayout>
    <q-layout view="hHh LpR fFf">
      <q-page-container>
        <slot />
      </q-page-container>
    </q-layout>
  </BaseLayout>
</template>

<script lang="ts" setup>
import BaseLayout from '@/layouts/BaseLayout.vue'
</script>
