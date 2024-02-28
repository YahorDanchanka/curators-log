<template>
  <q-page padding>
    <Head :title="`Создание родственника ${props.student.initials}`" />
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
      <q-breadcrumbs-el label="Создание" />
    </q-breadcrumbs>
    <AdultRelativeForm
      v-if="props.type === 'adult'"
      v-model="relative"
      @submit="StudentRelativeService.create(props.group.id, props.studentNumber, relative)"
    />
    <MinorRelativeForm
      v-else
      v-model="relative"
      @submit="StudentRelativeService.create(props.group.id, props.studentNumber, relative)"
    />
  </q-page>
</template>

<script setup lang="ts">
import { reactive } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import { StudentRelativeService } from '@/services'
import AdultRelativeForm from '@/components/AdultRelativeForm.vue'
import { GroupModel, RelativeFormModel, StudentModel } from '@/types'
import MinorRelativeForm from '@/components/MinorRelativeForm.vue'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; student: StudentModel; studentNumber: string; type: string }>()

const relative = reactive<RelativeFormModel>({
  surname: '',
  name: '',
  patronymic: '',
  sex: 'мужской',
  birthday: '',
  job: '',
  position: '',
  phone: '',
  educational_institution: '',
  address:
    props.type === 'adult'
      ? {
          type: 'Город',
          residence: '',
          street: '',
          apartment_number: '',
          region_id: 40,
          district_id: 44,
        }
      : null,
  type: props.type === 'adult' ? 'отец' : null,
})
</script>
