<template>
  <q-page padding>
    <Head :title="`Редактирование ${props.relative.initials}`" />
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el class="cursor-pointer" label="Группы" @click="router.get(route('groups.index'))" />
      <q-breadcrumbs-el :label="props.group.name!" style="color: black" />
      <q-breadcrumbs-el
        class="cursor-pointer"
        label="Список учащихся"
        @click="router.get(route('groups.students.index', { group: props.group.id }))"
      />
      <q-breadcrumbs-el :label="props.student.full_name" style="color: black" />
      <q-breadcrumbs-el
        class="cursor-pointer"
        label="Родственники"
        @click="
          router.get(route('groups.students.relatives.index', { group: props.group.id, student: props.studentNumber }))
        "
      />
      <q-breadcrumbs-el label="Редактирование" />
    </q-breadcrumbs>
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
import { Head, router } from '@inertiajs/vue3'
import { StudentRelativeService } from '@/services'
import AdultRelativeForm from '@/components/AdultRelativeForm.vue'
import { GroupModel, RelativeFormModel, RelativeModel, StudentModel } from '@/types'
import MinorRelativeForm from '@/components/MinorRelativeForm.vue'
import route from 'ziggy-js'

const props = defineProps<{
  group: GroupModel
  student: StudentModel
  studentNumber: string
  relative: RelativeModel
  type: string
}>()

const relative = reactive<RelativeFormModel>({ ...props.relative, type: props.relative.pivot?.type })
</script>
