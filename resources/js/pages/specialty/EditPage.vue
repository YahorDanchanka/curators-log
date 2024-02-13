<template>
  <q-page padding>
    <Head title="Обновление специальности" />
    <SpecialtyForm v-model="specialty" @submit="onSave(SpecialtyService.update(props.specialty.id, specialty))" />
  </q-page>
</template>

<script lang="ts" setup>
import SpecialtyForm from '@/components/SpecialtyForm.vue'
import { onSave } from '@/helpers'
import { SpecialtyService } from '@/services'
import { SpecialtyFormModel, SpecialtyTable } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const props = defineProps<{ specialty: SpecialtyTable }>()

const specialty = ref<SpecialtyFormModel>(props.specialty)
</script>
