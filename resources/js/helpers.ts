import { CourseTable } from '@/types'
import { VisitOptions } from '@inertiajs/core/types/types'
import { router } from '@inertiajs/vue3'
import { Notify, date } from 'quasar'

export function difference<T>(arr1: T, arr2: T): T {
  return arr1.filter((x) => !arr2.includes(x))
}

export function inertiaFetch(url: string, options?: VisitOptions) {
  return new Promise((resolve, reject) => {
    router.visit(url, { preserveState: true, preserveScroll: true, ...options, onSuccess: resolve, onError: reject })
  })
}

export function onSave(promise: Promise<any>, scenario: 'create' | 'update' | 'delete' = 'update') {
  const labels = {
    create: 'Добавление',
    delete: 'Удаление',
    update: 'Сохранение',
  }

  promise
    .then(() => {
      Notify.create({
        type: 'positive',
        message: `${labels[scenario]} завершено.`,
      })
    })
    .catch(() => {
      Notify.create({ type: 'negative', message: 'Ошибка сохранения.' })
    })
}

export function downloadFile(url: string) {
  window.location.href = url
}

export function formatDate(value: string, fromFormat: string = 'YYYY-MM-DD', toFormat: string = 'DD.MM.YYYY'): string {
  return date.formatDate(date.extractDate(value, fromFormat), toFormat)
}

export function getCourseDate(course: CourseTable, month: number): Date {
  const instance = new Date(month >= 9 && month <= 12 ? course.start_education : course.end_education)
  instance.setDate(1)
  instance.setMonth(month - 1)
  return instance
}

export function getDaysInMonth(month: number, year: number): number {
  return new Date(year, month, 0).getDate()
}
