<template>
  <h1 class="course-title text-h4 q-mb-md">
    <div class="course-title__header">
      <template v-if="title !== undefined">{{ title }}</template>
      <slot v-else />
    </div>
    <strong class="course-title__course">{{ props.courseNumber }} курс обучения</strong>
  </h1>
</template>

<script setup lang="ts">
const props = defineProps<{ title?: string; courseNumber: number }>()
</script>

<style lang="sass" scoped>
.course-title
  display: flex
  flex-wrap: wrap
  justify-content: center
  align-items: center
  text-align: center

.course-title__header
  margin-left: auto

.course-title__course
  margin-left: auto
  font-size: 0.4em
</style>
