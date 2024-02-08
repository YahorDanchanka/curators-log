<template>
  <q-page padding>
    <Head title="Факты ассоциального поведения учащегося" />
    <AsocialBehaviorForm
      v-model="modelValue"
      @submit="onSave(AsocialBehaviorService.create(props.group.id, props.studentNumber, modelValue), 'create')"
    />
  </q-page>
</template>

<script lang="ts" setup>
import AsocialBehaviorForm from '@/components/AsocialBehaviorForm.vue'
import { onSave } from '@/helpers'
import { AsocialBehaviorService } from '@/services'
import { AsocialBehaviorFormModel, GroupModel, StudentModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string }>()

const modelValue = ref<AsocialBehaviorFormModel>({ date: '', action: '', sanctions: '', result: '' })
</script>
