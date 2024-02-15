<template>
  <q-select
    input-debounce="0"
    :model-value="modelValue"
    :label="props.label"
    :options="options"
    use-input
    hide-selected
    fill-input
    @input-value="setModelValue"
    @filter="filter"
  />
</template>

<script lang="ts" setup>
import { cloneDeep } from 'lodash'
import { ref } from 'vue'

const props = defineProps<{ label?: string; options: any[] }>()
const modelValue = defineModel<string | undefined | null>({ required: true })

const options = ref<any[]>(cloneDeep(props.options))

function filter(value: string, update: any) {
  update(() => {
    const needle = value.toLocaleLowerCase()
    options.value = props.options.filter((v) => v.toLocaleLowerCase().indexOf(needle) > -1)
  })
}

function setModelValue(value: string) {
  modelValue.value = value
}
</script>
