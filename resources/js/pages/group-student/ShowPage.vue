<template>
  <q-page padding>
    <Head :title />
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
        <q-markup-table class="q-mb-sm" separator="cell" wrap-cells>
          <thead>
            <tr>
              <th>Дата</th>
              <th>За какие достижения</th>
              <th>Форма поощрения</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="3">
                <q-btn icon="add" color="primary" size="sm" round />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
        <div>16. Факты ассоциального поведения учащегося</div>
        <q-markup-table class="q-mb-md" separator="cell" wrap-cells>
          <thead>
            <tr>
              <th>Дата</th>
              <th>Характер проявления</th>
              <th>Меры</th>
              <th>Результат</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="3">
                <q-btn icon="add" color="primary" size="sm" round />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
        <h3 class="text-h4 text-center q-mb-md">Рекомендации специалистов</h3>
        <q-markup-table class="q-mb-md" separator="cell" wrap-cells>
          <thead>
            <tr>
              <th>
                Рекомендации педагога-психолога, педагога социального<br />
                (психологическая диагностика, консультирование)
              </th>
              <th>Результат</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="3">
                <q-btn icon="add" color="primary" size="sm" round />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
        <h3 class="text-h4 text-center q-mb-md">
          Индивидуальная работа с родителями (другими законными представителями)
        </h3>
        <q-markup-table class="q-mb-md" separator="cell" wrap-cells>
          <thead>
            <tr>
              <th>Дата</th>
              <th>Содержание</th>
              <th>Результат</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="3">
                <q-btn icon="add" color="primary" size="sm" round />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
        <h3 class="text-h4 text-center q-mb-md">Индивидуальная работа с учащимися</h3>
        <q-markup-table separator="cell" wrap-cells>
          <thead>
            <tr>
              <th>Дата</th>
              <th>Содержание</th>
              <th>Результат</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-center" colspan="3">
                <q-btn icon="add" color="primary" size="sm" round />
              </td>
            </tr>
          </tbody>
        </q-markup-table>
      </div>
    </div>
  </q-page>
</template>

<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import StudentCardField from '@/components/StudentCardField.vue'
import { GroupModel, StudentModel } from '@/types'
import { formatDate } from '@/helpers'

const props = defineProps<{ group: GroupModel; student: StudentModel }>()

const title = 'Карта персонифицированного учета'
</script>
