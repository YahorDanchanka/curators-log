<template>
  <q-page padding>
    <Head :title="title" />
    <h1 class="text-h4 text-center q-mb-md">{{ title }}</h1>
    <div class="row items-center">
      <div class="col-1 text-center">
        <img
          class="personalization-card__avatar img-fluid"
          alt="Фото учащегося"
          :src="student.image_url ? student.image_url : 'https://dummyimage.com/100x100/000/fff'"
        />
      </div>
      <div class="col-11">
        <div class="row">
          <div class="col-6">
            <StudentCardField label="1. Фамилия" :value="student.surname" />
          </div>
          <div class="col-6">
            <StudentCardField label="2. Имя" :value="student.name" />
          </div>
          <div class="col-6">
            <StudentCardField label="3. Отчество" :value="student.patronymic" />
          </div>
          <div class="col-6">
            <StudentCardField
              label="4. Дата рождения"
              :value="student.birthday ? formatDate(student.birthday) : student.birthday"
            />
          </div>
        </div>
      </div>
      <div class="col-6">
        <StudentCardField label="5. Гражданство" :value="student.citizenship" />
      </div>
      <div class="col-6">
        <StudentCardField label="6. Окончил УО" :value="student.educational_institution" />
      </div>
      <div class="col-12">
        <StudentCardField label="7. Паспортные данные" :value="student.passport?.passport" />
        <StudentCardField
          label="8. Домашний адрес, телефон"
          :value="[student.address?.address, student.phone, student.home_phone].filter(Boolean).join(', ')"
        />
        <StudentCardField label="9. Место проживания в период обучения" :value="student.study_address?.address" />
        <StudentCardField label="10. Сведения о состоянии здоровья" :value="student.health" />
        <div>
          11. Сведения о семье: ФИО, дата рождения, гражданство, место жительства и (или) место пребывания, место работы
          родителей или других законных представителей
        </div>
        <StudentCardField
          label="Отец"
          :value="
            [
              `${student.father?.pivot?.type || ''} ${student.father?.full_name || ''}`.replace('отец ', ''),
              student.father?.address?.address,
              student.father?.job,
              student.father?.position,
              student.father?.phone,
            ]
              .filter(Boolean)
              .join(', ')
          "
        />
        <StudentCardField
          label="Мать"
          :value="
            [
              `${student.mother?.pivot?.type || ''} ${student.mother?.full_name || ''}`.replace('мать ', ''),
              student.mother?.address?.address,
              student.mother?.job,
              student.mother?.position,
              student.mother?.phone,
            ]
              .filter(Boolean)
              .join(', ')
          "
        />
        <StudentCardField
          label="Другие члены семьи"
          :value="
            props.student.minor_relatives
              ?.map((relative) =>
                [relative.full_name, formatDate(relative.birthday!), relative.educational_institution].join(', ')
              )
              .join('<br>')
          "
        />
        <StudentCardField
          label="12. Отношения учащегося с членами семьи, попечителем и др."
          :value="student.social_conditions"
        />
        <StudentCardField label="13. Увлечения" :value="student.hobbies" />
        <StudentCardField label="14. Другие сведения" :value="student.other_details" />
        <div>15. Поощрения учащегося</div>
        <StudentAchievementTable
          class="q-mb-sm"
          :achievements="props.student.achievements"
          @create="
            router.get(
              route('groups.students.achievements.create', { group: props.group.id, student: props.studentNumber })
            )
          "
          @edit="
            (achievement) =>
              router.get(
                route('groups.students.achievements.edit', {
                  group: props.group.id,
                  student: props.studentNumber,
                  achievement: achievement.id,
                })
              )
          "
          @delete="
            (achievement) =>
              onSave(StudentAchievementService.delete(props.group.id, props.studentNumber, achievement.id), 'delete')
          "
        />
        <div>16. Факты ассоциального поведения учащегося</div>
        <AsocialBehaviorTable
          class="q-mb-md"
          :asocial-behavior="props.student.asocial_behavior"
          @create="
            router.get(
              route('groups.students.asocial-behaviors.create', { group: props.group.id, student: props.studentNumber })
            )
          "
          @edit="
            (asocialBehavior) =>
              router.get(
                route('groups.students.asocial-behaviors.edit', {
                  group: props.group.id,
                  student: props.studentNumber,
                  asocial_behavior: asocialBehavior.id,
                })
              )
          "
          @delete="
            (asocialBehavior) =>
              onSave(AsocialBehaviorService.delete(props.group.id, props.studentNumber, asocialBehavior.id), 'delete')
          "
        />
        <h3 class="text-h4 text-center q-mb-md">Рекомендации специалистов</h3>
        <ExpertAdviceTable
          class="q-mb-md"
          :expert-advice="props.student.expert_advice"
          @create="
            router.get(
              route('groups.students.expert-advice.create', { group: props.group.id, student: props.studentNumber })
            )
          "
          @edit="
            (expertAdvice) =>
              router.get(
                route('groups.students.expert-advice.edit', {
                  group: props.group.id,
                  student: props.studentNumber,
                  expert_advice: expertAdvice.id,
                })
              )
          "
          @delete="
            (expertAdvice) =>
              onSave(ExpertAdviceService.delete(props.group.id, props.studentNumber, expertAdvice.id), 'delete')
          "
        />
        <h3 class="text-h4 text-center q-mb-md">
          Индивидуальная работа с родителями (другими законными представителями)
        </h3>
        <IndividualWorkTable
          class="q-mb-md"
          :individual-work="props.student.relative_individual_work"
          @create="
            router.get(
              route('groups.students.individual-works.create', { group: props.group.id, student: props.studentNumber })
            )
          "
          @edit="
            (individualWork) =>
              router.get(
                route('groups.students.individual-works.edit', {
                  group: props.group.id,
                  student: props.studentNumber,
                  individual_work: individualWork.id,
                })
              )
          "
          @delete="
            (individualWork) =>
              onSave(IndividualWorkService.delete(props.group.id, props.studentNumber, individualWork.id), 'delete')
          "
        />
        <h3 class="text-h4 text-center q-mb-md">Индивидуальная работа с учащимися</h3>
        <IndividualWorkTable
          class="q-mb-md"
          :individual-work="props.student.student_individual_work"
          @create="
            router.get(
              route('groups.students.individual-works.create', {
                group: props.group.id,
                student: props.studentNumber,
                type: 'student',
              })
            )
          "
          @edit="
            (individualWork) =>
              router.get(
                route('groups.students.individual-works.edit', {
                  group: props.group.id,
                  student: props.studentNumber,
                  individual_work: individualWork.id,
                })
              )
          "
          @delete="
            (individualWork) =>
              onSave(IndividualWorkService.delete(props.group.id, props.studentNumber, individualWork.id), 'delete')
          "
        />
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import AsocialBehaviorTable from '@/components/AsocialBehaviorTable.vue'
import ExpertAdviceTable from '@/components/ExpertAdviceTable.vue'
import IndividualWorkTable from '@/components/IndividualWorkTable.vue'
import StudentAchievementTable from '@/components/StudentAchievementTable.vue'
import StudentCardField from '@/components/StudentCardField.vue'
import { downloadFile, formatDate, onSave } from '@/helpers'
import {
  AsocialBehaviorService,
  ExpertAdviceService,
  IndividualWorkService,
  StudentAchievementService,
} from '@/services'
import { GroupModel, StudentModel } from '@/types'
import { Head, router } from '@inertiajs/vue3'
import { Required } from 'utility-types'
import route from 'ziggy-js'

const props = defineProps<{
  group: GroupModel
  student: Required<
    StudentModel,
    'achievements' | 'asocial_behavior' | 'expert_advice' | 'student_individual_work' | 'relative_individual_work'
  >
  studentNumber: string
}>()

const title = 'Карта персонифицированного учета'

document.addEventListener('print', () => {
  downloadFile(route('groups.students.print', { group: props.group.id, student_number: props.studentNumber }))
})
</script>
