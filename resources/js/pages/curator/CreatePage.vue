<template>
  <q-page padding>
    <Head title="Создание куратора" />
    <CuratorForm v-model="curator" @submit="onSave(CuratorService.create(curator), 'create')" />
  </q-page>
</template>

<script lang="ts" setup>
import CuratorForm from '@/components/CuratorForm.vue'
import { onSave } from '@/helpers'
import { CuratorService } from '@/services'
import { CuratorFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const curator = ref<CuratorFormModel>({ surname: '', name: '', patronymic: '', login: '', password: '' })
</script>
