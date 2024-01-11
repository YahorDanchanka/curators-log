<template>
  <slot />
</template>

<script lang="ts" setup>
import { computed } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { useErrorHandler } from '@/composables/error-handler'
import type { IErrors } from '@/types'

const page = usePage()
const errors = computed<IErrors>(() => page.props.errors)
useErrorHandler(errors)
</script>
