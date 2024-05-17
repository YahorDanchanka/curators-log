<template>
  <q-page padding>
    <Head title="Создание пользователя" />
    <UserForm v-model="user" @submit="onSave(inertiaFetch(route('users.store'), { method: 'post', data: user }))" />
  </q-page>
</template>

<script lang="ts" setup>
import UserForm from '@/components/UserForm.vue'
import { inertiaFetch, onSave } from '@/helpers'
import { UserFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'
import route from 'ziggy-js'

const user = ref<UserFormModel>({ login: '', password: '', role: 'psychologist' })
</script>
