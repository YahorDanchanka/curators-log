<template>
  <q-page padding>
    <Head :title="`Создание родственника ${props.student.initials}`" />
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
import { Head } from '@inertiajs/vue3'
import { StudentRelativeService } from '@/services'
import AdultRelativeForm from '@/components/AdultRelativeForm.vue'
import { GroupModel, RelativeFormModel, StudentModel } from '@/types'
import MinorRelativeForm from '@/components/MinorRelativeForm.vue'

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
          region_id: 40,
          district_id: 44,
        }
      : null,
  type: props.type === 'adult' ? 'отец' : null,
})
</script>
