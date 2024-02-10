<template>
  <q-page class="login-page column items-center justify-center" padding>
    <Head title="Вход в аккаунт" />
    <q-card class="login-page__card">
      <q-card-section>
        <AuthForm v-model="user" @submit="AuthService.login(user)" />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script lang="ts" setup>
import AuthForm from '@/components/AuthForm.vue'
import BlankLayout from '@/layouts/BlankLayout.vue'
import { AuthService } from '@/services'
import { AuthFormModel } from '@/types'
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

defineOptions({ layout: BlankLayout })

const user = ref<AuthFormModel>({ login: '', password: '' })
</script>

<style lang="sass" scoped>
.login-page__card
  width: 100%
  max-width: 480px
</style>
