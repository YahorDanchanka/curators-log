<template>
  <q-page padding>
    <Head :title="`Редактирование ${props.relative.initials}`" />
    <AdultRelativeForm
      v-if="props.type === 'adult'"
      v-model="relative"
      @submit="StudentRelativeService.update(props.group.id, props.studentNumber, props.relative.id, relative)"
    />
    <MinorRelativeForm
      v-else
      v-model="relative"
      @submit="StudentRelativeService.update(props.group.id, props.studentNumber, props.relative.id, relative)"
    />
  </q-page>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import { StudentRelativeService } from '@/services'
import AdultRelativeForm from '@/components/AdultRelativeForm.vue'
import { GroupModel, RelativeFormModel, RelativeModel, StudentModel } from '@/types'
import MinorRelativeForm from '@/components/MinorRelativeForm.vue'

const props = defineProps<{
  group: GroupModel
  student: StudentModel
  studentNumber: string
  relative: RelativeModel
  type: string
}>()

const relative = reactive<RelativeFormModel>({ ...props.relative, type: props.relative.pivot?.type })
</script>
