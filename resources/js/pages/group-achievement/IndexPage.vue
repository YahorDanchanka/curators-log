<template>
  <ThePage padding>
    <Head :title="`Достижения учебной группы ${props.course.group_name}`" />
    <CourseTitle title="Достижения учебной группы" :course-number="props.course.number" />
    <q-markup-table separator="cell" wrap-cells>
      <thead>
        <tr>
          <th colspan="2">
            <q-btn
              color="primary"
              icon="add"
              size="sm"
              round
              @click="
                router.get(
                  route('groups.courses.achievements.create', {
                    group: props.group.id,
                    course: props.course.number,
                  })
                )
              "
            />
          </th>
        </tr>
        <tr>
          <th style="width: 50%">1 семестр</th>
          <th style="width: 50%">2 семестр</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(key, i) in Math.max(firstSemesterAchievements.length, secondSemesterAchievements.length)">
          <td>
            {{ firstSemesterAchievements[i]?.content }}
            <span
              v-if="firstSemesterAchievements[i]"
              class="link"
              @click="
                router.get(
                  route('groups.courses.achievements.edit', {
                    group: props.group.id,
                    course: props.course.number,
                    achievement: firstSemesterAchievements[i].id,
                  })
                )
              "
            >
              (изменить)
            </span>
            <span
              v-if="firstSemesterAchievements[i]"
              class="link"
              @click="
                onSave(
                  GroupAchievementService.delete(props.group.id, props.course.number, firstSemesterAchievements[i].id),
                  'delete'
                )
              "
            >
              (удалить)
            </span>
          </td>
          <td>
            {{ secondSemesterAchievements[i]?.content }}
            <span
              v-if="secondSemesterAchievements[i]"
              class="link"
              @click="
                router.get(
                  route('groups.courses.achievements.edit', {
                    group: props.group.id,
                    course: props.course.number,
                    achievement: secondSemesterAchievements[i].id,
                  })
                )
              "
            >
              (изменить)
            </span>
            <span
              v-if="secondSemesterAchievements[i]"
              class="link"
              @click="
                onSave(
                  GroupAchievementService.delete(props.group.id, props.course.number, secondSemesterAchievements[i].id),
                  'delete'
                )
              "
            >
              (удалить)
            </span>
          </td>
        </tr>
      </tbody>
    </q-markup-table>
  </ThePage>
</template>

<script lang="ts" setup>
import CourseTitle from '@/components/CourseTitle.vue'
import ThePage from '@/components/ThePage.vue'
import { downloadFile, onSave } from '@/helpers'
import { GroupAchievementService } from '@/services'
import { CourseModel, GroupModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import { computed } from 'vue'
import route from 'ziggy-js'

const props = defineProps<{ group: GroupModel; course: Required<CourseModel, 'achievements'> }>()

const firstSemesterAchievements = computed(() =>
  props.course.achievements.filter((achievement) => achievement.semester === '1')
)

const secondSemesterAchievements = computed(() =>
  props.course.achievements.filter((achievement) => achievement.semester === '2')
)

document.addEventListener('print', () => {
  downloadFile(
    route('groups.courses.achievements.print', {
      group: props.group.id,
      course_number: props.course.number,
    })
  )
})
</script>
