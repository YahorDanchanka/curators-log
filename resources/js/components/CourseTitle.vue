<template>
  <h1 class="course-title text-h4 q-mb-md">
    <span class="course-title__header">
      {{ props.title }}
    </span>
    <strong class="course-title__course">{{ props.courseNumber }} курс обучения</strong>
  </h1>
</template>

<script setup lang="ts">
const props = defineProps<{ title: string; courseNumber: number }>()
</script>
