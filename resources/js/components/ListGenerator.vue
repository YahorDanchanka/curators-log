<template>
  <q-list>
    <template v-for="item in list.filter((i) => !i.hidden)">
      <q-item v-if="'items' in item" clickable>
        <q-item-section>
          <q-item-label>{{ item.label }}</q-item-label>
        </q-item-section>
        <q-item-section side>
          <q-icon name="keyboard_arrow_right" />
        </q-item-section>
        <q-menu anchor="top end" self="top start" auto-close>
          <ListGenerator :list="item.items!" />
        </q-menu>
      </q-item>
      <q-item
        v-else
        :active="isMenuItemWithRoute(item) ? routeInfo.url.value === item.route : false"
        clickable
        v-ripple
        @click="
          isMenuItemWithRoute(item)
            ? item.download
              ? downloadFile(item.route)
              : router.visit(item.route)
            : item.onClick()
        "
      >
        <q-item-section v-if="item.icon" avatar>
          <q-icon :name="item.icon" />
        </q-item-section>
        <q-item-section> {{ item.label }} </q-item-section>
      </q-item>
    </template>
  </q-list>
</template>

<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { useRoute } from '@/composables/route'
import { isMenuItemWithRoute, MenuList } from '@/types'
import { downloadFile } from '@/helpers'

const props = defineProps<{ list: MenuList }>()
const routeInfo = useRoute()
</script>
