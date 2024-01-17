<template>
  <q-page padding>
    <Head :title="`Редактирование группы ${props.group.name}`" />
    <GroupForm
      v-model="group"
      :specialties="props.specialties"
      :curators="props.curators"
      @submit="GroupService.update(props.group.id, group)"
    />
  </q-page>
</template>

<script lang="ts" setup>
import { reactive, toRaw } from 'vue'
import { Head } from '@inertiajs/vue3'
import { useQuasar } from 'quasar'
import { GroupService } from '@/services'
import { CuratorModel, GroupFormModel, GroupModel, SpecialtyTable } from '@/types'
import GroupForm from '@/components/GroupForm.vue'

const props = defineProps<{
  group: GroupModel
  specialties: Pick<SpecialtyTable, 'id' | 'name'>[]
  curators: Pick<CuratorModel, 'id' | 'full_name'>[]
}>()
const $q = useQuasar()

const group = reactive<GroupFormModel>(structuredClone(toRaw(props.group)))
</script>
