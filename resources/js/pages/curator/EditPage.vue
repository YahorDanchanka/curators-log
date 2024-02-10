<template>
  <q-page padding>
    <Head title="Редактирование куратора" />
    <CuratorForm v-model="curator" is-update @submit="onSave(CuratorService.update(props.curator.id, curator))" />
  </q-page>
</template>

<script lang="ts" setup>
import CuratorForm from '@/components/CuratorForm.vue'
import { onSave } from '@/helpers'
import { CuratorService } from '@/services'
import { CuratorFormModel, CuratorModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { ref } from 'vue'

const props = defineProps<{ curator: Required<CuratorModel, 'user'> }>()

const curator = ref<CuratorFormModel>({ ...props.curator, login: props.curator.user.login, password: '' })
</script>
