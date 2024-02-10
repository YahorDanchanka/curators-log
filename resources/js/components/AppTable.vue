<template>
  <q-table :visible-columns="visibleColumns">
    <template v-for="(_, name) in $slots" v-slot:[name]="slotData">
      <template v-if="name === 'top'">
        <slot name="top" v-bind="slotData"></slot>
        <q-select
          class="q-ml-md"
          display-value="Колонки"
          option-value="name"
          style="min-width: 150px"
          v-model="visibleColumns"
          :options="columns"
          emit-value
          map-options
          options-cover
          multiple
          outlined
          dense
          options-dense
        />
      </template>
      <slot v-else :name="name" v-bind="slotData" />
    </template>
  </q-table>
</template>

<script setup lang="ts">
import { useStorage } from '@vueuse/core'
import { QTableColumn, QTableProps } from 'quasar'
import { computed, useAttrs } from 'vue'

const attrs = useAttrs()
const props = defineProps<{ id: string }>()

const columns = computed<QTableColumn[]>(() => (<QTableProps>attrs).columns ?? [])

/** По умолчанию видны все колонки */
const visibleColumns = useStorage(
  props.id,
  columns.value.map((column) => column.name)
)
</script>
