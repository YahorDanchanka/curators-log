<template>
  <q-page padding>
    <Head title="Обновление пользователя" />
    <UserForm
      v-model="user"
      is-update
      @submit="onSave(inertiaFetch(route('users.update', { user: $props.user.id }), { method: 'put', data: user }))"
    />
  </q-page>
</template>

<script lang="ts" setup>
import UserForm from '@/components/UserForm.vue'
import { inertiaFetch, onSave } from '@/helpers'
import { UserFormModel, UserModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { cloneDeep } from 'lodash'
import { Required } from 'utility-types'
import { ref } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ user: Required<UserModel, 'roles'> }>()

const user = ref<UserFormModel>(cloneDeep({ ...props.user, role: props.user.roles[0]?.name || null }))
</script>
