<template>
  <q-markup-table class="q-mb-md" separator="cell">
    <tbody v-if="true">
      <tr>
        <th style="border-bottom-width: 1px" rowspan="2">Учащиеся</th>
        <th style="border-bottom-width: 1px" :colspan="modelValue.subjects.length * 3 + 2">Предметы</th>
      </tr>
      <tr class="q-tr--no-hover">
        <template v-for="(subject, subjectIndex) in modelValue.subjects">
          <td
            class="grade-table__cell grade-table__cell_input"
            colspan="2"
            style="border-left: 1px solid rgba(0, 0, 0, 0.12)"
          >
            <q-input
              class="grade-table__input"
              v-model="subject.name"
              :input-style="{ width: `${subject.name.length / 1.7}em`, minWidth: '100px' }"
              borderless
            />
            <q-menu touch-position context-menu>
              <q-list style="min-width: 100px" dense>
                <q-item clickable v-close-popup @click="deleteCell(subjectIndex)">
                  <q-item-section>Удалить</q-item-section>
                </q-item>
              </q-list>
            </q-menu>
          </td>
          <th style="border-bottom-width: 1px">Итого за «{{ subject.name }}»</th>
        </template>
        <td
          class="cell_autowidth"
          style="border-left: 1px solid rgba(0, 0, 0, 0.12); border-bottom-width: 0"
          :rowspan="props.students.length + 1"
        >
          <q-btn size="sm" color="primary" icon="add" round @click="modelValue.addSubject()" />
        </td>
        <th style="border-bottom-width: 1px">Общий итог</th>
      </tr>
      <tr v-for="(student, studentIndex) in props.students" class="q-tr--no-hover" :key="student.id">
        <td>{{ student.initials }}</td>

        <template v-for="(subject, subjectIndex) in modelValue.subjects">
          <td v-if="hasGrades(student.id, subject)" class="grade-table__cell grade-table__cell_input">
            <q-markup-table class="grade-table__sub-table" separator="cell">
              <tr>
                <td
                  v-for="(grade, gradeIndex) in subject.rows[student.id].grades"
                  class="grade-table__td-grade grade-table__cell_input"
                  :class="grade.getClasses()"
                >
                  <q-input
                    type="number"
                    class="grade-table__input"
                    min="0"
                    max="10"
                    :modelValue="grade.value"
                    borderless
                    @update:modelValue="typeGrade(subject.rows[student.id].grades, gradeIndex, $event)"
                  />

                  <q-menu touch-position context-menu>
                    <q-list style="min-width: 100px" dense>
                      <q-item clickable>
                        <q-item-section>Тип оценки</q-item-section>
                        <q-item-section side>
                          <q-icon name="keyboard_arrow_right" />
                        </q-item-section>

                        <q-menu anchor="top end" self="top start">
                          <q-list>
                            <q-item
                              :class="grade.getClasses('default')"
                              :active="grade.type === 'default'"
                              dense
                              clickable
                              v-close-popup
                              @click="changeGradeType(student.id, subject, gradeIndex, 'default')"
                            >
                              <q-item-section>Стандартная</q-item-section>
                            </q-item>
                            <q-item
                              :class="grade.getClasses('primary')"
                              :active="grade.type === 'primary'"
                              dense
                              clickable
                              v-close-popup
                              @click="changeGradeType(student.id, subject, gradeIndex, 'primary')"
                            >
                              <q-item-section>ОКР</q-item-section>
                            </q-item>
                            <q-item
                              :class="grade.getClasses('course')"
                              :active="grade.type === 'course'"
                              dense
                              clickable
                              v-close-popup
                              @click="changeGradeType(student.id, subject, gradeIndex, 'course')"
                            >
                              <q-item-section>Курсовая работа</q-item-section>
                            </q-item>
                          </q-list>
                        </q-menu>
                      </q-item>
                      <q-item clickable v-close-popup @click="deleteCell(subjectIndex, gradeIndex)">
                        <q-item-section>Удалить</q-item-section>
                      </q-item>
                    </q-list>
                  </q-menu>
                </td>
              </tr>
            </q-markup-table>
          </td>
          <td
            v-if="studentIndex === 0"
            class="grade-table__cell"
            :class="{ cell_autowidth: hasGrades(student.id, subject) }"
            :rowspan="props.students.length"
          >
            <q-btn size="sm" color="primary" icon="add" round @click="addGrade(subject)" />
          </td>
          <td :colspan="hasGrades(student.id, subject) ? undefined : '2'">
            {{ Number.isNaN(subject.getGrade(student.id)) ? '' : subject.getGrade(student.id).toFixed(2) }}
          </td>
        </template>

        <td>{{ Number.isNaN(modelValue.getGrade(student)) ? '' : modelValue.getGrade(student).toFixed(2) }}</td>
      </tr>
    </tbody>
  </q-markup-table>
</template>

<script setup lang="ts">
import { watch } from 'vue'
import { debounce } from 'quasar'
import { GradeDTO, GradeRowDTO, GradeTableDTO } from '@/dto'
import type { GradeTableGrade, GradeTableSubject, StudentModel } from '@/types'

const props = withDefaults(
  defineProps<{ students: Pick<StudentModel, 'id' | 'initials'>[]; debounceWaitTime?: number }>(),
  {
    debounceWaitTime: 1500,
  }
)

const modelValue = defineModel<GradeTableDTO>({ required: true })
const emit = defineEmits(['save'])

function hasGrades(student_id: StudentModel['id'], subject: GradeTableSubject): boolean {
  return student_id in subject.rows && subject.rows[student_id].grades.length > 0
}

function changeGradeType(
  studentId: StudentModel['id'],
  subject: GradeTableSubject,
  gradeIndex: number,
  type: GradeTableGrade['type']
) {
  for (let i = 0; i < props.students.length; i++) {
    const student = props.students[i]
    subject.rows[student.id].grades[gradeIndex].type = type
  }
}

function deleteCell(subjectIndex: number, gradeIndex?: number) {
  /** Remove a grade cell */
  if (gradeIndex !== undefined) {
    for (let i = 0; i < props.students.length; i++) {
      const student = props.students[i]
      modelValue.value.subjects[subjectIndex].rows[student.id].grades.splice(gradeIndex, 1)
    }

    return
  }

  /** Remove a subject cell */
  modelValue.value.subjects.splice(subjectIndex, 1)
}

function typeGrade(grades: GradeTableGrade[], gradeIndex: number, grade: string) {
  const gradeNumeric = parseInt(grade, 10)

  if (grade === '') {
    grades[gradeIndex].value = ''
    return
  }

  if (gradeNumeric >= 0 && gradeNumeric <= 10) {
    grades[gradeIndex].value = gradeNumeric
  }
}

function addGrade(subject: GradeTableSubject) {
  for (let i = 0; i < props.students.length; i++) {
    const student = props.students[i]
    const cursor = subject.rows[student.id]
    const gradeObject = new GradeDTO('default', '')

    if (cursor) {
      cursor.grades.push(gradeObject)
    } else {
      subject.rows[student.id] = new GradeRowDTO([gradeObject])
    }
  }
}

function debounceHandler() {
  return debounce(() => {
    emit('save')
  }, props.debounceWaitTime)
}

const emitSave = debounceHandler()

watch(
  modelValue,
  () => {
    emitSave()
  },
  { deep: true }
)
</script>

<style lang="sass" scoped>
.grade-table__td-table
  padding: 0

.grade-table__sub-table
  border-radius: unset
  box-shadow: none

.grade-table__input
  padding: 0 5px

  :deep(.q-field__native)
    text-align: center
    margin: 0 auto

.grade-table__td-grade
  padding: 0
  min-width: 50px

.grade-table__td_hover
  background-color: rgba($negative, 0.3)
  cursor: pointer

  :deep(.q-field__native)
    cursor: pointer

.grade-table__cell
  text-align: center

.grade-table__cell_input
  padding: 0
</style>
