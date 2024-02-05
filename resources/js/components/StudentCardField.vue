<template>
  <div class="student-card-text-field">
    <div class="student-card-text-field__label">{{ label }}</div>
    <div class="student-card-text-field__value" v-html="value"></div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ label: string; value?: string | null }>()
</script>

<style lang="sass" scoped>
.student-card-text-field
  display: flex
  align-items: center
  flex-wrap: wrap

.student-card-text-field__value
  flex-grow: 1
  text-align: center
  border-bottom: 1px solid
  min-height: 24px
</style>
