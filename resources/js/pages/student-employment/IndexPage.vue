<template>
  <q-page padding>
    <Head :title="title" />
    <CourseTitle :title :course-number="props.course.number" />
    <q-markup-table separator="cell" wrap-cells>
      <thead>
        <tr>
          <th class="cell_autowidth" rowspan="2">№ п/п</th>
          <th rowspan="2">
            Учащиеся,<br />
            члены ОО «БРСМ»<br />
            <a href="#">
              добавить
              <q-popup-proxy>
                <div class="row items-center q-col-gutter-sm q-pa-sm bg-white" style="min-width: 300px">
                  <div class="col">
                    <q-select
                      label="Учащийся"
                      option-value="id"
                      option-label="full_name"
                      v-model="selectedStudent"
                      :options="props.group.students"
                      clearable
                      emit-value
                      map-options
                    />
                  </div>
                  <div class="col-auto">
                    <q-btn
                      label="Выбрать"
                      color="primary"
                      :disable="selectedStudent === null"
                      v-close-popup
                      @click="
                        selectedStudent
                          ? StudentCharacteristicService.attach(selectedStudent, 30, props.course.id)
                          : null
                      "
                    />
                  </div>
                </div>
              </q-popup-proxy>
            </a>
          </th>
          <th rowspan="2">
            Учащиеся, члены<br />
            ученического профкома<br />
            <a
              href="#"
              @click="
                router.get(
                  route('groups.courses.leadership.index', {
                    group: props.group.id,
                    course_number: props.course.number,
                  })
                )
              "
              >редактировать</a
            >
          </th>
          <th colspan="2">
            Занятость учащихся<br />
            в объединениях по интересам (название)
          </th>
        </tr>
        <tr>
          <th class="border-l">1 полугодие</th>
          <th>2 полугодие</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in Math.max(brsmStudents.length, leadershipStudents.length)">
          <td>{{ index + 1 }}</td>
          <td>
            {{ brsmStudents[index]?.initials }}
            <a
              v-if="brsmStudents[index]?.id"
              href="#"
              @click.prevent="StudentCharacteristicService.detach(brsmStudents[index].id, 30, props.course.id)"
            >
              (удалить)
            </a>
          </td>
          <td>{{ leadershipStudents[index]?.initials }}</td>
          <td class="cell_no-padding">
            <input
              v-if="
                leadershipStudents[index]?.id
                  ? studentEmploymentService.findIndex(leadershipStudents[index].id) !== -1
                  : false
              "
              class="cell__input text-center"
              v-model="
                studentEmploymentService.data[studentEmploymentService.findIndex(leadershipStudents[index].id)]
                  .first_semester
              "
            />
          </td>
          <td class="cell_no-padding">
            <input
              v-if="
                leadershipStudents[index]?.id
                  ? studentEmploymentService.findIndex(leadershipStudents[index].id) !== -1
                  : false
              "
              class="cell__input text-center"
              v-model="
                studentEmploymentService.data[studentEmploymentService.findIndex(leadershipStudents[index].id)]
                  .second_semester
              "
            />
          </td>
        </tr>
        <tr>
          <th>Всего</th>
          <th>{{ brsmStudents.length }}</th>
          <th>{{ leadershipStudents.length }}</th>
          <th>{{ studentEmploymentService.data.filter((row) => !!row.first_semester).length }}</th>
          <th>{{ studentEmploymentService.data.filter((row) => !!row.second_semester).length }}</th>
        </tr>
      </tbody>
    </q-markup-table>
  </q-page>
</template>

<script setup lang="ts">
import { computed, reactive, ref, toRaw } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import route from 'ziggy-js'
import { CourseModel, GroupModel, IEnum, StudentModel } from '@/types'
import CourseTitle from '@/components/CourseTitle.vue'
import { StudentRepository } from '@/repositories'
import { StudentCharacteristicService, StudentEmploymentService } from '@/services'
import { onSave } from '@/helpers'

const props = defineProps<{
  group: GroupModel
  course: CourseModel
  enums: {
    CharacteristicId: IEnum
  }
}>()

const title = 'Занятость учащихся общественно полезной деятельностью'

const studentEmploymentService = reactive(new StudentEmploymentService([]))
const selectedStudent = ref<number | null>(null)

const studentRepository = computed(() => new StudentRepository(toRaw(props.group.students)))
const brsmStudents = computed<StudentModel[]>(() => studentRepository.value.getBRSMStudents())
const leadershipStudents = computed<StudentModel[]>(() => studentRepository.value.getLeadershipStudents())

studentEmploymentService.load(studentRepository.value)

document.addEventListener('save', () => {
  onSave(studentEmploymentService.save(props.group.id, props.course.number))
})
</script>
