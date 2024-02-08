<template>
  <q-page padding>
    <Head title="Замечания и предложения по организации идеологической и воспитательной работы" />
    <AdviceForm v-model="modelValue" @submit="onSave(AdviceService.create(props.group.id, modelValue), 'create')" />
  </q-page>
</template>

<script lang="ts" setup>
import AdviceForm from '@/components/AdviceForm.vue'
import { onSave } from '@/helpers'
import { AdviceService } from '@/services'
import { AdviceFormModel, GroupModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { ref } from 'vue'

const props = defineProps<{ group: Required<GroupModel, 'advice'> }>()

const modelValue = ref<AdviceFormModel>({ date: '', comments: '', suggestions: '', full_name: '', position: '' })
</script>
