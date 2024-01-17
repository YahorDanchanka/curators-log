<template>
  <q-page padding>
    <Head title="Создание группы" />
    <GroupForm
      v-model="group"
      :specialties="props.specialties"
      :curators="props.curators"
      @submit="GroupService.create(group)"
    />
  </q-page>
</template>

<script lang="ts" setup>
import { reactive } from 'vue'
import { Head } from '@inertiajs/vue3'
import { uid, useQuasar } from 'quasar'
import { GroupService } from '@/services'
import { CuratorModel, GroupFormModel, SpecialtyTable } from '@/types'
import GroupForm from '@/components/GroupForm.vue'

const props = defineProps<{
  specialties: Pick<SpecialtyTable, 'id' | 'name'>[]
  curators: Pick<CuratorModel, 'id' | 'full_name'>[]
}>()
const $q = useQuasar()

const group = reactive<GroupFormModel>({
  number: 1,
  specialty_id: null,
  courses: [{ id: uid(), number: 1, start_education: null, end_education: null, curator_id: null }],
})
</script>
