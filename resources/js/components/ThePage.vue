<template>
  <q-page :style-fn="props.useHeight ? styleFunction : undefined">
    <q-breadcrumbs class="q-mb-md">
      <q-breadcrumbs-el
        v-for="breadcrumb in breadcrumbs"
        :label="breadcrumb.label"
        :class="{ 'cursor-pointer': isBreadcrumbActive(breadcrumb) }"
        :style="!isBreadcrumbActive(breadcrumb) ? { color: 'black' } : null"
        @click="isBreadcrumbActive(breadcrumb) ? redirect(breadcrumb.url!) : null"
      />
    </q-breadcrumbs>
    <slot />
  </q-page>
</template>

<script lang="ts" setup>
import { CourseModel, GradeReportModel, GroupModel, IBreadcrumb, IBreadcrumbs, StudentModel } from '@/types'
import { router, usePage } from '@inertiajs/vue3'
import { computed, reactive } from 'vue'
import route from 'ziggy-js'

const page = usePage<{
  group?: GroupModel
  course?: CourseModel
  student?: StudentModel
  studentNumber?: number
  gradeReport?: GradeReportModel
}>()
const props = defineProps<{ useHeight?: boolean }>()

const groupName = computed(() => page.props.course?.group_name ?? page.props.group?.name ?? '?')

const breadcrumbs = reactive<IBreadcrumbs>([
  { label: 'Группы', url: route('groups.index') },
  { label: groupName.value },
])

function styleFunction(offset: number) {
  return { height: offset ? `calc(100vh - ${offset}px)` : '100vh' }
}

function isRouteSame(url: string) {
  return url === window.location.href
}

function isBreadcrumbActive(breadcrumb: IBreadcrumb) {
  return !!breadcrumb.url && !isRouteSame(breadcrumb.url) && breadcrumbs[breadcrumbs.length - 1] !== breadcrumb
}

function redirect(url: string) {
  if (isRouteSame(url)) {
    return
  }

  router.get(url)
}

function addBreadcrumbsByRoute(routePrefix: string, indexBreadcrumb?: IBreadcrumb, showBreadcrumb?: IBreadcrumb) {
  if (route().current(`${routePrefix}.*`)) {
    const isRouteEdit = route().current(`${routePrefix}.edit`)

    if (indexBreadcrumb) {
      breadcrumbs.push(indexBreadcrumb)
    }

    if (route().current(`${routePrefix}.create`)) {
      breadcrumbs.push({ label: 'Создание' })
    }

    if (isRouteEdit || route().current(`${routePrefix}.show`)) {
      if (showBreadcrumb) {
        breadcrumbs.push(showBreadcrumb)
      }

      if (isRouteEdit) {
        breadcrumbs.push({ label: 'Редактирование' })
      }
    }
  }
}

router.on('navigate', () => {
  const groupRouteParams = { group: page.props.group?.id }
  const studentRouteParams = { group: page.props.group?.id, student: page.props.studentNumber }
  const courseRouteParams = { group: page.props.group?.id, course: page.props.course?.number }

  addBreadcrumbsByRoute(
    'groups.students',
    {
      label: 'Список учащихся',
      url: Object.values(groupRouteParams).every(Boolean)
        ? route('groups.students.index', groupRouteParams)
        : undefined,
    },
    {
      label: [page.props.student?.surname, page.props.student?.name, page.props.student?.patronymic]
        .filter(Boolean)
        .join(' '),
      url: Object.values(studentRouteParams).every(Boolean)
        ? route('groups.students.show', studentRouteParams)
        : undefined,
    }
  )

  addBreadcrumbsByRoute('groups.advice', {
    label: 'Замечания и предложения по организации идеологической и воспитательной работы ',
    url: Object.values(groupRouteParams).every(Boolean) ? route('groups.advice.index', groupRouteParams) : undefined,
  })

  addBreadcrumbsByRoute(
    'groups.courses.grade-reports',
    {
      label: 'Ведомости успеваемости группы',
      url: Object.values(courseRouteParams).every(Boolean)
        ? route('groups.courses.grade-reports.index', courseRouteParams)
        : undefined,
    },
    {
      label: page.props.gradeReport?.name ?? '',
    }
  )

  addBreadcrumbsByRoute('groups.interaction-with-parents', {
    label: 'Содержание взаимодействия с родителями (другими законными представителями) учащихся',
    url: Object.values(groupRouteParams).every(Boolean)
      ? route('groups.interaction-with-parents.index', groupRouteParams)
      : undefined,
  })

  addBreadcrumbsByRoute('groups.courses.achievements', {
    label: 'Достижения учебной группы',
    url: Object.values(courseRouteParams).every(Boolean)
      ? route('groups.courses.achievements.index', courseRouteParams)
      : undefined,
  })

  addBreadcrumbsByRoute('groups.courses.expulsions', {
    label: 'Отчисления за период обучения',
    url: Object.values(courseRouteParams).every(Boolean)
      ? route('groups.courses.expulsions.index', courseRouteParams)
      : undefined,
  })

  addBreadcrumbsByRoute('groups.family-attendances', {
    label: 'Учет посещаемости родителями (другими законными представителями) проводимых мероприятий',
  })

  addBreadcrumbsByRoute('groups.courses.leadership', {
    label: 'Актив учебной группы',
  })

  addBreadcrumbsByRoute('groups.courses.student-employment', {
    label: 'Занятость учащихся общественно полезной деятельностью',
  })

  addBreadcrumbsByRoute('groups.courses.reports', {
    label:
      'Отчет о выполнении плана воспитательной и идеологической работы куратора учебной группы, проведении внеплановых мероприятий',
  })

  addBreadcrumbsByRoute('groups.courses.plans', {
    label: 'План воспитательной и идеологической работы учебной группы № ПО-13',
  })

  addBreadcrumbsByRoute('groups.courses.education-level', {
    label: 'Результаты изучения уровня воспитанности учащихся',
  })

  addBreadcrumbsByRoute('groups.courses.socio-pedagogical-characteristic', {
    label: 'Социально-педагогическая характеристика',
  })

  addBreadcrumbsByRoute('groups.courses.other-characteristic', {
    label: 'Прочие характеристики',
  })
})
</script>
