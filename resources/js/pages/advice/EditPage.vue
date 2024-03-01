<template>
  <ThePage padding>
    <Head title="Замечания и предложения по организации идеологической и воспитательной работы" />
    <AdviceForm
      v-model="modelValue"
      @submit="onSave(AdviceService.update(props.group.id, props.advice.id, modelValue), 'update')"
    />
  </ThePage>
</template>

<script lang="ts" setup>
import AdviceForm from '@/components/AdviceForm.vue'
import ThePage from '@/components/ThePage.vue'
import { onSave } from '@/helpers'
import { AdviceService } from '@/services'
import { AdviceFormModel, AdviceTable, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { ref } from 'vue'

const props = defineProps<{ group: Required<GroupModel, 'advice'>; advice: AdviceTable }>()

const modelValue = ref<AdviceFormModel>(props.advice)
</script>
