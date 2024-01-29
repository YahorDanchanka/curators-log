import { router } from '@inertiajs/vue3'
import { Notify } from 'quasar'
import { VisitOptions } from '@inertiajs/core/types/types'

export function difference<T>(arr1: T, arr2: T): T {
  return arr1.filter((x) => !arr2.includes(x))
}

export function inertiaFetch(url: string, options?: VisitOptions) {
  return new Promise((resolve, reject) => {
    router.visit(url, { preserveState: true, preserveScroll: true, ...options, onSuccess: resolve, onError: reject })
  })
}

export function onSave(promise: Promise) {
  promise
    .then(() => {
      Notify.create({ type: 'positive', message: 'Сохранение завершено.' })
    })
    .catch(() => {
      Notify.create({ type: 'negative', message: 'Ошибка сохранения.' })
    })
}

export function downloadFile(url: string) {
  window.location.href = url
}
