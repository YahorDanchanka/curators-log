<template>
  <q-page padding>
    <Head title="Создание специальности" />
    <SpecialtyForm v-model="specialty" @submit="onSave(SpecialtyService.create(specialty), 'create')" />
  </q-page>
</template>

<script lang="ts" setup>
import SpecialtyForm from '@/components/SpecialtyForm.vue'
import { onSave } from '@/helpers'
import { SpecialtyService } from '@/services'
import { SpecialtyFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const specialty = ref<SpecialtyFormModel>({ name: '', code: '', prefix: '' })
</script>
